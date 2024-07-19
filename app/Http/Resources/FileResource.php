<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\File */
class FileResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'size'        => $this->size,
            'uploaded_at' => $this->uploaded_at,
            'is_public'   => $this->is_public,
            'unique_link' => $this->unique_link,
            'path'        => $this->path,
            'created_at'  => $this->created_at,

            'directory' => new DirectoryResource($this->whenLoaded('directory')),
        ];
    }
}
