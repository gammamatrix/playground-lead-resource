<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\PlanTestCase
 */
class PlanTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Plan::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Plan',
        'model_label_plural' => 'Plans',
        'model_route' => 'playground.lead.resource.plans',
        'model_slug' => 'plan',
        'model_slug_plural' => 'plans',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:plan',
        'table' => 'lead_plans',
    ];
}
