<?php
// database/seeders/ChallengeSeeder.php
namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run()
    {
        $challenges = [
            [
                'title' => '5-Minute Meditation',
                'description' => 'Take a short break to clear your mind',
                'full_description' => 'Meditation has been shown to reduce stress, improve focus, and promote emotional health. This quick 5-minute session is perfect for beginners and busy individuals.',
                'steps' => [
                    'Find a quiet place where you won\'t be disturbed',
                    'Sit comfortably with your back straight',
                    'Close your eyes and focus on your breathing',
                    'When your mind wanders, gently bring your attention back to your breath',
                    'Continue for 5 minutes',
                ],
                'points' => 20,
                'estimated_minutes' => 5,
                'icon' => 'self_improvement',
                'image_url' => 'challenges/meditation.jpg',
                'category' => 'Mindfulness',
            ],
            [
                'title' => 'Brain Teaser Puzzle',
                'description' => 'Solve today\'s logic puzzle',
                'full_description' => 'Exercise your brain with this fun logic puzzle. Brain teasers help improve cognitive function, problem-solving skills, and can even delay the onset of dementia.',
                'steps' => [
                    'Read the puzzle instructions carefully',
                    'Consider all possible solutions',
                    'Use process of elimination',
                    'Submit your answer',
                ],
                'points' => 30,
                'estimated_minutes' => 10,
                'icon' => 'psychology',
                'image_url' => 'challenges/puzzle.jpg',
                'category' => 'Brain Games',
            ],
            [
                'title' => '10 Jumping Jacks',
                'description' => 'Quick exercise to get your blood flowing',
                'full_description' => 'Jumping jacks are a simple cardiovascular exercise that increases your heart rate and helps improve blood circulation. They\'re perfect for a quick energy boost!',
                'steps' => [
                    'Stand upright with your legs together and arms at your sides',
                    'Jump up and spread your legs shoulder-width apart',
                    'Simultaneously raise your arms above your head',
                    'Jump again and return to the starting position',
                    'Repeat 10 times',
                ],
                'points' => 15,
                'estimated_minutes' => 2,
                'icon' => 'fitness_center',
                'image_url' => 'challenges/fitness.jpg',
                'category' => 'Fitness',
            ],
            [
                'title' => 'Daily Trivia Question',
                'description' => 'Test your knowledge with today\'s question',
                'full_description' => 'Expand your knowledge with our daily trivia question. Each day features a different category to keep things interesting and help you learn new facts.',
                'steps' => [
                    'Read today\'s trivia question',
                    'Think about the answer',
                    'Select your response from the options',
                    'Learn the correct answer and an interesting fact',
                ],
                'points' => 25,
                'estimated_minutes' => 3,
                'icon' => 'lightbulb',
                'image_url' => 'challenges/trivia.jpg',
                'category' => 'Knowledge',
            ],
            [
                'title' => 'Gratitude Journal',
                'description' => 'Write down three things you\'re grateful for',
                'full_description' => 'Practicing gratitude has been linked to greater happiness, improved health, better sleep, and stronger relationships. This simple exercise helps you focus on the positive aspects of your life.',
                'steps' => [
                    'Find a quiet moment to reflect',
                    'Think about positive things in your life',
                    'Write down three specific things you\'re grateful for',
                    'Reflect on why these things matter to you',
                ],
                'points' => 20,
                'estimated_minutes' => 5,
                'icon' => 'favorite',
                'image_url' => 'challenges/gratitude.jpg',
                'category' => 'Mindfulness',
            ],
            [
                'title' => 'Memory Challenge',
                'description' => 'Test and improve your short-term memory',
                'full_description' => 'This memory exercise helps strengthen your recall abilities and cognitive function. Regular memory training can improve your overall brain health and daily functioning.',
                'steps' => [
                    'View a sequence of items for 30 seconds',
                    'Try to memorize as many as possible',
                    'After the time is up, list all the items you remember',
                    'Check your score and see how you did',
                ],
                'points' => 35,
                'estimated_minutes' => 7,
                'icon' => 'memory',
                'image_url' => 'challenges/memory.jpg',
                'category' => 'Brain Games',
            ],
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
