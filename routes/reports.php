<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Report
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/report',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{report:slug}', [
        'as' => 'playground.lead.resource.reports.slug',
        'uses' => 'ReportController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/reports',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.reports',
        'uses' => 'ReportController@index',
    ])->can('index', Playground\Lead\Models\Report::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.reports.index',
        'uses' => 'ReportController@index',
    ])->can('index', Playground\Lead\Models\Report::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.reports.create',
        'uses' => 'ReportController@create',
    ])->can('create', Playground\Lead\Models\Report::class);

    Route::get('/edit/{report}', [
        'as' => 'playground.lead.resource.reports.edit',
        'uses' => 'ReportController@edit',
    ])->whereUuid('report')->can('edit', 'report');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.reports.go',
    //     'uses' => 'ReportController@go',
    // ]);

    Route::get('/{report}', [
        'as' => 'playground.lead.resource.reports.show',
        'uses' => 'ReportController@show',
    ])->whereUuid('report')->can('detail', 'report');

    // API

    Route::put('/lock/{report}', [
        'as' => 'playground.lead.resource.reports.lock',
        'uses' => 'ReportController@lock',
    ])->whereUuid('report')->can('lock', 'report');

    Route::delete('/lock/{report}', [
        'as' => 'playground.lead.resource.reports.unlock',
        'uses' => 'ReportController@unlock',
    ])->whereUuid('report')->can('unlock', 'report');

    Route::delete('/{report}', [
        'as' => 'playground.lead.resource.reports.destroy',
        'uses' => 'ReportController@destroy',
    ])->whereUuid('report')->can('delete', 'report')->withTrashed();

    Route::put('/restore/{report}', [
        'as' => 'playground.lead.resource.reports.restore',
        'uses' => 'ReportController@restore',
    ])->whereUuid('report')->can('restore', 'report')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.reports.post',
        'uses' => 'ReportController@store',
    ])->can('store', Playground\Lead\Models\Report::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.reports.put',
    //     'uses' => 'ReportController@store',
    // ])->can('store', Playground\Lead\Models\Report::class);
    //
    // Route::put('/{report}', [
    //     'as' => 'playground.lead.resource.reports.put.id',
    //     'uses' => 'ReportController@store',
    // ])->whereUuid('report')->can('update', 'report');

    Route::patch('/{report}', [
        'as' => 'playground.lead.resource.reports.patch',
        'uses' => 'ReportController@update',
    ])->whereUuid('report')->can('update', 'report');
});
