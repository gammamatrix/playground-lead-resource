<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Opportunity;

use Playground\Lead\Resource\Http\Requests\Opportunity\LockRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Opportunity\LockRequestTest
 */
class LockRequestTest extends RequestTestCase
{
    protected string $requestClass = LockRequest::class;
}
