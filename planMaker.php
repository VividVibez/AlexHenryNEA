<?php

class ClimbingTrainingPlan
{
    // Properties declaration
    private $exercises; // Array to hold all available exercises
    private $availability; // Number of available training days
    private $climbingGrade; // Climbing grade of the user
    private $focus; // Focus level of the user
    private $maxTrainingDays = 5; // Maximum number of training days in a week
    private $maxStrengthTrainingDays = 3; // Maximum number of days allocated for strength training in a week
    private $maxExercisesPerDay = 5; // Maximum number of exercises per training day
    
    // Constructor to initialize object properties
    public function __construct($grade, $availability, $focus) {
        $this->grade = $grade; // Set climbing grade
        $this->availability = min($availability, $this->maxTrainingDays); // Limit availability to maximum training days
        $this->focus = $focus + 1; // Incrementing focus by 1 for easier handling
        $this->exercises = $this->loadExercises(); // Load exercises from external JSON file
    }

    // Load exercises from a JSON file
    private function loadExercises() {
        $exercisesJson = file_get_contents('exercise_directory.json'); // Read JSON file content
        $exercises = json_decode($exercisesJson, true); // Decode JSON data into an associative array
        return $exercises; // Return the array of exercises
    }

    // Generate a climbing training plan
    public function generateTrainingPlan() {
        $filteredExercises = $this->filterDifficuty(); // Filter exercises based on user's climbing grade
        $trainingPlan = $this->createPlan($filteredExercises); // Create a training plan using filtered exercises
        return $trainingPlan; // Return the generated training plan
    }

    // Filter exercises based on difficulty level
    private function filterDifficuty() {
        $filtered = [];
        foreach ($this->exercises as $exercise) {
            if ($exercise['difficulty'] <= $this->grade) {
                $filtered[] = $exercise;
            }
        }
        return $filtered; // Return filtered exercises
    }

    // Determine the number of days for each type of training
    private function numOfDays() {
        // Initialization
        $focus = $this->focus;
        $availability = $this->availability;
        $strengthDays = 0;
        $techniqueDays = 0;
        $mixDays = 0;

        // Determine training focus and allocate days accordingly
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

    // Split exercises into arrays based on their types
    private function exerciseArrays($exercises) {
        // Filter exercises based on type
        $strengthExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'strength';
        });

