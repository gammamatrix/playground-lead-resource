<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Task;

use Playground\Lead\Resource\Http\Requests\Task\EditRequest;
use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Task\EditRequestTest
 */
class EditRequestTest extends RequestTestCase
{
    protected string $requestClass = EditRequest::class;
}
