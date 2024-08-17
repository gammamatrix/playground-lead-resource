<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Requests\Task;

use Playground\Lead\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\Task\RestoreRequest
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
