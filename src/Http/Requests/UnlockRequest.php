<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Requests;

/**
 * \Playground\Lead\Resource\Http\Requests\UnlockRequest
 */
class UnlockRequest extends FormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        '_return_url' => ['nullable', 'url'],
    ];
}
