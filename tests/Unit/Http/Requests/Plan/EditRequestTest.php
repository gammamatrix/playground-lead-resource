<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Plan;

use Playground\Lead\Resource\Http\Requests\Plan\EditRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Plan\EditRequestTest
 */
class EditRequestTest extends RequestTestCase
{
    protected string $requestClass = EditRequest::class;
}
