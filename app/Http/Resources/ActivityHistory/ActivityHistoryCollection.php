<?php

namespace App\Http\Resources\ActivityHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityHistoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'activity_histories' => ActivityHistoryResource::collection($this->collection),
        ];
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'current_page' => $paginated['current_page'],
            'per_page' => $paginated['per_page'],
            'total' => $paginated['total'],
            'total_pages' => $paginated['last_page'],
        ];
    }
}
