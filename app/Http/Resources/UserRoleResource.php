<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
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
            'role_name' => $this->role_name,
            'desc' => $this->desc,
            'menu_id' => explode(',',$this->menu_id) ,
            'idcard' => $this->idcard,
            'phone' => $this->phone,
        ];
    }
}
