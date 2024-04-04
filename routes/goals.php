<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Routes: Goal
|--------------------------------------------------------------------------
|
|
*/
Route::group([
    'prefix' => 'resource/lead/goal',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{goal:slug}', [
        'as' => 'playground.lead.resource.goals.slug',
        'uses' => 'GoalController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/goals',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.goals',
        'uses' => 'GoalController@index',
    ])->can('index', Playground\Lead\Models\Goal::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.goals.create',
        'uses' => 'GoalController@create',
    ])->can('create', Playground\Lead\Models\Goal::class);

    Route::get('/edit/{goal}', [
        'as' => 'playground.lead.resource.goals.edit',
        'uses' => 'GoalController@edit',
    ])->whereUuid('goal')
        ->can('edit', 'goal');

    // Route::get('/go/{id}', [
    //     'as'   => 'playground.lead.resource.goals.go',
    //     'uses' => 'GoalController@go',
    // ]);

    Route::get('/{goal}', [
        'as' => 'playground.lead.resource.goals.show',
        'uses' => 'GoalController@show',
    ])->whereUuid('goal')
        ->can('detail', 'goal');

    // Resource

    Route::put('/lock/{goal}', [
        'as' => 'playground.lead.resource.goals.lock',
        'uses' => 'GoalController@lock',
    ])->whereUuid('goal')
        ->can('lock', 'goal');

    Route::delete('/lock/{goal}', [
        'as' => 'playground.lead.resource.goals.unlock',
        'uses' => 'GoalController@unlock',
    ])->whereUuid('goal')
        ->can('unlock', 'goal');

    Route::delete('/{goal}', [
        'as' => 'playground.lead.resource.goals.destroy',
        'uses' => 'GoalController@destroy',
    ])->whereUuid('goal')
        ->can('delete', 'goal')
        ->withTrashed();

    Route::put('/restore/{goal}', [
        'as' => 'playground.lead.resource.goals.restore',
        'uses' => 'GoalController@restore',
    ])->whereUuid('goal')
        ->can('restore', 'goal')
        ->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.goals.post',
        'uses' => 'GoalController@store',
    ])->can('store', Playground\Lead\Models\Goal::class);

    // Route::put('/', [
    //     'as'   => 'playground.lead.resource.goals.put',
    //     'uses' => 'GoalController@store',
    // ])->can('store', \Playground\Lead\Models\Goal::class);
    //
    // Route::put('/{goal}', [
    //     'as'   => 'playground.lead.resource.goals.put.id',
    //     'uses' => 'GoalController@store',
    // ])->whereUuid('goal')->can('update', 'goal');

    Route::patch('/{goal}', [
        'as' => 'playground.lead.resource.goals.patch',
        'uses' => 'GoalController@update',
    ])->whereUuid('goal')->can('update', 'goal');
});
