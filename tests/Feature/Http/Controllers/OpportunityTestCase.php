<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\OpportunityTestCase
 */
class OpportunityTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Opportunity::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Opportunity',
        'model_label_plural' => 'Opportunities',
        'model_route' => 'playground.lead.resource.opportunities',
        'model_slug' => 'opportunity',
        'model_slug_plural' => 'opportunities',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:opportunity',
        'table' => 'lead_opportunities',
    ];
}
