<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Requests;

/**
 * \Playground\Lead\Resource\Http\Requests\RestoreRequest
 */
class RestoreRequest extends FormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        '_return_url' => ['nullable', 'url'],
    ];
}
