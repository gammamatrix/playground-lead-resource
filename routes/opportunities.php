<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Opportunity
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/opportunity',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{opportunity:slug}', [
        'as' => 'playground.lead.resource.opportunities.slug',
        'uses' => 'OpportunityController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/opportunities',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.opportunities',
        'uses' => 'OpportunityController@index',
    ])->can('index', Playground\Lead\Models\Opportunity::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.opportunities.index',
        'uses' => 'OpportunityController@index',
    ])->can('index', Playground\Lead\Models\Opportunity::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.opportunities.create',
        'uses' => 'OpportunityController@create',
    ])->can('create', Playground\Lead\Models\Opportunity::class);

    Route::get('/edit/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.edit',
        'uses' => 'OpportunityController@edit',
    ])->whereUuid('opportunity')->can('edit', 'opportunity');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.opportunities.go',
    //     'uses' => 'OpportunityController@go',
    // ]);

    Route::get('/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.show',
        'uses' => 'OpportunityController@show',
    ])->whereUuid('opportunity')->can('detail', 'opportunity');

    // API

    Route::put('/lock/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.lock',
        'uses' => 'OpportunityController@lock',
    ])->whereUuid('opportunity')->can('lock', 'opportunity');

    Route::delete('/lock/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.unlock',
        'uses' => 'OpportunityController@unlock',
    ])->whereUuid('opportunity')->can('unlock', 'opportunity');

    Route::delete('/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.destroy',
        'uses' => 'OpportunityController@destroy',
    ])->whereUuid('opportunity')->can('delete', 'opportunity')->withTrashed();

    Route::put('/restore/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.restore',
        'uses' => 'OpportunityController@restore',
    ])->whereUuid('opportunity')->can('restore', 'opportunity')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.opportunities.post',
        'uses' => 'OpportunityController@store',
    ])->can('store', Playground\Lead\Models\Opportunity::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.opportunities.put',
    //     'uses' => 'OpportunityController@store',
    // ])->can('store', Playground\Lead\Models\Opportunity::class);
    //
    // Route::put('/{opportunity}', [
    //     'as' => 'playground.lead.resource.opportunities.put.id',
    //     'uses' => 'OpportunityController@store',
    // ])->whereUuid('opportunity')->can('update', 'opportunity');

    Route::patch('/{opportunity}', [
        'as' => 'playground.lead.resource.opportunities.patch',
        'uses' => 'OpportunityController@update',
    ])->whereUuid('opportunity')->can('update', 'opportunity');
});
