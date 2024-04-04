<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Feature\Playground\Lead\Resource\Http\Controllers;

/**
 * \Tests\Feature\Playground\Lead\Resource\Http\Controllers\ReportTestCase
 */
class ReportTestCase extends TestCase
{
    public string $fqdn = \Playground\Lead\Models\Report::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'label',
        'model_label' => 'Report',
        'model_label_plural' => 'Reports',
        'model_route' => 'playground.lead.resource.reports',
        'model_slug' => 'report',
        'model_slug_plural' => 'reports',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:report',
        'table' => 'lead_reports',
    ];
}
