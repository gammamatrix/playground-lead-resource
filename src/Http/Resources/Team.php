<?php

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Playground\Lead\Models\Team as TeamModel;
use Playground\Lead\Resource\Http\Requests\FormRequest;

class Team extends JsonResource
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
         * @var ?TeamModel $team
         */
        $team = $request->route('team');

        return [
            'meta' => [
                'id' => $team?->id,
                'rules' => $request->rules(),
                'session_user_id' => $request->user()?->id,
                'timestamp' => Carbon::now()->toJson(),
                'validated' => $request->validated(),
            ],
        ];
    }
}
