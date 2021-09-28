<?php
//application functions

function get_workout_list(){
  include "connection.php";
  try{
  return $db->query("SELECT * FROM workouts");
  }catch (Exception $e){
    echo "Error: ".$e->getMessage() ."</br>";
    return array();
  }
}

function get_task_list($filter = NULL){
  include "connection.php";
  
  $sql = "SELECT tasks.*, workouts.title as workout FROM tasks JOIN workouts ON tasks.workout_id = workouts.workout_id";
  
  $where ="";
  if(is_array($filter)){
    switch($filter[0]){
      case "workout":
        $where = " WHERE workouts.workout_id = ?";
        break;
      case "category":
        $where = " WHERE category = ?";
      break;
      case "date":
        $where = " WHERE date >= ? AND date <= ?";
      break;
    }
  }
  
  $orderBy = " ORDER BY date DESC";
  if($filter){
    $orderBy = " ORDER BY workouts.title ASC, date DESC";
  }
  
  try{
    $results = $db->prepare($sql.$where.$orderBy);
    if(is_array($filter)){
      $results->bindValue(1,$filter[1]);
      if($filter[0] == "date"){
        $results->bindValue(2,$filter[2],PDO::PARAM_STR);
      }
    }
    $results->execute();
  }catch (Exception $e){
    echo "Error: ".$e->getMessage() ."</br>";
    return array();
  }
  return $results->fetchAll(PDO::FETCH_ASSOC);
}

function add_workout($title,$category,$workout_id = NULL){
  include "connection.php";
  if($workout_id){
    $sql = "UPDATE workouts SET  title = ?, category = ? WHERE workout_id = ?";
  }else{
    $sql = "INSERT INTO workouts(title, category) VALUES(?,?)";
  }
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$category,PDO::PARAM_STR);
    if($workout_id){
      $results->bindValue(3,$workout_id,PDO::PARAM_INT);
    }
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  return true;
}

function get_workout($workout_id){
  include "connection.php";
  $sql = "SELECT * FROM workouts WHERE workout_id = ?";
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$workout_id,PDO::PARAM_INT);
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  return $results->fetch();
}

function get_task($task_id){
  include "connection.php";
  $sql = "SELECT task_id, title, date, time, workout_id FROM tasks WHERE workout_id = ?";
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$task_id,PDO::PARAM_INT);
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  return $results->fetch();
}

function delete_task($task_id){
  include "connection.php";
  $sql = "DELETE FROM tasks WHERE task_id = ?";
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$task_id,PDO::PARAM_INT);
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  return true;
}

function delete_workout($workout_id){
  include "connection.php";
  $sql = "DELETE FROM workouts WHERE workout_id = ?"
    ." AND workout_id NOT IN (SELECT workout_id FROM tasks)";
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$workout_id,PDO::PARAM_INT);
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  if($results->rowCount()>0){
    return true;
  }else{
    return false;
  }
}

function add_task($workout_id,$title,$date,$time,$task_id=NULL){
  include "connection.php";
  if($task_id){
   $sql = "UPDATE tasks SET workout_id = ?, title= ?, date= ?,time = ? WHERE task_id = ?";
  }else{
    $sql = "INSERT INTO tasks(workout_id, title, date, time) VALUES(?,?,?,?)";
  }
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$workout_id,PDO::PARAM_INT);
    $results->bindValue(2,$title,PDO::PARAM_STR);
    $results->bindValue(3,$date,PDO::PARAM_STR);
    $results->bindValue(4,$time,PDO::PARAM_INT);
    if($task_id){
      $results->bindValue(5,$task_id,PDO::PARAM_INT);
    }
    $results->execute();
  }catch (Exception $e){
    echo "Error:".$e->getMessage()."<br/>";
    return false;
  }
  return true;
}