<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'uuid' => $this->uuid,
        //     'prod_code' => $this->prod_code,
        //     'name' => $this->name,
        //     'description' => $this->description,
        //     'image_url' => $this->image_url,
        //     'unit_price' => $this->unit_price,
        //     'cate_id' => $this->cate_id,
        //     'brands_id' => $this->brands_id,
        //     'created_at' => $this->created_at,

        // ];
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'prod_code' => $this->prod_code,
            'name' => $this->name,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'cate_id' => $this->cate_id,
            'category_name' => $this->category_name,
            'brands_id' => $this->brands_id,
            'brand_name' => $this->brand_name,
            // 'image_url' => [
            //     'name' => $this->image_url['name'],
            //     'url' => $this->image_url['url'],
            //     'size' => $this->image_url['size'],
            //     'created_at' => $this->image_url['created_at'],
            //     'extension' => $this->image_url['extension'],
            // ],
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
        ];
    }
}
