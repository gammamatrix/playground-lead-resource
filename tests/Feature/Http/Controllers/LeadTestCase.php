<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\LeadTestCase
 */
class LeadTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Lead::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Lead',
        'model_label_plural' => 'Leads',
        'model_route' => 'playground.lead.resource.leads',
        'model_slug' => 'lead',
        'model_slug_plural' => 'leads',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:lead',
        'table' => 'lead_leads',
    ];
}
