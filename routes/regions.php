<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Routes: Region
|--------------------------------------------------------------------------
|
|
*/
Route::group([
    'prefix' => 'resource/lead/region',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{region:slug}', [
        'as' => 'playground.lead.resource.regions.slug',
        'uses' => 'RegionController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/regions',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.regions',
        'uses' => 'RegionController@index',
    ])->can('index', Playground\Lead\Models\Region::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.regions.create',
        'uses' => 'RegionController@create',
    ])->can('create', Playground\Lead\Models\Region::class);

    Route::get('/edit/{region}', [
        'as' => 'playground.lead.resource.regions.edit',
        'uses' => 'RegionController@edit',
    ])->whereUuid('region')
        ->can('edit', 'region');

    // Route::get('/go/{id}', [
    //     'as'   => 'playground.lead.resource.regions.go',
    //     'uses' => 'RegionController@go',
    // ]);

    Route::get('/{region}', [
        'as' => 'playground.lead.resource.regions.show',
        'uses' => 'RegionController@show',
    ])->whereUuid('region')
        ->can('detail', 'region');

    // Resource

    Route::put('/lock/{region}', [
        'as' => 'playground.lead.resource.regions.lock',
        'uses' => 'RegionController@lock',
    ])->whereUuid('region')
        ->can('lock', 'region');

    Route::delete('/lock/{region}', [
        'as' => 'playground.lead.resource.regions.unlock',
        'uses' => 'RegionController@unlock',
    ])->whereUuid('region')
        ->can('unlock', 'region');

    Route::delete('/{region}', [
        'as' => 'playground.lead.resource.regions.destroy',
        'uses' => 'RegionController@destroy',
    ])->whereUuid('region')
        ->can('delete', 'region')
        ->withTrashed();

    Route::put('/restore/{region}', [
        'as' => 'playground.lead.resource.regions.restore',
        'uses' => 'RegionController@restore',
    ])->whereUuid('region')
        ->can('restore', 'region')
        ->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.regions.post',
        'uses' => 'RegionController@store',
    ])->can('store', Playground\Lead\Models\Region::class);

    // Route::put('/', [
    //     'as'   => 'playground.lead.resource.regions.put',
    //     'uses' => 'RegionController@store',
    // ])->can('store', \Playground\Lead\Models\Region::class);
    //
    // Route::put('/{region}', [
    //     'as'   => 'playground.lead.resource.regions.put.id',
    //     'uses' => 'RegionController@store',
    // ])->whereUuid('region')->can('update', 'region');

    Route::patch('/{region}', [
        'as' => 'playground.lead.resource.regions.patch',
        'uses' => 'RegionController@update',
    ])->whereUuid('region')->can('update', 'region');
});
