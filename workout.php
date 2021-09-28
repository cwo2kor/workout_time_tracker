<?php
require 'inc/functions.php';

$pageTitle = "workout | Time Tracker";
$page = "workouts";
$title = $category = "";

if(isset($_GET["id"])){
  $workout_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
  list($workout_id, $tittle, $category) = get_workout($workout_id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $workout_id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
  $title=trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
  $category=trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));
  
  if(empty($title) || empty($category)){
    $error_message = "Please fill in the required fields: Title, Category";
  }else{
    if(add_workout($title,$category,$workout_id)){
      header('Location:workout_list.php');
      exit;
    }else{
      $error_message = "workout not added correctly";
    }
  }
}

include 'inc/header.php';
?>

<div class="section page">
    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header"><?php
                if(!empty($workout_id)){
                  echo "Update";
                }else{
                  echo "Add";
                }
                ?> workout</h1>
            <?php
              if(isset($error_message)){
                echo "<p class='message'>$error_message</p>";
              }
            ?>
            <form class="form-container form-add" method="post" action="workout.php">
                <table>
                    <tr>
                        <th><label for="title">Title<span class="required">*</span></label></th>
                        <td><input type="text" id="title" name="title" value="<?php echo $title;?>" /></td>
                    </tr>
                    <tr>
                        <th><label for="category">Category<span class="required">*</span></label></th>
                        <td><select id="category" name="category">
                                <option value="">Select One</option>
                                <option value="Abs"<?php
                                  if($category == "Abs"){
                                    echo 'selected';
                                  }
                                  ?>>Abs</option>
                                <option value="Biceps & Triceps"<?php
                                  if($category == "Biceps & Triceps"){
                                    echo 'selected';
                                  }
                                  ?>>Biceps & Triceps</option>
                                <option value="Shoulder"<?php
                                  if($category == "Shoulder"){
                                    echo 'selected';
                                  }
                                  ?>>Shoulder</option>
                        </select></td>
                    </tr>
                </table>
              <?php 
                if(!empty($workout_id)){
                  echo "<input type ='hidden' name='id' value=".$workout_id."/>";
                }
              ?>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
