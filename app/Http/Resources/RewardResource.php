<?php
// app/Http/Resources/RewardResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'points_cost' => $this->points_cost,
            'image_url' => $this->image_url ? url('storage/' . $this->image_url) : null,
            'category' => $this->category,
            'reward_type' => $this->reward_type,
            'is_active' => $this->is_active,
            'stock' => $this->stock,
            'can_afford' => $user ? $user->points >= $this->points_cost : false,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
