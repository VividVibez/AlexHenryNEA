<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href='css/home.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>
</head>
<body>
<section class="strips">

  <article class="strips__strip">
    <div class="strip__content">
      <h1 class="strip__title">My Plan</h1>
    </div>
  </article>

  <article class="strips__strip">
    <div class="strip__content">
      <h1 class="strip__title">Progress</h1>>
    </div>
  </article>

  <article class="strips__strip">
    <div class="strip__content">
      <h1 class="strip__title">Exercise</h1>
    </div>
  </article>

  <article class="strips__strip">
    <div class="strip__content">
      <h1 class="strip__title">Account</h1>
    </div>
  </article>

  <article class="strips__strip">
    <div class="strip__content">
      <h1 class="strip__title">Logout</h1>
    </div>
  </article>

</section>
<script>
    var myPlan = document.querySelector('.stripts__strip');
    mmyPlan.addEventListener("click", function() {
        <?php 
        header("location: question2.php")
        ?>
    });

</script>
</body>
</html>