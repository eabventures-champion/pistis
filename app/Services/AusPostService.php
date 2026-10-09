<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AusPostService
{
    protected string $apiKey;
    protected string $originPostcode;
    protected string $baseUrl;
    protected float $defaultShippingCost;

    /**
     * Map common country names to ISO 2-letter codes for AusPost International API
     */
    protected static array $countryCodeMap = [
        // Pacific / Oceania
        'australia' => 'AU', 'au' => 'AU',
        'new zealand' => 'NZ', 'nz' => 'NZ',
        'fiji' => 'FJ', 'fj' => 'FJ',
        'papua new guinea' => 'PG', 'pg' => 'PG',
        'samoa' => 'WS', 'ws' => 'WS',

        // North America
        'united states' => 'US', 'usa' => 'US', 'us' => 'US',
        'canada' => 'CA', 'ca' => 'CA',
        'mexico' => 'MX', 'mx' => 'MX',

        // Europe
        'united kingdom' => 'GB', 'uk' => 'GB', 'gb' => 'GB',
        'germany' => 'DE', 'de' => 'DE',
        'france' => 'FR', 'fr' => 'FR',
        'italy' => 'IT', 'it' => 'IT',
        'spain' => 'ES', 'es' => 'ES',
        'netherlands' => 'NL', 'nl' => 'NL',
        'switzerland' => 'CH', 'ch' => 'CH',
        'sweden' => 'SE', 'se' => 'SE',
        'norway' => 'NO', 'no' => 'NO',
        'denmark' => 'DK', 'dk' => 'DK',
        'ireland' => 'IE', 'ie' => 'IE',
        'belgium' => 'BE', 'be' => 'BE',
        'austria' => 'AT', 'at' => 'AT',
        'poland' => 'PL', 'pl' => 'PL',
        'portugal' => 'PT', 'pt' => 'PT',

        // Asia
        'japan' => 'JP', 'jp' => 'JP',
        'singapore' => 'SG', 'sg' => 'SG',
        'hong kong' => 'HK', 'hk' => 'HK',
        'south korea' => 'KR', 'kr' => 'KR',
        'china' => 'CN', 'cn' => 'CN',
        'taiwan' => 'TW', 'tw' => 'TW',
        'malaysia' => 'MY', 'my' => 'MY',
        'thailand' => 'TH', 'th' => 'TH',
        'indonesia' => 'ID', 'id' => 'ID',
        'philippines' => 'PH', 'ph' => 'PH',
        'india' => 'IN', 'in' => 'IN',
        'united arab emirates' => 'AE', 'uae' => 'AE', 'ae' => 'AE',

        // South America
        'brazil' => 'BR', 'br' => 'BR',
        'argentina' => 'AR', 'ar' => 'AR',
        'chile' => 'CL', 'cl' => 'CL',
        'colombia' => 'CO', 'co' => 'CO',
        'peru' => 'PE', 'pe' => 'PE',

        // Africa / Middle East
        'south africa' => 'ZA', 'za' => 'ZA',
    ];

    public function __construct()
    {
        $this->apiKey = Setting::get('auspost_api_key', config('services.auspost.api_key', '')) ?? '';
        $this->originPostcode = Setting::get('auspost_origin_postcode', config('services.auspost.origin_postcode', '2000')) ?? '2000';
        $this->baseUrl = rtrim(config('services.auspost.base_url', 'https://digitalapi.auspost.com.au/postage'), '/');
        $this->defaultShippingCost = (float) Setting::get('auspost_default_shipping_cost', config('services.auspost.default_shipping_cost', 12.00));
    }

    /**
     * Resolve ISO 2-letter code from input string
     */
    public function resolveCountryCode(?string $country): string
    {
        if (empty($country)) {
            return 'AU';
        }

        $clean = strtolower(trim($country));
        if (isset(self::$countryCodeMap[$clean])) {
            return self::$countryCodeMap[$clean];
        }

        // If it's already a 2-letter code
        if (strlen($clean) === 2) {
            return strtoupper($clean);
        }

        return 'AU';
    }

    /**
     * Check if destination is domestic Australia
     */
    public function isDomestic(string $country): bool
    {
        $code = $this->resolveCountryCode($country);
        return $code === 'AU';
    }

    /**
     * Get available shipping options for the given destination and weight
     *
     * @return array Array of options: [['code' => ..., 'name' => ..., 'cost' => float, 'delivery_time' => ...]]
     */
    public function getRates(string $country, ?string $postcode, float $weightInKg): array
    {
        $weightInKg = max(round($weightInKg, 2), 0.2); // minimum 200g

        if ($this->isDomestic($country)) {
            return $this->getDomesticRates($postcode, $weightInKg);
        } else {
            $countryCode = $this->resolveCountryCode($country);
            return $this->getInternationalRates($countryCode, $weightInKg);
        }
    }

    /**
     * Calculate Domestic Rates (Australia Post Parcel Post & Express Post)
     */
    public function getDomesticRates(?string $destinationPostcode, float $weightInKg): array
    {
        $cleanPostcode = trim($destinationPostcode ?? '');

        // If API key is not configured or postcode is invalid, return resilient fallback rates
        if (empty($this->apiKey) || empty($cleanPostcode) || !preg_match('/^[0-9]{4}$/', $cleanPostcode)) {
            return $this->getDomesticFallbackRates($weightInKg);
        }

        try {
            $response = Http::withHeaders([
                'AUTH-KEY' => $this->apiKey,
                'Accept'   => 'application/json',
            ])->timeout(5)->get("{$this->baseUrl}/parcel/domestic/service.json", [
                'from_postcode' => $this->originPostcode,
                'to_postcode'   => $cleanPostcode,
                'length'        => 22,
                'width'         => 16,
                'height'        => 8,
                'weight'        => $weightInKg,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $services = $data['services']['service'] ?? [];

                if (!empty($services)) {
                    $rates = [];
                    foreach ($services as $service) {
                        $code = $service['code'] ?? '';
                        $price = isset($service['price']) ? (float) $service['price'] : null;

                        // Focus on standard parcel and express post
                        if (in_array($code, ['AUS_PARCEL_REGULAR', 'AUS_PARCEL_EXPRESS']) || $price !== null) {
                            $rates[] = [
                                'code' => $code,
                                'name' => $service['name'] ?? 'Parcel Post',
                                'cost' => $price ?? $this->defaultShippingCost,
                                'delivery_time' => $service['delivery_time'] ?? '2–5 business days',
                            ];
                        }
                    }

                    if (!empty($rates)) {
                        return $rates;
                    }
                }
            } else {
                Log::warning('AusPost Domestic API returned non-200: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('AusPost Domestic API error: ' . $e->getMessage());
        }

        return $this->getDomesticFallbackRates($weightInKg);
    }

    /**
     * Calculate International Rates
     */
    public function getInternationalRates(string $countryCode, float $weightInKg): array
    {
        if (empty($this->apiKey) || empty($countryCode)) {
            return $this->getInternationalFallbackRates($countryCode, $weightInKg);
        }

        try {
            $response = Http::withHeaders([
                'AUTH-KEY' => $this->apiKey,
                'Accept'   => 'application/json',
            ])->timeout(5)->get("{$this->baseUrl}/parcel/international/service.json", [
                'country_code' => $countryCode,
                'weight'       => $weightInKg,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $services = $data['services']['service'] ?? [];

                if (!empty($services)) {
                    $rates = [];
                    foreach ($services as $service) {
                        $code = $service['code'] ?? '';
                        $price = isset($service['price']) ? (float) $service['price'] : null;

                        // Include standard and express international options
                        if (in_array($code, ['INT_PARCEL_STD_OWN_PACKAGING', 'INT_PARCEL_EXP_OWN_PACKAGING']) || $price !== null) {
                            $rates[] = [
                                'code' => $code,
                                'name' => $service['name'] ?? 'International Standard',
                                'cost' => $price ?? 25.00,
                                'delivery_time' => $service['delivery_time'] ?? '6–12 business days',
                            ];
                        }
                    }

                    if (!empty($rates)) {
                        return $rates;
                    }
                }
            } else {
                Log::warning('AusPost International API non-200: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('AusPost International API error: ' . $e->getMessage());
        }

        return $this->getInternationalFallbackRates($countryCode, $weightInKg);
    }

    /**
     * Graceful fallback rates for Australia domestic shipping
     */
    protected function getDomesticFallbackRates(float $weightInKg): array
    {
        // Standard AusPost base + incremental weight formula
        $baseStandard = 10.60;
        $baseExpress = 14.10;

        if ($weightInKg > 0.5) {
            $extra = ceil(($weightInKg - 0.5) * 2) * 2.50;
            $baseStandard += $extra;
            $baseExpress += $extra;
        }

        return [
            [
                'code' => 'AUS_PARCEL_REGULAR',
                'name' => 'Australia Post Standard',
                'cost' => round($baseStandard, 2),
                'delivery_time' => '2–5 business days',
            ],
            [
                'code' => 'AUS_PARCEL_EXPRESS',
                'name' => 'Australia Post Express',
                'cost' => round($baseExpress, 2),
                'delivery_time' => '1–2 business days',
            ]
        ];
    }

    /**
     * Graceful fallback rates for International shipping
     */
    protected function getInternationalFallbackRates(string $countryCode, float $weightInKg): array
    {
        $baseStd = 24.00;
        $baseExp = 39.00;

        if ($weightInKg > 1.0) {
            $extra = ($weightInKg - 1.0) * 8.00;
            $baseStd += $extra;
            $baseExp += $extra;
        }

        return [
            [
                'code' => 'INT_PARCEL_STD_OWN_PACKAGING',
                'name' => 'AusPost International Standard',
                'cost' => round($baseStd, 2),
                'delivery_time' => '6–10 business days',
            ],
            [
                'code' => 'INT_PARCEL_EXP_OWN_PACKAGING',
                'name' => 'AusPost International Express',
                'cost' => round($baseExp, 2),
                'delivery_time' => '3–6 business days',
            ]
        ];
    }
}
