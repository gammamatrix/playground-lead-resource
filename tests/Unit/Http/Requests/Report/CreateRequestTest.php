<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Report;

use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Report\CreateRequestTest
 */
class CreateRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Lead\Resource\Http\Requests\Report\CreateRequest::class;
}
