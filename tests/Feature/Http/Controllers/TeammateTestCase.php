<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\TeammateTestCase
 */
class TeammateTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Teammate::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Teammate',
        'model_label_plural' => 'Teammates',
        'model_route' => 'playground.lead.resource.teammates',
        'model_slug' => 'teammate',
        'model_slug_plural' => 'teammates',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:teammate',
        'table' => 'lead_teammates',
    ];
}
