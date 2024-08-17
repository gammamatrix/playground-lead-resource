<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lead Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/lead',
    'middleware' => config('playground-lead-resource.middleware.default'),
    'namespace' => '\Playground\Lead\Resource\Http\Controllers',
], function () {

    Route::get('/', [
        'as' => 'playground.lead.resource',
        'uses' => 'IndexController@index',
    ])->can('index', Playground\Lead\Models\Campaign::class);

});
