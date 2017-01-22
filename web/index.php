<?php
session_start();
include('includes/conn.php');
require 'vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
$route = new RouteCollector();

$route->get('/', function(){
  if(!isset($_SESSION['user'])) {
    header("location: /signin");
  }
  require('templates/homepage.php');
});

$route->get('/signin', function(){
  if(isset($_SESSION['user'])) {
    header('location: /');
  }
  require('templates/signin.html');
});

$route->get('/signup', function(){
  require('templates/signup.html');
});

$route->post('/signin', function(){
  $conn = getPDO();
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  $num = $conn->prepare("SELECT id FROM user WHERE username = '$user' AND password = '$pass'");
  $num->execute();
  
  if($num->rowCount() == 1) {
    $_SESSION['user'] = $user;
    header('location: /');
  } else {
    header('location: /signin');
  }
});

$route->post('/signup', function(){
  $conn = getPDO();
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  $email = $_POST['email'];

  $sql = $conn->prepare("INSERT INTO `user`(`username`, `password`, `email`) VALUES ('$user', '$pass', '$email')");
  if($sql->execute()){
      header("location: /signin");
  }
});

$route->get('/validateuser', function(){  
  $conn = getPDO();
  $user = $_GET['user'];
  //check whether the username exists or not
  $check = $conn->prepare("SELECT id FROM user WHERE username = '$user'");
  $check->execute();

  if($check->rowCount() > 0) {
    echo json_encode('Username exists');
  } else {
    echo json_encode('true');
  }
});

$route->get('/logout', function(){
  session_destroy();
  header("location: /");
});

$dispatcher = new Dispatcher($route->getData());

$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;