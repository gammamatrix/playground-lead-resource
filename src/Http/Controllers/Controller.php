<?php

declare(strict_types=1);
/**
 * Playground
 */
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

    protected function getViewPath(
        string $controller = '',
        string $view = ''
    ): string {
        $basePath = config('playground-cms-resource.blade');

        return sprintf(
            '%1$s%2$s%3$s%4$s',
            empty($basePath) || ! is_string($basePath) ? '' : $basePath,
            $controller,
            $view ? '/' : '',
            $view
        );
    }
}
