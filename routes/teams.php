<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Team
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/team',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{team:slug}', [
        'as' => 'playground.lead.resource.teams.slug',
        'uses' => 'TeamController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/teams',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.teams',
        'uses' => 'TeamController@index',
    ])->can('index', Playground\Lead\Models\Team::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.teams.index',
        'uses' => 'TeamController@index',
    ])->can('index', Playground\Lead\Models\Team::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.teams.create',
        'uses' => 'TeamController@create',
    ])->can('create', Playground\Lead\Models\Team::class);

    Route::get('/edit/{team}', [
        'as' => 'playground.lead.resource.teams.edit',
        'uses' => 'TeamController@edit',
    ])->whereUuid('team')->can('edit', 'team');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.teams.go',
    //     'uses' => 'TeamController@go',
    // ]);

    Route::get('/{team}', [
        'as' => 'playground.lead.resource.teams.show',
        'uses' => 'TeamController@show',
    ])->whereUuid('team')->can('detail', 'team');

    // API

    Route::put('/lock/{team}', [
        'as' => 'playground.lead.resource.teams.lock',
        'uses' => 'TeamController@lock',
    ])->whereUuid('team')->can('lock', 'team');

    Route::delete('/lock/{team}', [
        'as' => 'playground.lead.resource.teams.unlock',
        'uses' => 'TeamController@unlock',
    ])->whereUuid('team')->can('unlock', 'team');

    Route::delete('/{team}', [
        'as' => 'playground.lead.resource.teams.destroy',
        'uses' => 'TeamController@destroy',
    ])->whereUuid('team')->can('delete', 'team')->withTrashed();

    Route::put('/restore/{team}', [
        'as' => 'playground.lead.resource.teams.restore',
        'uses' => 'TeamController@restore',
    ])->whereUuid('team')->can('restore', 'team')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.teams.post',
        'uses' => 'TeamController@store',
    ])->can('store', Playground\Lead\Models\Team::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.teams.put',
    //     'uses' => 'TeamController@store',
    // ])->can('store', Playground\Lead\Models\Team::class);
    //
    // Route::put('/{team}', [
    //     'as' => 'playground.lead.resource.teams.put.id',
    //     'uses' => 'TeamController@store',
    // ])->whereUuid('team')->can('update', 'team');

    Route::patch('/{team}', [
        'as' => 'playground.lead.resource.teams.patch',
        'uses' => 'TeamController@update',
    ])->whereUuid('team')->can('update', 'team');
});
