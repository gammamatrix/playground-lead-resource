<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Playground\Lead\Models\Lead as LeadModel;
use Playground\Lead\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Lead\Resource\Http\Resources\Lead
 */
class Lead extends JsonResource
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request&FormRequest $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        /**
         * @var ?LeadModel $lead
         */
        $lead = $request->route('lead');

        /**
         * @var ?Authenticatable $user;
         */
        $user = $request->user();

        return [
            'meta' => [
                'id' => $lead?->id,
                'rules' => $request->rules(),
                'session_user_id' => $user?->getAttributeValue('id'),
                'timestamp' => Carbon::now()->toJson(),
                'validated' => $request->validated(),
            ],
        ];
    }
}
