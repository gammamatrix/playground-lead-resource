<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Policies\SourcePolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Lead\Resource\Policies\SourcePolicy;
use Tests\Unit\Playground\Lead\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Policies\SourcePolicy\PolicyTest
 */
#[CoversClass(SourcePolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new SourcePolicy;

        $this->assertInstanceOf(SourcePolicy::class, $instance);
    }
}
