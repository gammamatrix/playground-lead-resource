<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Routes: Teammate
|--------------------------------------------------------------------------
|
|
*/
Route::group([
    'prefix' => 'resource/lead/teammate',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{teammate:slug}', [
        'as' => 'playground.lead.resource.teammates.slug',
        'uses' => 'TeammateController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/teammates',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.teammates',
        'uses' => 'TeammateController@index',
    ])->can('index', Playground\Lead\Models\Teammate::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.teammates.create',
        'uses' => 'TeammateController@create',
    ])->can('create', Playground\Lead\Models\Teammate::class);

    Route::get('/edit/{teammate}', [
        'as' => 'playground.lead.resource.teammates.edit',
        'uses' => 'TeammateController@edit',
    ])->whereUuid('teammate')
        ->can('edit', 'teammate');

    // Route::get('/go/{id}', [
    //     'as'   => 'playground.lead.resource.teammates.go',
    //     'uses' => 'TeammateController@go',
    // ]);

    Route::get('/{teammate}', [
        'as' => 'playground.lead.resource.teammates.show',
        'uses' => 'TeammateController@show',
    ])->whereUuid('teammate')
        ->can('detail', 'teammate');

    // Resource

    Route::put('/lock/{teammate}', [
        'as' => 'playground.lead.resource.teammates.lock',
        'uses' => 'TeammateController@lock',
    ])->whereUuid('teammate')
        ->can('lock', 'teammate');

    Route::delete('/lock/{teammate}', [
        'as' => 'playground.lead.resource.teammates.unlock',
        'uses' => 'TeammateController@unlock',
    ])->whereUuid('teammate')
        ->can('unlock', 'teammate');

    Route::delete('/{teammate}', [
        'as' => 'playground.lead.resource.teammates.destroy',
        'uses' => 'TeammateController@destroy',
    ])->whereUuid('teammate')
        ->can('delete', 'teammate')
        ->withTrashed();

    Route::put('/restore/{teammate}', [
        'as' => 'playground.lead.resource.teammates.restore',
        'uses' => 'TeammateController@restore',
    ])->whereUuid('teammate')
        ->can('restore', 'teammate')
        ->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.teammates.post',
        'uses' => 'TeammateController@store',
    ])->can('store', Playground\Lead\Models\Teammate::class);

    // Route::put('/', [
    //     'as'   => 'playground.lead.resource.teammates.put',
    //     'uses' => 'TeammateController@store',
    // ])->can('store', \Playground\Lead\Models\Teammate::class);
    //
    // Route::put('/{teammate}', [
    //     'as'   => 'playground.lead.resource.teammates.put.id',
    //     'uses' => 'TeammateController@store',
    // ])->whereUuid('teammate')->can('update', 'teammate');

    Route::patch('/{teammate}', [
        'as' => 'playground.lead.resource.teammates.patch',
        'uses' => 'TeammateController@update',
    ])->whereUuid('teammate')->can('update', 'teammate');
});
