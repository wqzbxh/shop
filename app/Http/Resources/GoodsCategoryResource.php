<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'category_name' => $this->category_name,
            'category_desc' => $this->category_desc,
            'order' => $this->order,
            'id_del' => $this->id_del,
        ];
    }

    /**
     * Get the array representation of the resource without the ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArrayWithoutId($request)
    {
        return [
            'type' => $this->type,
            'category_name' => $this->category_name,
            'category_desc' => $this->category_desc,
        ];
    }
}
