<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Source;

use Playground\Lead\Resource\Http\Requests\Source\IndexRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Source\IndexRequestTest
 */
class IndexRequestTest extends RequestTestCase
{
    protected string $requestClass = IndexRequest::class;
}
