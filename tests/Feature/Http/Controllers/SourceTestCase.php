<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\SourceTestCase
 */
class SourceTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Source::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Source',
        'model_label_plural' => 'Sources',
        'model_route' => 'playground.lead.resource.sources',
        'model_slug' => 'source',
        'model_slug_plural' => 'sources',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:source',
        'table' => 'lead_sources',
    ];
}
