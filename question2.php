<?php
session_start();
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup</title>
    <link href='css/questions.css' rel='stylesheet'>
</head>
</head>
<body>
    <div class="wrapper">

        <div class="hero">
            <h1 class="center">Account Setup</h1>
            <p class="center">Please answer the following questions to help design a plan for you.</p>
        </div>
        
        <div class="question">  
                <form oninput="output.value = Math.round(range.valueAsNumber / 1000)" method="post">

                    <h2 class="center">What is your focus?</h2>
                    <p>1 - Climbing , 5 - Powerlifting</p>

                    <div class="range-input">
                        <input type="range" min="0" max="5" value="0" step="1" id="focus" name ="focus">
                        <div class="value">
                          <div></div>
                        </div>
                    </div>

                    <button class="btn" type="submit">Confirm</button>

                    <?php
                    // Check if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        
                        if (isset($_POST["focus"])) {
                            savequestion($_SESSION["usr"],"focus",$_POST["focus"]);
                            header("location: question3.php");
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