        $techniqueExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'technique';
        });

        $conditioningExercises = array_filter($exercises, function ($exercise) {
            return $exercise['type'] === 'conditioning';
        });

        // Pack the arrays into a single array
        $values = [$strengthExercises, $techniqueExercises, $conditioningExercises];
        return $values;
    }

    // Spread rest days throughout the week
    private function spreadRestDays($restDays) {
        $weekDays = array('','','','','','',''); // Array to represent each day of the week
        $interval = $restDays/7; // Calculate the interval between rest days
        $count = 1; // Counter to keep track of intervals
        
        for($i=0; $i<7; $i++) {
            if (round($interval) == $count) { // Check if the interval matches the counter
                $weekDays[$i] = 'rest'; // Set the day as a rest day
                $count ++; // Increment the counter
            }
            $interval = $restDays/7 + $interval; // Adjust the interval for the next iteration
        }
        return $weekDays; // Return the array representing the week with rest days spread
    }

    // Assign types of training to each day
    function dayAssignment($s,$t,$m) {
        $totalDays = $s + $t + $m; // Calculate total training days
        $restDays = 7 - $totalDays; // Calculate total rest days
        $first = TRUE; // Flag to mark the first strength training day

        $weekDays = $this->spreadRestDays($restDays); // Spread rest days throughout the week

        for($x=0; $x<=6; $x++) {
            if ($weekDays[$x] === 'rest') { // Check if the day is a rest day
                continue; // Skip to the next day
            }
            if ($s > 0 && $first == TRUE) { // Check if there are remaining strength training days and it's the first day
                $weekDays[$x] = 'strength'; // Assign the day as a strength training day
                $first = FALSE; // Set the first flag to false
                $s--; // Decrement the count of remaining strength training days
                continue; // Move to the next day
            }
            if ($s > 0 && $weekDays[$x - 1] != 'strength') { // Check if there are remaining strength training days and the previous day was not a strength training day
                $weekDays[$x] = 'strength'; // Assign the day as a strength training day
                $s--; // Decrement the count of remaining strength training days
            } else { // If strength training days are exhausted or the previous day was a strength training day
                if ($t > 0 && $m > 0) { // Check if there are remaining technique and mix training days
                    if (mt_rand(0,1) === 1 && $t > 0) { // Randomly choose between technique and mix training
                        $weekDays[$x] = 'technique'; // Assign the day as a technique training day
                        $t--; // Decrement the count of remaining technique training days
                    } else {
                        $weekDays[$x] ='mix'; // Assign the day as a mix training day
                        $m--; // Decrement the count of remaining mix training days
                    }
                } else { // If either technique or mix training days are exhausted
                    if ($t > 0) { // Check if there are remaining technique training days
                        $weekDays[$x] = 'technique'; // Assign the day as a technique training day
                        $t--; // Decrement the count of remaining technique training days
                    } else { // If technique training days are exhausted
                        $weekDays[$x] = 'mix'; // Assign the day as a mix training day
                        $m--; // Decrement the count of remaining mix training days
                    }
                }
            }
        }
        return $weekDays; // Return the array representing the types of training for each day of the week
    }

    // Create the training plan based on the type of training for each day
    private function createPlan($exercises) {
        // Determine the number of days for each type of training
        $typeOfDay = $this->numOfDays();
        $strengthDays = $typeOfDay[0];
        $techniqueDays = $typeOfDay[1];
        $mixDays = $typeOfDay[2];

        // Assign types of training to each day
        $days = $this->dayAssignment($strengthDays,$techniqueDays,$mixDays);

        // Split exercises into arrays based on their types
        $exercises = $this->exerciseArrays($exercises);
        $strengthExercises = $exercises[0];
        $techniqueExercises = $exercises[1];
        $conditioningExercises = $exercises[2];

        // Generate training plan
        $trainingPlan = []; // Initialize an array to hold the training plan
        $yesterdaysExercises = []; // Initialize an array to hold the exercises performed on the previous day

        for ($x = 0; $x <= 6; $x++) { // Loop through each day of the week
            if ($days[$x] === 'strength') { // Check if it's a strength training day
                // Generate exercises for the day and add them to the training plan
                $trainingPlan['Day ' . $x+1] = $this->day($strengthExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan; // Update yesterday's exercises with the current day's exercises
                continue; // Move to the next day
            }
            if ($days[$x] === 'technique') { // Check if it's a technique training day
                // Generate exercises for the day and add them to the training plan
                $trainingPlan['Day ' . $x+1] = $this->day($techniqueExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan; // Update yesterday's exercises with the current day's exercises
                continue; // Move to the next day
            }
            if ($days[$x] === 'mix') { // Check if it's a mix training day
                // Generate exercises for the day and add them to the training plan
                $trainingPlan['Day ' . $x+1] = $this->mixDay($strengthExercises, $techniqueExercises, $conditioningExercises, $yesterdaysExercises);
                $yesterdaysExercises = $trainingPlan; // Update yesterday's exercises with the current day's exercises
                continue; // Move to the next day
            } 
            $trainingPlan['Day' . $x+1][] = 'Rest Day'; // Add a rest day to the training plan
        }
        return $trainingPlan; // Return the generated training plan
    }

    // Generate exercises for a typical training day
    private function day($exercises, $conditioningExercises, $yesterdaysExercises) {
        shuffle($exercises); // Shuffle the array of exercises
        shuffle($conditioningExercises); // Shuffle the array of conditioning exercises
        $day = []; // Initialize an array to hold exercises for the day
        $i = 0; // Initialize a counter

        // Select exercises for the day
        do {
            $exercise = array_pop($exercises); // Remove and return the last element from the exercises array
            if (array_search($exercise, $yesterdaysExercises) === FALSE) { // Check if the exercise was not performed yesterday
                $day[] = $exercise; // Add the exercise to the day's exercises
                $i++; // Increment the counter
            }
        } while ($i <= 4); // Repeat until 5 exercises are selected

        // Add conditioning exercises randomly
        if (rand(0,1) == 1) { // Randomly decide whether to add conditioning exercises
            $day[] = array_pop($conditioningExercises); // Add a conditioning exercise to the day's exercises
            $day[] = array_pop($conditioningExercises); // Add another conditioning exercise to the day's exercises
        }
        return $day; // Return the array of exercises for the day
    }

    // Generate exercises for a mixed training day
    private function mixDay($strengthExercises, $techniqueExercises, $conditioningExercises, $yesterdaysExercises) {
        shuffle($strengthExercises); // Shuffle the array of strength exercises
        shuffle($conditioningExercises); // Shuffle the array of conditioning exercises
        shuffle($techniqueExercises); // Shuffle the array of technique exercises

        $day = []; // Initialize an array to hold exercises for the day
        $i = 0; // Initialize a counter

        // Select exercises for the day
        do {
            if (rand(0,1) === 1) { // Randomly choose between strength and technique exercises
                $exercise = array_pop($strengthExercises); // Remove and return the last element from the strength exercises array
            } else {
                $exercise = array_pop($techniqueExercises); // Remove and return the last element from the technique exercises array
            }
            
            if (array_search($exercise, $yesterdaysExercises) === FALSE) { // Check if the exercise was not performed yesterday
                $day[] = $exercise; // Add the exercise to the day's exercises
                $i++; // Increment the counter
            }
        } while ($i <= 4); // Repeat until 5 exercises are selected

        // Add conditioning exercises randomly
        if (rand(0,1) == 1) { // Randomly decide whether to add conditioning exercises
            $day[] = array_pop($conditioningExercises); // Add a conditioning exercise to the day's exercises
            $day[] = array_pop($conditioningExercises); // Add another conditioning exercise to the day's exercises
        }
        return $day; // Return the array of exercises for the day
    }
}

// Function to save the training plan to a file
function save($trainingPlan, $username, $conn) {
    $jsonTrainingPlan = json_encode($trainingPlan); // Convert training plan array to JSON format
    $filename = "plans/" . $username . ".json"; // Define the filename based on the username
    $file = fopen($filename, 'w'); // Open file for writing
    fwrite($file, $jsonTrainingPlan); // Write JSON data to the file
    fclose($file); // Close the file
}

// Function to generate a new training plan
function newPlan($un) {
    $conn = require __DIR__ . "/database.php"; // Include database connection

    // Fetch user information from the database
    $check = "SELECT *
    FROM basic_info
    WHERE username = '$un'";
    $rs = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($rs);

    // Extract user details
    $focus = $row['focus'];
    $climbingGrade = $row['grade']; 
    $trainingDays = $row['vacancy'];

    // Generate training plan
    $trainingPlanGenerator = new ClimbingTrainingPlan($climbingGrade, $trainingDays,  $focus);
    $trainingPlan = $trainingPlanGenerator->generateTrainingPlan();

    // Save training plan to file
    save($trainingPlan, $un, $conn);

    return $trainingPlan; // Return the generated training plan
}
?>
