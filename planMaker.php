<?php

class ClimbingTrainingPlan
{
    private $exercises;
    private $trainingDays;
    private $climbingGrade;
    private $focus;
    private $maxTrainingDays = 5;
    private $maxStrengthTrainingDaysPerWeek = 3;
    private $intensityIncrement = 0.5; // Intensity increment based on climbing grade
    private $maxExercisesPerDay = 3;
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

        // Calculate exercises per day
        $exercisesPerDay = ceil(count($adjustedExercises) / $this->trainingDays);
        if ($exercisesPerDay > $this->maxExercisesPerDay) {
            $exercisesPerDay = $this->maxExercisesPerDay;
        }

        // Generate training plan
        $trainingPlan = [];
        $strengthTrainingDays = 0;
        $usedExercises = []; // To track used exercises for each day

        for ($day = 1; $day <= $this->trainingDays; $day++) {
            $trainingPlan["Day $day"] = [];

            foreach ($adjustedExercises as $exercise) {

                // Shuffle exercises
                shuffle($adjustedExercises);

                // Check if the exercise has already been used on this day
                if (in_array($exercise['name'], $usedExercises)) {
                    continue;
                }

                // Check if the maximum number of strength training days per week is reached
                if ($exercise['type'] == 'strength') {
                    if ($strengthTrainingDays >= $this->maxStrengthTrainingDaysPerWeek) {
                        continue;
                    }
                    $strengthTrainingDays++;
                }

                $trainingPlan["Day $day"][] = $exercise;
                $usedExercises[] = $exercise['name'];

            }

            $usedExercises = []; // Reset used exercises for the next day
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
            // Check if the climber's grade allows  exercises
            if ($exercise['difficulty'] > $this->climbingGrade) {
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

function newPlan($un) {

    $exercisesJson = file_get_contents('exercise_directory.json');
    $exercises = json_decode($exercisesJson, true);

    // Connect to the database
    $conn = require __DIR__ . "/database.php";

    // SQL query to retrieve user information based on username
    $check = "SELECT *
    FROM basic_info
    WHERE username = '$un'";

    // Execute the SQL query
    $rs = mysqli_query($conn, $check);

    // Fetch the row of data as an associative array
    $row = mysqli_fetch_assoc($rs);

    // Extract relevant information from the database row
    $focus = $row['focus'];
    $climbingGrade = $row['grade']; 
    $trainingDays = $row['vacancy'];
    print_r($focus);
    print_r($climbingGrade);
    print_r($trainingDays);

    $trainingPlanGenerator = new ClimbingTrainingPlan($exercises, $trainingDays, $climbingGrade, $focus);
    $trainingPlan = $trainingPlanGenerator->generateTrainingPlan();
    return json_encode($trainingPlan, JSON_PRETTY_PRINT);
}



?>
