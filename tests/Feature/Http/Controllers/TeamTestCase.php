<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\TeamTestCase
 */
class TeamTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Team::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Team',
        'model_label_plural' => 'Teams',
        'model_route' => 'playground.lead.resource.teams',
        'model_slug' => 'team',
        'model_slug_plural' => 'teams',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:team',
        'table' => 'lead_teams',
    ];
}
