<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign;

use Playground\Lead\Resource\Http\Requests\Campaign\LockRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign\LockRequestTest
 */
class LockRequestTest extends RequestTestCase
{
    protected string $requestClass = LockRequest::class;
}
