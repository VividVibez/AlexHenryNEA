<?php

class ClimbingTrainingPlan
{
    private $exercises;
    private $availability;
    private $climbingGrade;
    private $focus;
    private $maxTrainingDays = 5;
    private $maxStrengthTrainingDays = 3;
    private $maxExercisesPerDay = 5;
    
    public function __construct($grade, $availability, $focus, $maxTrainingDays) {
        $this->grade = $grade;
        $this->availability = min($availability, $maxTrainingDays);
        $this->focus = $focus + 1;
        $this->exercises = loadExercises();
    }

    private function loadExercises() {
        // Load exercises from JSON file
        $exercisesJson = file_get_contents('exercise_directory.json');
        $exercises = json_decode($exercisesJson, true);
        return $exercises;
    }

    public function generateTrainingPlan() {
        $filteredExercises = $this->filterDifficuty();
        $trainingPlan = $this->createPlan($filteredExercises);
        return $trainingPlan;
    }

    private function filterDifficuty() {
        $filtered = [];
        foreach ($this->exercises as $exercise) {
            if ($exercise['difficulty'] <= $this->grade) {
                $filtered[] = $exercise;
            }
        }
        return $filtered;
    }

    private function numOfDays($maxStrengthTrainingDays) {
        $focus = $this->focus;
        $availability = $this->availability;
        $strengthDays = 0;
        $techniqueDays = 0;
        $mixDays = 0;

        switch($focus){
            case 1:
                $strengthDays = min($availability, $maxStrengthTrainingDays);
                $mixDays = $availability - $strengthDays;
                break;
            case 2:
                if ($availability <= 2) {
                    $mixDays = $availability;

                } else {
                    $strengthDays = min($availability, $maxStrengthTrainingDays);
                    $mixDays = ceil(($availability-$strengthDays)/2);
                    $techniqueDays = $availability -($mixDays + $strengthDays);
                }
                break;
            case 3:
                if ($availability >=2) {
                    $strengthDays = 1;
                    $techniqueDays = 1;
                    $mixDays = $availability - ($strengthDays + $techniqueDays);
                } else {
                    $mixDays = $availability;
                }
                break;
            case 4:
                $mixDays = ceil($availability * 0.3);
                $techniqueDays = $availability - $mixDays;
                break;
            case 5:
                $techniqueDays = $availability;
                break;
        }

        // Pack the values into an array
        $values = [$strengthDays, $techniqueDays, $mixDays];

        return $values;  
    }

    private function exerciseArrays() {

        $exercises = $this->exercises;
        // Filter exercises to keep only strength-type exercises
        $strengthExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'strength';
        });

        $techniqueExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'technique';
        });

        $conditioningExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'conditioning';
        });

        $values = [$strengthExercises, $techniqueExercises, $conditioningExercises];

        return $values;
    }

    private function dayAssignment() {
        $days = [];
        
    }

    private function createPlan() {

        $days = numOfDays();
        $strengthDays = $days[0];
        $techniqueDays = $days[1];
        $mixDays = $days[2];
        $day = dayAssignment($strengthDays,$techniqueDays,$mixDays);

        $exercises = exerciseArrays();
        $strengthExercises = $exercises[0];
        $techniqueExercises = $exercises[1];
        $conditioningExercises = $exercises[2];


        $availability = $this->availability;
        $conditioning = FALSE;

        $trainingPlan = [];
        $yesterdaysDay = "";
        $yesterdaysExercises = [];
        $daysSinceRest = 0;
    }

    private function strengthDay($exercises, $yesterdaysExercises) {

        shuffle($exercises);
        $day = [];

        for ($i = 0; $i == 3; $i++) {
             $day[] = array_pop($exercises);
        }
    }
}



function newPlan($un) {

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

    $trainingPlanGenerator = new ClimbingTrainingPlan($trainingDays, $climbingGrade, $focus);
    $trainingPlan = $trainingPlanGenerator->generateTrainingPlan();
    return json_encode($trainingPlan, JSON_PRETTY_PRINT);
}



?>
