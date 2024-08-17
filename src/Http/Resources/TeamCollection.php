<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use Playground\Http\Requests\IndexRequest;

/**
 * \Playground\Lead\Resource\Http\Resources\TeamCollection
 */
class TeamCollection extends ResourceCollection
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request&IndexRequest $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'columns' => $request->getPaginationColumns(),
                'dates' => $request->getPaginationDates(),
                'flags' => $request->getPaginationFlags(),
                'ids' => $request->getPaginationIds(),
                'rules' => $request->rules(),
                'session_user_id' => $request->user()?->id,
                'sortable' => $request->getSortable(),
                'timestamp' => Carbon::now()->toJson(),
                'validated' => $request->validated(),
            ],
        ];
    }
}
