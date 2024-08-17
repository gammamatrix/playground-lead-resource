<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Policies\ReportPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Lead\Resource\Policies\ReportPolicy;
use Tests\Unit\Playground\Lead\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Policies\ReportPolicy\PolicyTest
 */
#[CoversClass(ReportPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new ReportPolicy;

        $this->assertInstanceOf(ReportPolicy::class, $instance);
    }
}
