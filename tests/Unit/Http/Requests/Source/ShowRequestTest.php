<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Source;

use Playground\Lead\Resource\Http\Requests\Source\ShowRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Source\ShowRequestTest
 */
class ShowRequestTest extends RequestTestCase
{
    protected string $requestClass = ShowRequest::class;
}
