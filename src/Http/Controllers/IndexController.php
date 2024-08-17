<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Controllers;

use Illuminate\View\View;

/**
 * \Playground\Lead\Resource\Http\Controllers\IndexController
 */
class IndexController extends Controller
{
    /**
     * Show the index.
     */
    public function index(): View
    {
        return view('playground-lead-resource::index');
    }
}
