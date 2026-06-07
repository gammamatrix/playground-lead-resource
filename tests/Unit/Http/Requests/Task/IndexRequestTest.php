<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Task;

use Playground\Lead\Resource\Http\Requests\Task\IndexRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Task\IndexRequestTest
 */
class IndexRequestTest extends RequestTestCase
{
    protected string $requestClass = IndexRequest::class;
}
