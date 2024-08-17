<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Policies;

use Playground\Auth\Policies\ModelPolicy;

/**
 * \Playground\Lead\Resource\Policies\PlanPolicy
 */
class PlanPolicy extends ModelPolicy
{
    protected string $package = 'playground-lead-resource';

    /**
     * @var array<int, string> The roles allowed to view the MVC.
     */
    protected $rolesToView = [
        'user',
        'staff',
        'publisher',
        'manager',
        'admin',
        'root',
    ];

    /**
     * @var array<int, string> The roles allowed for actions in the MVC.
     */
    protected $rolesForAction = [
        'publisher',
        'manager',
        'admin',
        'root',
    ];
}
