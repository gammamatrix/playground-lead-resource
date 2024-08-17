<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Plan
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/plan',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{plan:slug}', [
        'as' => 'playground.lead.resource.plans.slug',
        'uses' => 'PlanController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/plans',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.plans',
        'uses' => 'PlanController@index',
    ])->can('index', Playground\Lead\Models\Plan::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.plans.index',
        'uses' => 'PlanController@index',
    ])->can('index', Playground\Lead\Models\Plan::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.plans.create',
        'uses' => 'PlanController@create',
    ])->can('create', Playground\Lead\Models\Plan::class);

    Route::get('/edit/{plan}', [
        'as' => 'playground.lead.resource.plans.edit',
        'uses' => 'PlanController@edit',
    ])->whereUuid('plan')->can('edit', 'plan');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.plans.go',
    //     'uses' => 'PlanController@go',
    // ]);

    Route::get('/{plan}', [
        'as' => 'playground.lead.resource.plans.show',
        'uses' => 'PlanController@show',
    ])->whereUuid('plan')->can('detail', 'plan');

    // API

    Route::put('/lock/{plan}', [
        'as' => 'playground.lead.resource.plans.lock',
        'uses' => 'PlanController@lock',
    ])->whereUuid('plan')->can('lock', 'plan');

    Route::delete('/lock/{plan}', [
        'as' => 'playground.lead.resource.plans.unlock',
        'uses' => 'PlanController@unlock',
    ])->whereUuid('plan')->can('unlock', 'plan');

    Route::delete('/{plan}', [
        'as' => 'playground.lead.resource.plans.destroy',
        'uses' => 'PlanController@destroy',
    ])->whereUuid('plan')->can('delete', 'plan')->withTrashed();

    Route::put('/restore/{plan}', [
        'as' => 'playground.lead.resource.plans.restore',
        'uses' => 'PlanController@restore',
    ])->whereUuid('plan')->can('restore', 'plan')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.plans.post',
        'uses' => 'PlanController@store',
    ])->can('store', Playground\Lead\Models\Plan::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.plans.put',
    //     'uses' => 'PlanController@store',
    // ])->can('store', Playground\Lead\Models\Plan::class);
    //
    // Route::put('/{plan}', [
    //     'as' => 'playground.lead.resource.plans.put.id',
    //     'uses' => 'PlanController@store',
    // ])->whereUuid('plan')->can('update', 'plan');

    Route::patch('/{plan}', [
        'as' => 'playground.lead.resource.plans.patch',
        'uses' => 'PlanController@update',
    ])->whereUuid('plan')->can('update', 'plan');
});
