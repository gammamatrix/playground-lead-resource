<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Lead\Resource\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\FormRequest
 */
class FormRequest extends BaseFormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if (empty($user)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return static::RULES;
    }

    public function userHasAdminPrivileges(?Authenticatable $user = null): bool
    {
        $admin = false;
        if (! empty($user)) {
            if (method_exists($user, 'isAdmin')) {
                $admin = ! empty($user->isAdmin());
            } else {
                // standard user, no roles or privileges
                $admin = true;
            }
        }

        return $admin;
    }
}
