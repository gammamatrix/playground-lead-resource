<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Teammate;

use Playground\Lead\Resource\Http\Requests\Teammate\LockRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Teammate\LockRequestTest
 */
class LockRequestTest extends RequestTestCase
{
    protected string $requestClass = LockRequest::class;
}
