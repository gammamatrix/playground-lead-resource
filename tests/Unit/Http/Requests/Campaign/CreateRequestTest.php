<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign;

use Playground\Lead\Resource\Http\Requests\Campaign\CreateRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign\CreateRequestTest
 */
class CreateRequestTest extends RequestTestCase
{
    protected string $requestClass = CreateRequest::class;
}
