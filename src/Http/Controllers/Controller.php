<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * \Playground\Lead\Resource\Http\Controllers\Controller
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    // use DispatchesJobs;
}
