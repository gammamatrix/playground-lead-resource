<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Lead\Resource\Http\Requests\Goal;

use Tests\Unit\Playground\Lead\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Lead\Resource\Http\Requests\Goal\DestroyRequestTest
 */
class DestroyRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Lead\Resource\Http\Requests\Goal\DestroyRequest::class;
}
