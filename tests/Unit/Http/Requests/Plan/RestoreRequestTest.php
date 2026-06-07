<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Plan;

use Playground\Lead\Resource\Http\Requests\Plan\RestoreRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Plan\RestoreRequestTest
 */
class RestoreRequestTest extends RequestTestCase
{
    protected string $requestClass = RestoreRequest::class;
}
