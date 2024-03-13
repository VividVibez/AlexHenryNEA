<?php
session_start();
include 'functions.php';

if (!isset($_SESSION["usr"])) {
    header("location: login.php");
}

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
            <h1 class="center">Account Setup</h1>
            <p class="center">Please answer the following questions to help design a plan for you.</p>
        </div>
        
        <div class="question">  
                <form oninput="output.value = Math.round(range.valueAsNumber / 1000)" method="post">

                    <h2 class="center">Whats is your avaliability? (Days a Week)</h2>

                    <div class="range-input">
                        <input type="range" min="0" max="7" value="0" step="1" id="ava" name ="ava">
                        <div class="value">
                          <div></div>
                        </div>
                    </div>

                    <button class="btn" type="submit">Confirm</button>

                    <?php
                    // Check if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        
                        if (isset($_POST["ava"])) {
                            savequestion($_SESSION["usr"],"vacancy",$_POST["ava"]);
                            $_SESSION["questions"] = TRUE;
                            header("location: myPlan.php");
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