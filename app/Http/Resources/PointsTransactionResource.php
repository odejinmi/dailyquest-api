<?php
// app/Http/Resources/PointsTransactionResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointsTransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'points' => $this->points,
            'transaction_type' => $this->transaction_type,
            'description' => $this->description,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
