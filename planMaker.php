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
    
    public function __construct($grade, $availability, $focus) {
        $this->grade = $grade;
        $this->availability = min($availability, $this->maxTrainingDays);
        $this->focus = $focus + 1;
        $this->exercises = $this->loadExercises();
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

    private function numOfDays() {
        $focus = $this->focus;
        $availability = $this->availability;
        $strengthDays = 0;
        $techniqueDays = 0;
        $mixDays = 0;

        switch($focus){
            case 1:
                $strengthDays = min($availability, $this->maxStrengthTrainingDays);
                $mixDays = $availability - $strengthDays;
                break;
            case 2:
                if ($availability <= 2) {
                    $mixDays = $availability;

                } else {
                    $strengthDays = min($availability, $this->maxStrengthTrainingDays);
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
        $interval = $restDays/7;
        $count = 1;
        
          for($i=0; $i<7; $i++) {
            if (round($interval) == $count) {
                      
              $weekDays[$i] = 'rest';
              $count ++;
            }
            $interval = $restDays/7 + $interval;
          }
        return $weekDays;
    }

    function dayAssignment($s,$t,$m) {
        
        $totalDays = $s + $t + $m;
        $restDays = 7 - $totalDays;
        $first = TRUE;

        $weekDays = $this->spreadRestDays($restDays);

        for($x=0; $x<=6; $x++) {
	
          if ($weekDays[$x] === 'rest') {
            continue;
          }
          
          if ($s > 0 && $first == TRUE) {
            $weekDays[$x] = 'strength';
            $first = FALSE;
            $s--;
            continue;
          }
          
          if ($s > 0 && $weekDays[$x - 1] != 'strength') {
            $weekDays[$x] = 'strength';
            $s--;
          } else {
              if ($t > 0 && $m > 0) {
                if (mt_rand(0,1) === 1 && $t > 0) {
                  $weekDays[$x] = 'technique';
                  $t--;
                } else {
                  $weekDays[$x] ='mix';
                  $m--;
                }
              } else {
                if ($t > 0) {
                  $weekDays[$x] = 'technique';
                  $t--;
                } else {
                  $weekDays[$x] = 'mix';
                  $m--;
                }
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
            if ($days[$x] === 'strength') {
                $trainingPlan['Day ' . $x+1][] = $this->day($strengthExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan;
                continue;
            }
            if ($days[$x] === 'technique') {
                $trainingPlan['Day ' . $x+1][] = $this->day($techniqueExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan;
                continue;
            }
            if ($days[$x] === 'mix') {
                $trainingPlan['Day ' . $x+1][] = $this->mixDay($strengthExercises, $techniqueExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan;
                continue;
            }


        }
        return $trainingPlan;
    }

    private function day($exercises, $conditioningExercises, $yesterdaysExercises) {

        shuffle($exercises);
        shuffle($conditioningExercises);
        $day = [];
        $i = 0;

        do {
            $exercise = array_pop($exercises);
            if (array_search($exercise, $yesterdaysExercises) === FALSE) {
                $day[] = $exercise;
                $i++;
            }
        } while ($i <= 4);
        if (rand(0,1) == 1) {
            $day[] = array_pop($conditioningExercises);
            $day[] = array_pop($conditioningExercises);
        }
        return $day;
    }

    private function mixDay($strengthExercises, $techniqueExercises, $conditioningExercises, $yesterdaysExercises) {

        shuffle($strengthExercises);
        shuffle($conditioningExercises);
        shuffle($techniqueExercises);

        $day = [];
        $i = 0;

        do {
            if (rand(0,1) === 1) {
                $exercise = array_pop($strengthExercises);
            } else {
                $exercise = array_pop($techniqueExercises);
            }
            
            if (array_search($exercise, $yesterdaysExercises) === FALSE) {
                $day[] = $exercise;
                $i++;
            }
        } while ($i <= 4);
        if (rand(0,1) == 1) {
            $day[] = array_pop($conditioningExercises);
            $day[] = array_pop($conditioningExercises);
        }
        return $day;
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

    $trainingPlanGenerator = new ClimbingTrainingPlan($climbingGrade, $trainingDays,  $focus);
    $trainingPlan = $trainingPlanGenerator->generateTrainingPlan();

    foreach ($trainingPlan as $day => $workouts) {
        
        echo "<h2>$day</h2>";
        echo "<ul>";
        foreach ($workouts[0] as $exercise) {
            echo "<li>";
            echo "Name: {$exercise['name']}, Equipment: {$exercise['equipment']}, Difficulty: {$exercise['difficulty']}, Type: {$exercise['type']}";
            echo "</li>";
        }
        echo "</ul>";
    }
    return json_encode($trainingPlan, JSON_PRETTY_PRINT);
}
?>
