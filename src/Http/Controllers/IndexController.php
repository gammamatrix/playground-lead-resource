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
     * @var array<string, string>
     */
    public array $packageInfo = [
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource',
        'view' => 'playground-lead-resource',
    ];

    /**
     * Show the index.
     */
    public function index(): View
    {
        $packageInfo = $this->packageInfo();

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s::index', $packageInfo->view());

        return view($view, [
            'packageInfo' => $packageInfo,
        ]);
    }
}
