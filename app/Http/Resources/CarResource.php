<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name, 
            'num'=> $this->num, 
            'color'=> $this->color, 
            'VIN'=> $this->VIN, 
            'make'=> $this->make, 
            'model'=> $this->model, 
            'year'=> $this->year,
        ];
    }
}
