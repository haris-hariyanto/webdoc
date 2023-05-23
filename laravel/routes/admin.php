<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\App;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    App::setLocale('id');

    Route::get('/', function () {
        return redirect()->route('admin.documents.index');
    })->name('index');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('website', [Controllers\Admin\Settings\WebsiteSettings::class, 'index'])->name('website');
        Route::put('website', [Controllers\Admin\Settings\WebsiteSettings::class, 'save']);

        Route::get('/home', [Controllers\Admin\Settings\Pages\HomeSettings::class, 'index'])->name('home');
        Route::put('/home', [Controllers\Admin\Settings\Pages\HomeSettings::class, 'save']);

        Route::get('/popular-documents', [Controllers\Admin\Settings\Pages\PopularDocumentsSettings::class, 'index'])->name('popular-documents');
        Route::put('/popular-documents', [Controllers\Admin\Settings\Pages\PopularDocumentsSettings::class, 'save']);

        Route::get('/new-documents', [Controllers\Admin\Settings\Pages\NewDocumentsSettings::class, 'index'])->name('new-documents');
        Route::put('/new-documents', [Controllers\Admin\Settings\Pages\NewDocumentsSettings::class, 'save']);

        Route::get('/document', [Controllers\Admin\Settings\Pages\DocumentSettings::class, 'index'])->name('document');
        Route::put('/document', [Controllers\Admin\Settings\Pages\DocumentSettings::class, 'save']);

        Route::get('/search', [Controllers\Admin\Settings\Pages\SearchSettings::class, 'index'])->name('search');
        Route::put('/search', [Controllers\Admin\Settings\Pages\SearchSettings::class, 'save']);

        Route::get('/sitemap', [Controllers\Admin\Settings\Pages\SitemapSettings::class, 'index'])->name('sitemap');
        Route::put('/sitemap', [Controllers\Admin\Settings\Pages\SitemapSettings::class, 'save']);

        Route::get('/academia', [Controllers\Admin\Settings\Scrapers\AcademiaSettings::class, 'index'])->name('scrapers.academia');
        Route::put('/academia', [Controllers\Admin\Settings\Scrapers\AcademiaSettings::class, 'save']);

        Route::get('/proxy', [Controllers\Admin\Settings\Scrapers\ProxySettings::class, 'index'])->name('scrapers.proxy');
        Route::put('/proxy', [Controllers\Admin\Settings\Scrapers\ProxySettings::class, 'save']);

        Route::get('/scribd', [Controllers\Admin\Settings\Scrapers\ScribdSettings::class, 'index'])->name('scrapers.scribd');
        Route::put('/scribd', [Controllers\Admin\Settings\Scrapers\ScribdSettings::class, 'save']);
    });

    Route::delete('/disks-bulk-delete', [Controllers\Admin\DiskController::class, 'bulkDelete'])->name('disks.bulk-delete');
    Route::resource('disks', Controllers\Admin\DiskController::class);
    Route::get('/disks-index.json', [Controllers\Admin\DiskController::class, 'indexData'])->name('disks.index.data');

    Route::delete('/documents-bulk-delete', [Controllers\Admin\DocumentController::class, 'bulkDelete'])->name('documents.bulk-delete');
    Route::resource('documents', Controllers\Admin\DocumentController::class)->except([
        'create', 'store',
    ]);
    Route::get('/documents-index.json', [Controllers\Admin\DocumentController::class, 'indexData'])->name('documents.index.data');

    Route::put('/scrape-queues-bulk-add-priotity', [Controllers\Admin\KeywordController::class, 'addPriority'])->name('keywords.bulk-add-priority');
    Route::put('/scrape-queues-bulk-remove-priority', [Controllers\Admin\KeywordController::class, 'removePriority'])->name('keywords.bulk-remove-priority');
    Route::delete('/keywords-bulk-delete', [Controllers\Admin\KeywordController::class, 'bulkDelete'])->name('keywords.bulk-delete');
    Route::put('/scrape-queues-reset-failed', [Controllers\Admin\KeywordController::class, 'resetFailed'])->name('keywords.reset-failed');
    Route::resource('/keywords', Controllers\Admin\KeywordController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/keywords-index.json', [Controllers\Admin\KeywordController::class, 'indexData'])->name('keywords.index.data');

    Route::resource('users', Controllers\Admin\UserController::class);
    Route::get('/users-index.json', [Controllers\Admin\UserController::class, 'indexData'])->name('users.index.data');
    Route::get('/users/{user}/password', [Controllers\Admin\UserController::class, 'editPassword'])->name('users.password.edit');
    Route::put('/users/{user}/password', [Controllers\Admin\UserController::class, 'updatePassword'])->name('users.password.update');

    Route::resource('pages', Controllers\Admin\PageController::class);
    Route::get('/pages-index.json', [Controllers\Admin\PageController::class, 'indexData'])->name('pages.index.data');

    Route::resource('contacts', Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('/contacts-index.json', [Controllers\Admin\ContactController::class, 'indexData'])->name('contacts.index.data');
    Route::put('/contacts/{contact}/toggle-status', [Controllers\Admin\ContactController::class, 'toggleStatus'])->name('contacts.toggle-status');
});