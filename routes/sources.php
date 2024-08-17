<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Source
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/source',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{source:slug}', [
        'as' => 'playground.lead.resource.sources.slug',
        'uses' => 'SourceController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/sources',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.sources',
        'uses' => 'SourceController@index',
    ])->can('index', Playground\Lead\Models\Source::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.sources.index',
        'uses' => 'SourceController@index',
    ])->can('index', Playground\Lead\Models\Source::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.sources.create',
        'uses' => 'SourceController@create',
    ])->can('create', Playground\Lead\Models\Source::class);

    Route::get('/edit/{source}', [
        'as' => 'playground.lead.resource.sources.edit',
        'uses' => 'SourceController@edit',
    ])->whereUuid('source')->can('edit', 'source');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.sources.go',
    //     'uses' => 'SourceController@go',
    // ]);

    Route::get('/{source}', [
        'as' => 'playground.lead.resource.sources.show',
        'uses' => 'SourceController@show',
    ])->whereUuid('source')->can('detail', 'source');

    // API

    Route::put('/lock/{source}', [
        'as' => 'playground.lead.resource.sources.lock',
        'uses' => 'SourceController@lock',
    ])->whereUuid('source')->can('lock', 'source');

    Route::delete('/lock/{source}', [
        'as' => 'playground.lead.resource.sources.unlock',
        'uses' => 'SourceController@unlock',
    ])->whereUuid('source')->can('unlock', 'source');

    Route::delete('/{source}', [
        'as' => 'playground.lead.resource.sources.destroy',
        'uses' => 'SourceController@destroy',
    ])->whereUuid('source')->can('delete', 'source')->withTrashed();

    Route::put('/restore/{source}', [
        'as' => 'playground.lead.resource.sources.restore',
        'uses' => 'SourceController@restore',
    ])->whereUuid('source')->can('restore', 'source')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.sources.post',
        'uses' => 'SourceController@store',
    ])->can('store', Playground\Lead\Models\Source::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.sources.put',
    //     'uses' => 'SourceController@store',
    // ])->can('store', Playground\Lead\Models\Source::class);
    //
    // Route::put('/{source}', [
    //     'as' => 'playground.lead.resource.sources.put.id',
    //     'uses' => 'SourceController@store',
    // ])->whereUuid('source')->can('update', 'source');

    Route::patch('/{source}', [
        'as' => 'playground.lead.resource.sources.patch',
        'uses' => 'SourceController@update',
    ])->whereUuid('source')->can('update', 'source');
});
