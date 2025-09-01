<?php
// app/Http/Resources/ChallengeResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        $userChallenge = $user ? $user->challenges()->where('challenge_id', $this->id)->first() : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'full_description' => $this->full_description,
            'steps' => $this->steps,
            'points' => $this->points,
            'estimated_minutes' => $this->estimated_minutes,
            'icon' => $this->icon,
            'image_url' => $this->image_url ? url('storage/' . $this->image_url) : null,
            'category' => $this->category,
            'is_completed' => $userChallenge ? (bool)$userChallenge->pivot->is_completed : false,
            'completed_at' => $userChallenge ? $userChallenge->pivot->completed_at : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
