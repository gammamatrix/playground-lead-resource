<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\GoalTestCase
 */
class GoalTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Goal::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Goal',
        'model_label_plural' => 'Goals',
        'model_route' => 'playground.lead.resource.goals',
        'model_slug' => 'goal',
        'model_slug_plural' => 'goals',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:goal',
        'table' => 'lead_goals',
    ];
}
