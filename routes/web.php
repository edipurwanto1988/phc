<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController as PublicServiceController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\HalamanController as PublicHalamanController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceCategoryController as AdminServiceCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\HalamanController as AdminHalamanController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

// ==========================================
// GUEST / PUBLIC ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [PublicServiceController::class, 'index'])->name('public.services.index');
Route::get('/layanan/{slug}', [PublicServiceController::class, 'show'])->name('public.services.show');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('public.about');
Route::get('/kontak', [PublicContactController::class, 'index'])->name('public.contact');
Route::post('/kontak', [PublicContactController::class, 'submit'])->name('public.contact.submit');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('public.blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('public.blog.show');
Route::get('/halaman/{slug}', [PublicHalamanController::class, 'show'])->name('public.halaman.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Auth Guest
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google Socialite Login
Route::get('/login/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ==========================================
// CUSTOMER PORTAL ROUTES
// ==========================================
Route::middleware(['auth'])->prefix('customer')->group(function () {
    // Basic verification to ensure the user is indeed a customer
    Route::middleware(['role:Customer'])->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
        Route::post('/testimonials', [CustomerDashboardController::class, 'submitTestimonial'])->name('customer.testimonials.submit');
    });
});

// ==========================================
// ADMIN PORTAL ROUTES
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Only allow Admin/Staff/Super Admin to access the admin panel
    Route::middleware(['role:Super Admin,Admin,Staff,Cleaner'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // CRUD Routes
        Route::resource('/customers', AdminCustomerController::class)->names('admin.customers');
        Route::resource('/orders', AdminOrderController::class)->names('admin.orders');
        Route::resource('/expenses', \App\Http\Controllers\Admin\ExpenseController::class)->names('admin.expenses');
        Route::get('/expenses/{expense}/download-slip', [\App\Http\Controllers\Admin\ExpenseController::class, 'downloadSlip'])->name('admin.expenses.download-slip');
        Route::post('/services/reorder', [AdminServiceController::class, 'reorder'])->name('admin.services.reorder');
        Route::resource('/services', AdminServiceController::class)->names('admin.services');
        Route::resource('/service-categories', AdminServiceCategoryController::class)->names('admin.service-categories');
        Route::resource('/users', AdminUserController::class)->names('admin.users');
        Route::resource('/roles', AdminRoleController::class)->names('admin.roles');
        Route::resource('/posts', AdminPostController::class)->names('admin.posts');
        Route::resource('/testimonials', AdminTestimonialController::class)->names('admin.testimonials');
        Route::resource('/halaman', AdminHalamanController::class)->names('admin.halaman');
        Route::post('/menu/reorder', [AdminMenuController::class, 'reorder'])->name('admin.menu.reorder');
        Route::resource('/menu', AdminMenuController::class)->names('admin.menu');
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
        Route::get('/settings/gdrive/auth', [AdminSettingController::class, 'redirectToGoogleDrive'])->name('admin.settings.gdrive-auth');
        Route::get('/settings/gdrive/callback', [AdminSettingController::class, 'handleGoogleDriveCallback'])->name('admin.settings.gdrive-callback');
        Route::post('/settings/gdrive/test-upload', [AdminSettingController::class, 'testGDriveUpload'])->name('admin.settings.gdrive-test');
        Route::post('/settings/gdrive/disconnect', [AdminSettingController::class, 'disconnectGDrive'])->name('admin.settings.gdrive-disconnect');
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/detail', [AdminReportController::class, 'detail'])->name('admin.reports.detail');
        
        // Extra Assignment Route for orders
        Route::get('/orders/{order}/download-invoice', [AdminOrderController::class, 'downloadInvoice'])->name('admin.orders.download-invoice');
        Route::post('/orders/{order}/assign', [AdminOrderController::class, 'assignCleaner'])->name('admin.orders.assign');
        Route::post('/orders/assignments/{assignment}/gaji', [AdminOrderController::class, 'updateGaji'])->name('admin.orders.update-gaji');
        Route::post('/orders/assignments/{assignment}/photos', [AdminOrderController::class, 'uploadPhotos'])->name('admin.orders.upload-photos');
        Route::delete('/orders/assignments/{assignment}/photos/{type}', [AdminOrderController::class, 'deletePhoto'])->name('admin.orders.delete-photo');
        Route::delete('/orders/assignments/{assignment}', [AdminOrderController::class, 'deleteAssignment'])->name('admin.orders.delete-assignment');
        Route::post('/orders/{order}/assignments/reorder', [AdminOrderController::class, 'reorderAssignments'])->name('admin.orders.assignments-reorder');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{order}/coordinates', [AdminOrderController::class, 'updateCoordinates'])->name('admin.orders.coordinates');
    });
});
