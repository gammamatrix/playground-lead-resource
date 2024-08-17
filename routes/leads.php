<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Lead
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/lead',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{lead:slug}', [
        'as' => 'playground.lead.resource.leads.slug',
        'uses' => 'LeadController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/leads',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.leads',
        'uses' => 'LeadController@index',
    ])->can('index', Playground\Lead\Models\Lead::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.leads.index',
        'uses' => 'LeadController@index',
    ])->can('index', Playground\Lead\Models\Lead::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.leads.create',
        'uses' => 'LeadController@create',
    ])->can('create', Playground\Lead\Models\Lead::class);

    Route::get('/edit/{lead}', [
        'as' => 'playground.lead.resource.leads.edit',
        'uses' => 'LeadController@edit',
    ])->whereUuid('lead')->can('edit', 'lead');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.leads.go',
    //     'uses' => 'LeadController@go',
    // ]);

    Route::get('/{lead}', [
        'as' => 'playground.lead.resource.leads.show',
        'uses' => 'LeadController@show',
    ])->whereUuid('lead')->can('detail', 'lead');

    // API

    Route::put('/lock/{lead}', [
        'as' => 'playground.lead.resource.leads.lock',
        'uses' => 'LeadController@lock',
    ])->whereUuid('lead')->can('lock', 'lead');

    Route::delete('/lock/{lead}', [
        'as' => 'playground.lead.resource.leads.unlock',
        'uses' => 'LeadController@unlock',
    ])->whereUuid('lead')->can('unlock', 'lead');

    Route::delete('/{lead}', [
        'as' => 'playground.lead.resource.leads.destroy',
        'uses' => 'LeadController@destroy',
    ])->whereUuid('lead')->can('delete', 'lead')->withTrashed();

    Route::put('/restore/{lead}', [
        'as' => 'playground.lead.resource.leads.restore',
        'uses' => 'LeadController@restore',
    ])->whereUuid('lead')->can('restore', 'lead')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.leads.post',
        'uses' => 'LeadController@store',
    ])->can('store', Playground\Lead\Models\Lead::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.leads.put',
    //     'uses' => 'LeadController@store',
    // ])->can('store', Playground\Lead\Models\Lead::class);
    //
    // Route::put('/{lead}', [
    //     'as' => 'playground.lead.resource.leads.put.id',
    //     'uses' => 'LeadController@store',
    // ])->whereUuid('lead')->can('update', 'lead');

    Route::patch('/{lead}', [
        'as' => 'playground.lead.resource.leads.patch',
        'uses' => 'LeadController@update',
    ])->whereUuid('lead')->can('update', 'lead');
});
