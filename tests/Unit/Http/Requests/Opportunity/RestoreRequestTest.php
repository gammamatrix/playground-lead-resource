<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Opportunity;

use Playground\Lead\Resource\Http\Requests\Opportunity\RestoreRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Opportunity\RestoreRequestTest
 */
class RestoreRequestTest extends RequestTestCase
{
    protected string $requestClass = RestoreRequest::class;
}
