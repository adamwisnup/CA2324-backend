<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'document' => $this->document,
            'gender' => $this->gender,
            'year_of_enrollment' => $this->year_of_enrollment,
            'faculty' => $this->faculty,
            'major' => $this->major,
            'class' => $this->class,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
