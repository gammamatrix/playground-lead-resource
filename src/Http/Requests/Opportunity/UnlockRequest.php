<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Requests\Opportunity;

use Playground\Lead\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\Opportunity\UnlockRequest
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
