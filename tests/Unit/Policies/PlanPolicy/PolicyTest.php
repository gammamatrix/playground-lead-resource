<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Policies\PlanPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Lead\Resource\Policies\PlanPolicy;
use Tests\Unit\Playground\Lead\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Policies\PlanPolicy\PolicyTest
 */
#[CoversClass(PlanPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new PlanPolicy;

        $this->assertInstanceOf(PlanPolicy::class, $instance);
    }
}
