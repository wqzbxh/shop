<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeProjectResource extends JsonResource
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
            'id' => (string)$this->id,
            'name' => $this->name,
            'project_no' => $this->project_no,
            'start_date' => $this->start_date,
            'time_estimate' => $this->time_estimate,
            'info' => $this->info,
            'customer_name' => $this->customer_name,
        ];
    }
}
