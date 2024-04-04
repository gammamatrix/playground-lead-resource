<?php

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Playground\Lead\Models\Opportunity as OpportunityModel;
use Playground\Lead\Resource\Http\Requests\FormRequest;

class Opportunity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request&FormRequest $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        /**
         * @var ?OpportunityModel $opportunity
         */
        $opportunity = $request->route('opportunity');

        return [
            'meta' => [
                'id' => $opportunity?->id,
                'rules' => $request->rules(),
                'session_user_id' => $request->user()?->id,
                'timestamp' => Carbon::now()->toJson(),
                'validated' => $request->validated(),
            ],
        ];
    }
}
