<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\CampaignTestCase
 */
class CampaignTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Campaign::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Campaign',
        'model_label_plural' => 'Campaigns',
        'model_route' => 'playground.lead.resource.campaigns',
        'model_slug' => 'campaign',
        'model_slug_plural' => 'campaigns',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:campaign',
        'table' => 'lead_campaigns',
    ];
}
