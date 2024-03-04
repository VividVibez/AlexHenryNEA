<?php

class ClimbingTrainingPlan
{
    private $exercises;
    private $trainingDays;
    private $climbingGrade;
    private $focus;
    private $maxTrainingDays = 5;
    private $maxStrengthTrainingDaysPerWeek = 3;
    private $minGradeForHangboard = 3;
    private $restDaysFrequency = 2; // Rest every 2 days
    private $intensityIncrement = 0.5; // Intensity increment based on climbing grade
    private $progressFilePath = 'progress.json';

    public function __construct($exercises, $trainingDays, $climbingGrade, $focus)
    {
        $this->exercises = $exercises;
        $this->trainingDays = min($trainingDays, $this->maxTrainingDays);
        $this->climbingGrade = $climbingGrade;
        $this->focus = $focus;
    }

    public function generateTrainingPlan()
    {
        // Load progress data
        $progressData = $this->loadProgressData();

        // Adjust intensity based on climbing grade
        $adjustedIntensity = $this->adjustIntensity($this->climbingGrade);

        // Filter and adjust exercises based on focus, grade, and previous progress
        $filteredExercises = $this->filterExercises();
        $adjustedExercises = $this->adjustExercises($filteredExercises, $adjustedIntensity, $progressData);

        // Shuffle exercises
        shuffle($adjustedExercises);

        // Calculate exercises per day
        $exercisesPerDay = ceil(count($adjustedExercises) / $this->trainingDays);

        // Generate training plan
        $trainingPlan = [];
        $day = 1;
        $strengthTrainingDays = 0;

        foreach ($adjustedExercises as $exercise) {
            if (!isset($trainingPlan["Day $day"])) {
                $trainingPlan["Day $day"] = [];
            }

            // Check if the maximum number of strength training days per week is reached
            if ($exercise['type'] == 'strength') {
                if ($strengthTrainingDays >= $this->maxStrengthTrainingDaysPerWeek) {
                    continue;
                }
                $strengthTrainingDays++;
            }

            $trainingPlan["Day $day"][] = $exercise;

            // Move to the next day if the exercises per day limit is reached
            if (count($trainingPlan["Day $day"]) >= $exercisesPerDay) {
                $day++;
                $strengthTrainingDays = 0;
            }

            // Break the loop if the maximum number of training days is reached
            if ($day > $this->trainingDays) {
                break;
            }
        }

        return $trainingPlan;
    }

    private function filterExercises()
    {
        $filteredExercises = [];
        foreach ($this->exercises as $exercise) {
            // Check if the exercise type matches the focus
            if (($this->focus == 1 && $exercise['type'] == 'strength')
                || ($this->focus == 5 && $exercise['type'] == 'technique')
                || ($this->focus >= 2 && $this->focus <= 4)) {
                $filteredExercises[] = $exercise;
            }
        }

        // Filter exercises based on climber's grade and constraints
        $filteredExercises = array_filter($filteredExercises, function ($exercise) {
            // Check if the climber's grade allows hangboard exercises
            if ($exercise['equipment'] == 'Hangboard' && $this->climbingGrade < $this->minGradeForHangboard) {
                return false;
            }
            // Add more grade-based filters if needed
            return true;
        });

        return $filteredExercises;
    }

    private function adjustIntensity($grade)
    {
        // Adjust intensity based on climbing grade
        return max(1, $grade * $this->intensityIncrement);
    }

    private function adjustExercises($exercises, $intensity, $progressData)
    {
        // Adjust exercises based on intensity and previous progress
        // For simplicity, this function can be further expanded
        // based on specific criteria and algorithms for adjusting exercises

        // For now, just return the filtered exercises
        return $exercises;
    }

    private function loadProgressData()
    {
        // Load progress data from file
        if (file_exists($this->progressFilePath)) {
            $progressData = file_get_contents($this->progressFilePath);
            return json_decode($progressData, true);
        }
        return [];
    }
}

// Assuming the inputs are already collected and stored in variables
$exercisesJson = file_get_contents('exercise_directory.json');
$exercises = json_decode($exercisesJson, true);
$trainingDays = 5; // Example value, replace with actual value
$climbingGrade = 4; // Example value, replace with actual value
$focus = 3; // Example value, replace with actual value

$trainingPlanGenerator = new ClimbingTrainingPlan($exercises, $trainingDays, $climbingGrade, $focus);
$trainingPlan = $trainingPlanGenerator->generateTrainingPlan();

echo json_encode($trainingPlan, JSON_PRETTY_PRINT);
?>
