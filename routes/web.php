<?php

use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LeadFormController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\StripeCheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', fn () => redirect()->route('install.welcome'))->name('index');
    Route::get('/welcome', [InstallController::class, 'welcome'])->name('welcome');
    Route::match(['get', 'post'], '/database', [InstallController::class, 'database'])->name('database');
    Route::post('/test-database', [InstallController::class, 'testDatabase'])->name('test-database');
    Route::get('/administrator', [InstallController::class, 'administrator'])->name('administrator');
    Route::post('/run', [InstallController::class, 'run'])->name('run');
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});

Route::get('/', HomeController::class)->name('home');
Route::get('/p/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/contact', [LeadFormController::class, 'contact'])->name('contact');
Route::post('/contact', [LeadFormController::class, 'storeContact'])->name('contact.store');
Route::get('/quote', [LeadFormController::class, 'quote'])->name('quote');
Route::post('/quote', [LeadFormController::class, 'storeQuote'])->name('quote.store');

Route::get('/checkout/{package}', [StripeCheckoutController::class, 'create'])->name('checkout.create');
Route::get('/checkout/success', [StripeCheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeCheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::post('/webhook/stripe', [StripeCheckoutController::class, 'webhook'])->name('webhook.stripe')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('leads', AdminLeadController::class)->except(['create', 'store', 'edit']);
    Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
    Route::post('clients/{client}/invite', [\App\Http\Controllers\Admin\ClientInviteController::class, 'store'])->name('clients.invite');
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    Route::post('projects/{project}/documents', [\App\Http\Controllers\Admin\ProjectDocumentController::class, 'store'])->name('projects.documents.store');
    Route::delete('projects/{project}/documents/{document}', [\App\Http\Controllers\Admin\ProjectDocumentController::class, 'destroy'])->name('projects.documents.destroy');
    Route::get('projects/{project}/documents/{document}/download', [\App\Http\Controllers\Admin\ProjectDocumentController::class, 'download'])->name('projects.documents.download');
    Route::post('projects/{project}/messages', [\App\Http\Controllers\Admin\ProjectMessageController::class, 'store'])->name('projects.messages.store');
    Route::post('projects/{project}/invoices', [\App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('projects.invoices.store');
    Route::post('projects/{project}/invoices/{invoice}/send-payment-link', [\App\Http\Controllers\Admin\InvoiceController::class, 'sendPaymentLink'])->name('projects.invoices.send-payment-link');
    Route::resource('packages', AdminPackageController::class);
    Route::resource('publications', AdminPublicationController::class)->except(['show']);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('throttle:10,1');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('modules', [\App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('modules.index');
    Route::post('modules/enable', [\App\Http\Controllers\Admin\ModuleController::class, 'enable'])->name('modules.enable');
    Route::post('modules/disable', [\App\Http\Controllers\Admin\ModuleController::class, 'disable'])->name('modules.disable');
});

Route::get('/invoice/success', [\App\Http\Controllers\Admin\InvoiceController::class, 'success'])->name('invoice.success');

Route::prefix('portal')->name('portal.')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\ClientPortal\PortalController::class, 'index'])->name('index');
    Route::get('/projects/{project}', [\App\Http\Controllers\ClientPortal\PortalController::class, 'show'])->name('project');
    Route::post('/projects/{project}/documents', [\App\Http\Controllers\ClientPortal\PortalController::class, 'storeDocument'])->name('projects.documents.store');
    Route::get('/projects/{project}/documents/{document}/download', [\App\Http\Controllers\ClientPortal\PortalController::class, 'downloadDocument'])->name('projects.documents.download');
    Route::post('/projects/{project}/messages', [\App\Http\Controllers\ClientPortal\PortalController::class, 'storeMessage'])->name('projects.messages.store');
});

require __DIR__.'/auth.php';
