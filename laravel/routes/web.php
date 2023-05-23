<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Helpers\Settings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$settings = new Settings(['popular-documents', 'new-documents', 'document', 'search']);

Route::get('/', [Controllers\Main\HomeController::class, 'index'])->name('index');

Route::get('/' . $settings->get('popular-documents.permalink', 'top-documents'), [Controllers\Main\Content\DocumentsController::class, 'popularDocuments'])->name('popular-documents');
Route::get('/' . $settings->get('new-documents.permalink', 'new-documents'), [Controllers\Main\Content\DocumentsController::class, 'newDocuments'])->name('new-documents');

Route::get('/' . $settings->get('document.permalink', 'document'), function () {
    return redirect()->route('index');
});
Route::get('/' . $settings->get('document.permalink', 'document') . '/{document}', [Controllers\Main\Content\DocumentController::class, 'index'])->name('document');

Route::get('/download', function () {
    return redirect()->route('index');
});
Route::match(['get', 'post'], '/download/{document}', [Controllers\Main\Content\DocumentController::class, 'download'])->name('download');

Route::get('/get-file/{document?}', function () {
    return redirect()->route('index');
});
Route::post('/get-file/{document}', [Controllers\Main\Content\DocumentController::class, 'getFile'])->name('get-file');

Route::get('/' . $settings->get('search.permalink'), [Controllers\Main\Content\SearchController::class, 'index'])->name('search');

Route::get('/p/{page}', [Controllers\Main\Misc\PageController::class, 'page'])->name('page');
Route::get('/contact', [Controllers\Main\Misc\ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [Controllers\Main\Misc\ContactController::class, 'send']);

Route::get('/sitemaps-index.xml', [Controllers\Main\Misc\SitemapController::class, 'index']);
Route::get('/sitemap-documents-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapDocuments'])->name('sitemap.documents');

Route::prefix('settings')->name('account.account-settings.')->middleware(['auth'])->group(function () {
    Route::get('/', [Controllers\Main\Account\AccountSettingsController::class, 'index'])->name('index');

    Route::get('/username', [Controllers\Main\Account\UsernameController::class, 'edit'])->name('username.edit');
    Route::put('/username', [Controllers\Main\Account\UsernameController::class, 'update'])->name('username.update');

    Route::get('/email', [Controllers\Main\Account\EmailController::class, 'edit'])->name('email.edit');
    Route::put('/email', [Controllers\Main\Account\EmailController::class, 'update'])->name('email.update');

    Route::get('/password', [Controllers\Main\Account\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [Controllers\Main\Account\PasswordController::class, 'update'])->name('password.update');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';