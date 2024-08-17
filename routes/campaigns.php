<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Resource Routes: Campaign
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'api/lead/campaign',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/{campaign:slug}', [
        'as' => 'playground.lead.resource.campaigns.slug',
        'uses' => 'CampaignController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/lead/campaigns',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.lead.resource.campaigns',
        'uses' => 'CampaignController@index',
    ])->can('index', Playground\Lead\Models\Campaign::class);

    Route::post('/index', [
        'as' => 'playground.lead.resource.campaigns.index',
        'uses' => 'CampaignController@index',
    ])->can('index', Playground\Lead\Models\Campaign::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.lead.resource.campaigns.create',
        'uses' => 'CampaignController@create',
    ])->can('create', Playground\Lead\Models\Campaign::class);

    Route::get('/edit/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.edit',
        'uses' => 'CampaignController@edit',
    ])->whereUuid('campaign')->can('edit', 'campaign');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.lead.resource.campaigns.go',
    //     'uses' => 'CampaignController@go',
    // ]);

    Route::get('/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.show',
        'uses' => 'CampaignController@show',
    ])->whereUuid('campaign')->can('detail', 'campaign');

    // API

    Route::put('/lock/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.lock',
        'uses' => 'CampaignController@lock',
    ])->whereUuid('campaign')->can('lock', 'campaign');

    Route::delete('/lock/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.unlock',
        'uses' => 'CampaignController@unlock',
    ])->whereUuid('campaign')->can('unlock', 'campaign');

    Route::delete('/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.destroy',
        'uses' => 'CampaignController@destroy',
    ])->whereUuid('campaign')->can('delete', 'campaign')->withTrashed();

    Route::put('/restore/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.restore',
        'uses' => 'CampaignController@restore',
    ])->whereUuid('campaign')->can('restore', 'campaign')->withTrashed();

    Route::post('/', [
        'as' => 'playground.lead.resource.campaigns.post',
        'uses' => 'CampaignController@store',
    ])->can('store', Playground\Lead\Models\Campaign::class);

    // Route::put('/', [
    //     'as' => 'playground.lead.resource.campaigns.put',
    //     'uses' => 'CampaignController@store',
    // ])->can('store', Playground\Lead\Models\Campaign::class);
    //
    // Route::put('/{campaign}', [
    //     'as' => 'playground.lead.resource.campaigns.put.id',
    //     'uses' => 'CampaignController@store',
    // ])->whereUuid('campaign')->can('update', 'campaign');

    Route::patch('/{campaign}', [
        'as' => 'playground.lead.resource.campaigns.patch',
        'uses' => 'CampaignController@update',
    ])->whereUuid('campaign')->can('update', 'campaign');
});
