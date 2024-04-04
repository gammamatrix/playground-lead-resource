<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Lead\Resource\Policies\CampaignPolicy;

// use Illuminate\Support\Facades\Artisan;
use Playground\Lead\Resource\Policies\CampaignPolicy;
use Tests\Unit\Playground\Lead\Resource\TestCase;

/**
 * \ests\Unit\Playground\Lead\Resource\Policies\CampaignPolicy\PolicyTest
 */
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new CampaignPolicy;

        $this->assertInstanceOf(CampaignPolicy::class, $instance);
    }
}
