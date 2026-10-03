<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductTypeController as AdminProductTypeController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\Storefront\AboutController;
use App\Http\Controllers\Storefront\CategoryController;
use App\Http\Controllers\Storefront\ContactController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\InquiryController;
use App\Http\Controllers\Storefront\LegalController;
use App\Http\Controllers\Storefront\ProductCatalogController;
use App\Http\Controllers\Storefront\ProductDetailController;
use App\Http\Controllers\Storefront\ProductTypeController;
use App\Http\Controllers\Storefront\SitemapController;
use App\Http\Middleware\EnsureAdminRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/products', [ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('catalog.category');
Route::get('/category/{categorySlug}/{typeSlug}', [ProductTypeController::class, 'show'])->name('catalog.product_type');
Route::get('/products/{slug}', [ProductDetailController::class, 'show'])->name('catalog.product');

Route::post('/inquiries/general', [InquiryController::class, 'storeGeneral'])
    ->middleware('throttle:10,1')
    ->name('inquiry.general');

Route::post('/inquiries/quote', [InquiryController::class, 'storeQuote'])
    ->middleware('throttle:10,1')
    ->name('inquiry.quote');

Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms-of-trade', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/health', [HealthCheckController::class, 'check'])->name('health');

/*
|--------------------------------------------------------------------------
| Protected Administration Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Auth
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated Admin Dashboard & Operations
    Route::middleware(['auth', EnsureAdminRole::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Catalog Management (Super Admin & Catalog Manager)
        Route::middleware(EnsureAdminRole::class . ':super_admin,catalog_manager')->group(function () {
            Route::resource('categories', AdminCategoryController::class)->except(['show']);
            Route::resource('product-types', AdminProductTypeController::class)->except(['show']);
            Route::resource('products', AdminProductController::class);
            Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle_status');
        });

        // Inquiries Management (Super Admin & Inquiry Manager)
        Route::middleware(EnsureAdminRole::class . ':super_admin,inquiry_manager')->group(function () {
            Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
            Route::get('inquiries/export', [AdminInquiryController::class, 'exportCsv'])->name('inquiries.export');
            Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
            Route::post('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.update_status');
            Route::post('inquiries/{inquiry}/note', [AdminInquiryController::class, 'addNote'])->name('inquiries.add_note');
        });

        // CMS Content Management (Super Admin & Content Editor)
        Route::middleware(EnsureAdminRole::class . ':super_admin,content_editor')->group(function () {
            Route::get('cms', [CmsController::class, 'index'])->name('cms.index');
            Route::get('cms/{cmsSection}/edit', [CmsController::class, 'edit'])->name('cms.edit');
            Route::put('cms/{cmsSection}', [CmsController::class, 'update'])->name('cms.update');
        });

        // Audit Logs (Super Admin only)
        Route::middleware(EnsureAdminRole::class . ':super_admin')->group(function () {
            Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
        });
    });
});
