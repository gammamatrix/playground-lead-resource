<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        // return true;
        $user = $this->user();

        if (empty($user)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = is_array(static::RULES) ? static::RULES : [];

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
        // $exception = $validator->getException();

        // throw (new $exception($validator))
        //             ->errorBag($this->errorBag)
        //             ->redirectTo($this->getRedirectUrl());
    }

    public function userHasAdminPrivileges(Authenticatable $user = null): bool
    {
        $admin = false;
        if (! empty($user)) {
            if (method_exists($user, 'isAdmin')) {
                $admin = $user->isAdmin();
            } else {
                // standard user, no roles or privileges
                $admin = true;
            }
        }

        return $admin;
    }
}
