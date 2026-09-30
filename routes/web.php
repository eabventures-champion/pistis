<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CampaignVideoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShopifySyncController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ─── Storefront ──────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Payment
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/paypal/create/{order}', [CheckoutController::class, 'createPayPalOrder'])->name('payment.paypal.create');
Route::post('/payment/paypal/capture/{order}', [CheckoutController::class, 'capturePayPalOrder'])->name('payment.paypal.capture');


// ─── Customer Auth ───────────────────────────────────────────────────
Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});
Route::redirect('/auth/login', '/login')->name('login');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout')
    ->middleware('auth:customer');

// ─── Customer Account ────────────────────────────────────────────────
Route::middleware('auth:customer')->prefix('account')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('account.index');
    Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderDetail'])->name('account.order-detail');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('account.update-profile');
});

// ─── Admin Auth ──────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// ─── Admin Panel ─────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::delete('/products/destroy-all', [ProductController::class, 'destroyAll'])->name('products.destroy-all');
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::resource('products', ProductController::class);

    // Categories
    Route::post('/categories/toggle-homepage-section', [CategoryController::class, 'toggleHomepageSection'])->name('categories.toggle-homepage-section');
    Route::post('/categories/toggle-editorial-badges', [CategoryController::class, 'toggleEditorialBadges'])->name('categories.toggle-editorial-badges');
    Route::delete('/categories/bulk-destroy', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');
    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    // Orders
    Route::post('/orders/bulk-action', [OrderController::class, 'bulkAction'])->name('orders.bulk-action');
    Route::post('/orders/{order}/archive', [OrderController::class, 'archive'])->name('orders.archive');
    Route::post('/orders/{order}/unarchive', [OrderController::class, 'unarchive'])->name('orders.unarchive');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Customers
    Route::post('/customers/bulk-action', [CustomerController::class, 'bulkAction'])->name('customers.bulk-action');
    Route::post('/customers/archive-all', [CustomerController::class, 'archiveAll'])->name('customers.archive-all');
    Route::post('/customers/unarchive-all', [CustomerController::class, 'unarchiveAll'])->name('customers.unarchive-all');
    Route::post('/customers/disable-all', [CustomerController::class, 'disableAll'])->name('customers.disable-all');
    Route::post('/customers/enable-all', [CustomerController::class, 'enableAll'])->name('customers.enable-all');
    Route::post('/customers/destroy-all', [CustomerController::class, 'destroyAll'])->name('customers.destroy-all');
    Route::post('/customers/{customer}/archive', [CustomerController::class, 'archive'])->name('customers.archive');
    Route::post('/customers/{customer}/unarchive', [CustomerController::class, 'unarchive'])->name('customers.unarchive');
    Route::post('/customers/{customer}/disable', [CustomerController::class, 'disable'])->name('customers.disable');
    Route::post('/customers/{customer}/enable', [CustomerController::class, 'enable'])->name('customers.enable');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    // Shopify Sync
    Route::post('/shopify/sync/{product}', [ShopifySyncController::class, 'syncProduct'])->name('shopify.sync-product');
    Route::post('/shopify/sync-all', [ShopifySyncController::class, 'syncAll'])->name('shopify.sync-all');
    Route::post('/shopify/pull', [ShopifySyncController::class, 'pullFromShopify'])->name('shopify.pull');
    Route::get('/shopify/logs', [ShopifySyncController::class, 'logs'])->name('shopify.logs');
    Route::post('/shopify/test-connection', [ShopifySyncController::class, 'testConnection'])->name('shopify.test-connection');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/remove-logo', [SettingsController::class, 'removeLogo'])->name('settings.remove-logo');

    // Campaign Video Management
    Route::get('/campaign-video', [CampaignVideoController::class, 'index'])->name('campaign-video.index');
    Route::post('/campaign-video', [CampaignVideoController::class, 'update'])->name('campaign-video.update');
    Route::delete('/campaign-video/remove-video', [CampaignVideoController::class, 'removeVideo'])->name('campaign-video.remove-video');

    // Hero Slides Management
    Route::post('/hero-slides/reorder', [HeroSlideController::class, 'reorder'])->name('hero-slides.reorder');
    Route::post('/hero-slides/{hero_slide}/toggle-status', [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle-status');
    Route::post('/hero-slides/{hero_slide}/duplicate', [HeroSlideController::class, 'duplicate'])->name('hero-slides.duplicate');
    Route::resource('hero-slides', HeroSlideController::class);
});
