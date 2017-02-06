<?php
include('conn.php');
session_start();

if($_GET['validateuser']) {
  $user = $_GET['validateuser'];
  //check whether the username exists or not
  $check = $conn -> prepare("SELECT id FROM user WHERE username = '$user'");
  $check -> execute();

  if($check -> rowCount() > 0) {
    echo 'Username exists';
  } else {
    echo 'success';
  }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($_POST['signin']){
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $num = $conn -> prepare("SELECT id FROM user WHERE username = '$user' AND password = '$pass'");
    $num -> execute();
    
    if($num -> rowCount() == 1) {
      $_SESSION['user'] = $user;
      header("location: /");
    } else {
      echo "failed";
    }
  }
  
  if($_POST['signup']){
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];

    $sql = $conn -> prepare("INSERT INTO `user`(`username`, `password`, `email`) VALUES ('$user', '$pass', '$email')");
    if($sql -> execute()){
      header("location: signin.html");
    }
  }
}

if($_GET['logout']){
  session_destroy();
  header("location: /");
}
?>
