<?php
/**
 * Updated: 20 August 2026
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController as PublicServiceController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\HalamanController as PublicHalamanController;
use App\Http\Controllers\Public\OrderProgressController as PublicOrderProgressController;
use App\Http\Controllers\Public\LokerController as PublicLokerController;

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
use App\Http\Controllers\Admin\OrderProgressController as AdminOrderProgressController;
use App\Http\Controllers\Admin\LokerController as AdminLokerController;
use App\Http\Controllers\Admin\PeriodeLokerController as AdminPeriodeLokerController;

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

// Loker (lowongan kerja / lamaran)
Route::get('/loker', [PublicLokerController::class, 'index'])->name('public.loker.index');
Route::post('/loker', [PublicLokerController::class, 'submit'])->name('public.loker.submit');

// Progress pekerjaan (link publik untuk client)
Route::get('/progress/{token}', [PublicOrderProgressController::class, 'show'])->name('public.progress.show');
Route::get('/progress/{token}/bukti/{bukti}', [PublicOrderProgressController::class, 'viewBukti'])->name('public.progress.bukti.view');
Route::get('/progress/{token}/cleaner/{cleaner}/foto', [PublicOrderProgressController::class, 'viewCleanerFoto'])->name('public.progress.cleaner.foto');

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
        Route::get('/users/{user}/foto/view', [AdminUserController::class, 'viewFoto'])->name('admin.users.foto.view');
        Route::delete('/users/{user}/foto', [AdminUserController::class, 'deleteFoto'])->name('admin.users.foto.destroy');
        Route::resource('/roles', AdminRoleController::class)->names('admin.roles');
        Route::resource('/posts', AdminPostController::class)->names('admin.posts');
        Route::resource('/testimonials', AdminTestimonialController::class)->names('admin.testimonials');
        Route::resource('/loker', AdminLokerController::class)->names('admin.loker');
        Route::get('/loker/{loker}/ktp/view', [AdminLokerController::class, 'viewKtp'])->name('admin.loker.ktp.view');
        Route::resource('/periode-loker', AdminPeriodeLokerController::class)->names('admin.periode-loker');
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
        Route::get('/orders/{order}/profit', [AdminOrderController::class, 'profit'])->name('admin.orders.profit');
        Route::get('/orders/{order}/download-invoice', [AdminOrderController::class, 'downloadInvoice'])->name('admin.orders.download-invoice');
        Route::get('/orders/payments/{payment}/download-invoice', [AdminOrderController::class, 'downloadPaymentInvoice'])->name('admin.orders.payments.download-invoice');
        Route::post('/orders/{order}/assign', [AdminOrderController::class, 'assignCleaner'])->name('admin.orders.assign');
        Route::post('/orders/assignments/{assignment}/gaji', [AdminOrderController::class, 'updateGaji'])->name('admin.orders.update-gaji');
        Route::post('/orders/assignments/{assignment}/photos', [AdminOrderController::class, 'uploadPhotos'])->name('admin.orders.upload-photos');
        Route::get('/orders/assignments/{assignment}/photos/{type}/view', [AdminOrderController::class, 'viewDrivePhoto'])->name('admin.orders.view-drive-photo');
        Route::delete('/orders/assignments/{assignment}/photos/{type}', [AdminOrderController::class, 'deletePhoto'])->name('admin.orders.delete-photo');

        // Absensi Routes
        Route::prefix('/orders/{order}/absensi')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderAttendanceController::class, 'index'])->name('admin.orders.absensi.index');
            Route::post('/schedules', [\App\Http\Controllers\Admin\OrderAttendanceController::class, 'storeSchedule'])->name('admin.orders.absensi.storeSchedule');
            Route::delete('/schedules/{schedule}', [\App\Http\Controllers\Admin\OrderAttendanceController::class, 'destroySchedule'])->name('admin.orders.absensi.destroySchedule');
            Route::post('/schedules/{schedule}/attend', [\App\Http\Controllers\Admin\OrderAttendanceController::class, 'storeAttendance'])->name('admin.orders.absensi.storeAttendance');
            Route::patch('/attendances/{attendance}', [\App\Http\Controllers\Admin\OrderAttendanceController::class, 'updateStatus'])->name('admin.orders.absensi.updateStatus');
        });
        Route::delete('/orders/assignments/{assignment}', [AdminOrderController::class, 'deleteAssignment'])->name('admin.orders.delete-assignment');
        Route::post('/orders/{order}/assignments/reorder', [AdminOrderController::class, 'reorderAssignments'])->name('admin.orders.assignments-reorder');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{order}/payments', [AdminOrderController::class, 'storePayment'])->name('admin.orders.payments.store');
        Route::put('/orders/payments/{payment}', [AdminOrderController::class, 'updatePayment'])->name('admin.orders.payments.update');
        Route::delete('/orders/payments/{payment}', [AdminOrderController::class, 'destroyPayment'])->name('admin.orders.payments.destroy');
        Route::post('/orders/{order}/coordinates', [AdminOrderController::class, 'updateCoordinates'])->name('admin.orders.coordinates');
        Route::post('/orders/{order}/catatan', [AdminOrderController::class, 'updateCatatan'])->name('admin.orders.catatan');

        // Progress Pekerjaan (lantai & ruangan) — halaman khusus
        Route::get('/progress/{order}', [AdminOrderProgressController::class, 'show'])->name('admin.progress.show');
        Route::post('/progress/{order}/rooms', [AdminOrderProgressController::class, 'storeRoom'])->name('admin.progress.rooms.store');
        Route::post('/progress/{order}/rooms/import', [AdminOrderProgressController::class, 'importRooms'])->name('admin.progress.rooms.import');
        Route::put('/progress/{order}/rooms/{room}', [AdminOrderProgressController::class, 'updateRoom'])->name('admin.progress.rooms.update');
        Route::delete('/progress/{order}/rooms/{room}', [AdminOrderProgressController::class, 'destroyRoom'])->name('admin.progress.rooms.destroy');
        Route::post('/progress/{order}/rooms/{room}/bukti', [AdminOrderProgressController::class, 'uploadBukti'])->name('admin.progress.rooms.bukti.upload');
        Route::delete('/progress/{order}/rooms/{room}/bukti/{bukti}', [AdminOrderProgressController::class, 'deleteBukti'])->name('admin.progress.rooms.bukti.destroy');
        Route::get('/progress/{order}/rooms/{room}/bukti/{bukti}/view', [AdminOrderProgressController::class, 'viewBukti'])->name('admin.progress.rooms.bukti.view');
        Route::post('/progress/{order}/link', [AdminOrderProgressController::class, 'generateLink'])->name('admin.progress.link');
    });
});
