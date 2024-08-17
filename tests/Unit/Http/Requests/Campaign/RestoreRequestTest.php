<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign;

use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Campaign\RestoreRequestTest
 */
class RestoreRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Lead\Resource\Http\Requests\Campaign\RestoreRequest::class;
}
