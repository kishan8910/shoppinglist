<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * return item object as array
         */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'sort' => $this->sort,
            'bought' => $this->bought,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
