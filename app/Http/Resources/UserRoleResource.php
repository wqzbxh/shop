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
        switch ($request->get('type')) {
            case 'select':
                return $this->selectFormat();
                break;
            default:
                return [
                    'id' => $this->id,
                    'role_name' => $this->role_name,
                    'desc' => $this->desc,
                    'menu_id' => explode(',', $this->menu_id)
                ];
                break;
        }
    }

    /**
     * 给下拉框的选择返回格式
     * @return array
     */
    public function selectFormat()
    {
        return [
            'value' => (string)$this->id,
            'label' => $this->role_name,
        ];
    }

    /**
     * 默认返回格式
     * @return void
     */
    public function defaultFormat()
    {

    }
}
