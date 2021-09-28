<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = "all";

if(!empty($_GET["filter"])){
  $filter = explode(":",filter_input(INPUT_GET, "filter", FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Report on
              <?php 
                if(!is_array($filter)){
                  echo "All Tasks by workout";
                }else{
                  echo ucwords($filter[0]) . " : ";
                  switch($filter[0]){
                    case "workout":
                      $workout = get_workout($filter[1]);
                      echo $workout["title"];
                      break;
                    case "category":
                      echo $filter[1];
                      break;
                    case "date":
                      echo $filter[1]." - ". $filter[2];
                      break;
                  }
                }
              ?>
            </h1>
          <form class="form-container form-report" action ="reports.php" method ="get">
            <label for="filter">Filter:</label>
            <select id="filter" name="filter">
              <option vlaue="">Select One</option>
              <optgroup label="workout">
              <?php
                foreach(get_workout_list() as $item){
                  echo "<option value='workout:".$item["workout_id"]."'>";
                  echo $item["title"]."</option>\n";
                }
              ?>
              </optgroup>
              <optgroup label="Category">
                <option value="category:Abs">Abs</option>
                <option value="category:Biceps & Triceps">Biceps & Triceps</option>
                <option value="category:Legs">legs</option>
              </optgroup>
              <optgroup label="Date">
                <option value="date:<?php 
                  echo date("m/d/Y",strtotime("-2 Sunday"));
                  echo ":";
                  echo date("m/d/Y",strtotime("-1 Saturday"));
                ?>">Last Week</option>
                <option value="date:<?php 
                  echo date("m/d/Y",strtotime("-1 Sunday"));
                  echo ":";
                  echo date("m/d/Y");
                ?>">This Week</option>
                <option value="date:<?php 
                  echo date("m/d/Y",strtotime("first day of last month"));
                  echo ":";
                  echo date("m/d/Y",strtotime("last day of last month"));
                ?>">Last Month</option>
                <option value="date:<?php 
                  echo date("m/d/Y",strtotime("first day of this month"));
                  echo ":";
                  echo date("m/d/Y");
                ?>">This Month</option>
              </optgroup>
            </select>
            <input class="button" type="submit" value="Run"/>
          </form>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>
                  <?php 
                    $total = $workout_id = $workout_total = 0;
                    $tasks = get_task_list($filter);
                     foreach($tasks as $item){
                       if($workout_id != $item["workout_id"]){
                        $workout_id = $item["workout_id"];
                           echo "<thead>\n";
                           echo "<tr>\n";
                           echo "<th>".$item["title"]."</th>\n";
                           echo "<th>Date</th>\n";
                           echo "<th>Time</th>\n";
                           echo "</tr>\n";
                           echo "</thead>\n";
                       }
                      $workout_total += $item["time"];
                      $total += $item["time"];
                       echo "<tr>\n";
                       echo "<td>" .$item["title"]."</td>\n";
                       echo "<td>" .$item["date"]."</td>\n";
                       echo "<td>" .$item["time"]."</td>\n";
                       echo "</tr>\n";
                       if(next($tasks)["workout_id"] != $item["workout_id"]){
                           echo "<tr>\n";
                           echo "<th class='workout-total-label' colspan='2'>workout Total</th>\n";
                           echo "<th classs = 'workout-total-number'>$workout_total</th>\n";
                           echo "<tr>\n";
                           $workout_total= 0;
                         }
                     }
                  ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?php echo $total; ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>

