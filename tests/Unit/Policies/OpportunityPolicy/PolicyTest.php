<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Policies\OpportunityPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Lead\Resource\Policies\OpportunityPolicy;
use Tests\Unit\Playground\Lead\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Policies\OpportunityPolicy\PolicyTest
 */
#[CoversClass(OpportunityPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new OpportunityPolicy;

        $this->assertInstanceOf(OpportunityPolicy::class, $instance);
    }
}
