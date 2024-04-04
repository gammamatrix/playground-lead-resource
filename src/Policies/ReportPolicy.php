<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Policies;

use Playground\Auth\Policies\ModelPolicy;

/**
 * \Playground\Lead\Resource\Policies\ReportPolicy
 */
class ReportPolicy extends ModelPolicy
{
    protected string $package = 'playground-lead-resource';

    /**
     * @var array<int, string> The roles allowed to view the MVC.
     */
    protected $rolesToView = [
        'user',
        'staff',
        'sales',
        'manager',
        'admin',
        'root',
    ];

    /**
     * @var array<int, string> The roles allowed for actions in the MVC.
     */
    protected $rolesForAction = [
        'sales',
        'manager',
        'admin',
        'root',
    ];
}
