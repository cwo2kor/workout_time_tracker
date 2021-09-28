<?php
require 'inc/functions.php';

$page = "workout";
$pageTitle = "workout List | Time Tracker";

if(isset($_POST["delete"])){
  if(delete_workout(filter_input(INPUT_POST,'delete',FILTER_SANITIZE_NUMBER_INT))){
    header("location:workout_list.php?msg=workout+Deleted");
     exit;
  }else{
    header("location: workout_list.php?msg=Unable+To+Delete+workout");
    exit;
  }
}
if(isset($_GET["msg"])){
  $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>
<div class="section catalog random">

    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header">workout List</h1>
            <div class="actions-item">
                <a class="actions-link" href="workout.php">
                <span class="actions-icon">
                  <svg viewbox="0 0 64 64"><use xlink:href="#workout_icon"></use></svg>
                </span>
                Add workout
                </a>
            </div>
            <?php
              if(isset($error_message)){
                echo "<p class='message'>$error_message</p>";
              }
            ?>
            <div class="form-container">
                <ul class="items">
                   <?php
                      foreach(get_workout_list() as $item){
                        echo "<li><a href ='workout.php?id=".$item["workout_id"]."'>".$item["title"]."</a>";
                        echo "<form method='post' action='workout_list.php' onsubmit=\"return confirm('Are your sure you want to delete this workout?');\">\n";
                        echo "<input type='hidden' value ='".$item["workout_id"]."' name='delete'/>\n";
                        echo "<input type='submit' class='button--delete' value='Delete'/>\n";
                        echo "</form>";
                        echo "</li>";
                      }
                    ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<?php include("inc/footer.php"); ?>
