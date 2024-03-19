<?php
session_start();
include 'functions.php';
if (!isset($_SESSION["usr"])) {
    header("location: login.php");
}
planSetup($_SESSION["usr"]);

function done($un) {
    $conn = require __DIR__ . "/database.php";

    $doneQuestions = "SELECT *
    FROM plan
    WHERE username = '$un'";

    $rs = mysqli_query($conn, $doneQuestions);
    $row = mysqli_fetch_assoc($rs);
    $done = $row['answered'];
    
    if ($done == 1) {
        header("location: myPlan.php");
    }
}
done($_SESSION["usr"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup</title>
    <link href='css/questions.css' rel='stylesheet'>
    <link href='css/logoutBtn.css' rel='stylesheet'>
</head>
</head>
<body>
    <div class="logout">
        <form method="post">
            <input type="submit" name="logout" class="logoutBtn" value="Logout">
        </form>
    </div>
    <?php
    if(array_key_exists('logout', $_POST)) { 
        unset($_SESSION["usr"]);
        header("location: login.php");
    } 
    ?>
    <div class="wrapper">

        <div class="hero">
            <h1>Account Setup</h1>
            <p>Please answer the following questions to help design a plan for you.</p>
        </div>
        
        <div class="question">  
                <form oninput="output.value = Math.round(range.valueAsNumber / 1000)" method="post">

                    <h2>What Bouldering Grade is your highest achieved in the last 2 years?</h2>

                    <div class="range-input">
                        <input type="range" min="0" max="17" value="0" step="1" id="grade" name ="grade">
                        <div class="value">
                          <div></div>
                        </div>
                    </div>

                    <button class="btn" type="submit">Confirm</button>

                    <?php
                    // Check if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        
                        if (isset($_POST["grade"])) {
                            savequestion($_SESSION["usr"], "grade", $_POST["grade"]);
                            header("location: question2.php");
                        }
                    }
                    ?>

                </form>  
            </div>
        </div>
    </div>
<script>
let rangeInput = document.querySelector(".range-input input");
let rangeValue = document.querySelector(".range-input .value div");

let start = parseFloat(rangeInput.min);
let end = parseFloat(rangeInput.max);
let step = parseFloat(rangeInput.step);

for(let i=start;i<=end;i+=step){
  rangeValue.innerHTML += '<div>'+i+'</div>';
}

rangeInput.addEventListener("input",function(){
  let top = parseFloat(rangeInput.value)/step * -40;
  rangeValue.style.marginTop = top+"px";
});
</script>
</body>
</html>