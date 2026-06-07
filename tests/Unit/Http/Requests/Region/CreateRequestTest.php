<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Region;

use Playground\Lead\Resource\Http\Requests\Region\CreateRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Region\CreateRequestTest
 */
class CreateRequestTest extends RequestTestCase
{
    protected string $requestClass = CreateRequest::class;
}
