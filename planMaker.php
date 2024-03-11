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

    private function spreadRestDays($restDays) {
        $weekDays = array('','','','','','','');
 

        if ($restDays === 2) {
            $weekDays[2] = 'rest';
            $weekDays[5] = 'rest';
        } elseif ($restDays === 3) {
            $weekDays[2] = 'rest';
            $weekDays[4] = 'rest';
            $weekDays[6] = 'rest';
        } elseif ($restDays === 4) {
            $weekDays[0] = 'rest';
            $weekDays[2] = 'rest';
            $weekDays[3] = 'rest';
            $weekDays[5] = 'rest';
        } else {
            $weekDays[0] = 'rest';
            $weekDays[1] = 'rest';
            $weekDays[3] = 'rest';
            $weekDays[4] = 'rest';
            $weekDays[5] = 'rest';
        }
        return $weekDays;
    }

    private function dayAssignment($s,$t,$m) {
        
        $totalDays = $s + $t + $m;
        $restDays = 7 - $totalDays;

        $weekDays = $this->spreadRestDays($restDays);

        for($x=0; $x<=6; $x++) {
	
            if ($weekDays[$x] === 'rest') {
                print('rest');
            } else{
                if ($weekDays[max($x - 1,0)] === 'strength') {
                    if ($t > 0) {
                        $weekDays[$x] = 'technique';
                        $t--;
                        continue;
                    } elseif ($m > 0) {
                        $weekDays[$x] = 'mix';
                        $m--;
                        continue;
                    }
                } else {
                    $weekDays[$x] = 'strength';
                    $s--;
                    continue;
                }
            }
        }
        return $weekDays;
    }

    private function createPlan() {

        $typeOfDay = $this->numOfDays();
        $strengthDays = $typeOfDay[0];
        $techniqueDays = $typeOfDay[1];
        $mixDays = $typeOfDay[2];
        $days = $this->dayAssignment($strengthDays,$techniqueDays,$mixDays);

        $exercises = $this->exerciseArrays();
        $strengthExercises = $exercises[0];
        $techniqueExercises = $exercises[1];
        $conditioningExercises = $exercises[2];

        $trainingPlan = [];
        $yesterdaysExercises = [];
        for ($x = 0; $x <= 6; $x++) {
            if ($day === 'strength') {
                $trainingPlan['Day' + $x][] = $this->strengthDay($strengthExercises,$yesterdaysExercises);
                continue;
            }
            if ($day === 'technique') {
                $trainingPlan['Day' + $x][] = $this->techniqueDay($strengthExercises,$yesterdaysExercises);
                continue;
            }
            if ($day === 'mix') {
                $trainingPlan['Day' + $x][] = $this->strengthDay($strengthExercises,$yesterdaysExercises);
                continue;
            }

        }


 
    }

    private function strengthDay($exercises, $yesterdaysExercises) {

        shuffle($exercises);
        $day = [];

        do {
            $exercise = array_pop($exercises);
            if (array_search($exercise, $exercises) === FALSE) {
                $day[] = $exercise
            }
        }
        
            
        
        return $day
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
