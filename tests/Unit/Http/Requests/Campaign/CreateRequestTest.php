<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign;

use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign\CreateRequestTest
 */
class CreateRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Lead\Resource\Http\Requests\Campaign\CreateRequest::class;
}
