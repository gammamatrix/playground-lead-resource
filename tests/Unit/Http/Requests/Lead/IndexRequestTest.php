<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Lead;

use Playground\Lead\Resource\Http\Requests\Lead\IndexRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Lead\IndexRequestTest
 */
class IndexRequestTest extends RequestTestCase
{
    protected string $requestClass = IndexRequest::class;
}
