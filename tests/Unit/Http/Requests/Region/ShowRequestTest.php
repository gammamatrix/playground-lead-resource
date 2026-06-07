<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Region;

use Playground\Lead\Resource\Http\Requests\Region\ShowRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Region\ShowRequestTest
 */
class ShowRequestTest extends RequestTestCase
{
    protected string $requestClass = ShowRequest::class;
}
