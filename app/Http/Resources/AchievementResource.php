<?php
// app/Http/Resources/AchievementResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        $userAchievement = $user ? $user->achievements()->where('achievement_id', $this->id)->first() : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'category' => $this->category,
            'points_reward' => $this->points_reward,
            'is_unlocked' => $userAchievement ? true : false,
            'unlocked_at' => $userAchievement ? $userAchievement->pivot->unlocked_at : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
