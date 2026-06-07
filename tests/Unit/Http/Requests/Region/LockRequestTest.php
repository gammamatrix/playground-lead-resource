<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Region;

use Playground\Lead\Resource\Http\Requests\Region\LockRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Region\LockRequestTest
 */
class LockRequestTest extends RequestTestCase
{
    protected string $requestClass = LockRequest::class;
}
