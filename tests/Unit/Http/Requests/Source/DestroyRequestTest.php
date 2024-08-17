<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Source;

use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Source\DestroyRequestTest
 */
class DestroyRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Lead\Resource\Http\Requests\Source\DestroyRequest::class;
}
