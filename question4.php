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
    <!-- <link href='css/questions.css' rel='stylesheet'> -->
    <!-- <link href='css/logoutBtn.css' rel='stylesheet'> -->
    <link href='css/checkbox.css' rel='stylesheet'>

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

                    <fieldset class="checkbox-group">
                        <legend class="checkbox-group-legend">Choose your favorites</legend>
                        <div class="checkbox">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="checkbox-input" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="192" height="192" fill="currentColor" viewBox="0 0 256 256">
                                            <rect width="256" height="256" fill="none"></rect>
                                            <circle cx="96" cy="144.00002" r="10"></circle>
                                            <circle cx="160" cy="144.00002" r="10"></circle>
                                            <path d="M74.4017,80A175.32467,175.32467,0,0,1,128,72a175.32507,175.32507,0,0,1,53.59754,7.99971" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path>
                                            <path d="M181.59717,176.00041A175.32523,175.32523,0,0,1,128,184a175.32505,175.32505,0,0,1-53.59753-7.99971" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path>
                                            <path d="M155.04392,182.08789l12.02517,24.05047a7.96793,7.96793,0,0,0,8.99115,4.20919c24.53876-5.99927,45.69294-16.45908,61.10024-29.85086a8.05225,8.05225,0,0,0,2.47192-8.38971L205.65855,58.86074a8.02121,8.02121,0,0,0-4.62655-5.10908,175.85294,175.85294,0,0,0-29.66452-9.18289,8.01781,8.01781,0,0,0-9.31925,5.28642l-7.97318,23.91964" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path>
                                            <path d="M100.95624,182.08757l-12.02532,24.0508a7.96794,7.96794,0,0,1-8.99115,4.20918c-24.53866-5.99924-45.69277-16.459-61.10006-29.85069a8.05224,8.05224,0,0,1-2.47193-8.38972L50.34158,58.8607a8.0212,8.0212,0,0,1,4.62655-5.1091,175.85349,175.85349,0,0,1,29.66439-9.18283,8.0178,8.0178,0,0,1,9.31924,5.28642l7.97318,23.91964" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path>
                                        </svg>
                                    </span>
                                    <span class="checkbox-label">Discord</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="checkbox-input" checked />
                                <span class="checkbox-tile">
                                    <span class="checkbox-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="192" height="192" fill="currentColor" viewBox="0 0 256 256">
                                            <rect width="256" height="256" fill="none"></rect>
                                            <polygon points="56 100 56 168 128 236 128 168 200 168 56 32 200 32 200 100 56 100" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></polygon>
                                        </svg>
                                    </span>
                                    <span class="checkbox-label">Framer</span>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="checkbox-input" />
                                <span class="checkbox-tile">
                                    <span class="checkbox-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="192" height="192" fill="currentColor" viewBox="0 0 256 256">
                                            <rect width="256" height="256" fill="none"></rect>
                                            <polygon points="72 40 184 40 240 104 128 224 16 104 72 40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></polygon>
                                            <polygon points="177.091 104 128 224 78.909 104 128 40 177.091 104" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></polygon>
                                            <line x1="16" y1="104" x2="240" y2="104" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></line>
                                        </svg>
                                    </span>
                                    <span class="checkbox-label">Sketch</span>
                                </span>
                            </label>
                        </div>

                    </fieldset>

                    <button class="btn" type="submit">Confirm</button>

                    <?php
                    // Check if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        
                        if (isset($_POST["ava"])) {
                            savequestion($_SESSION["usr"],"vacancy",$_POST["ava"]);
                            header("location: myPlan.php");
                        }
                    }
                    ?>

                </form>  
            </div>
        </div>
    </div>
</body>
</html>