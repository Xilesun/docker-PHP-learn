<?php
session_start();
include('includes/conn.php');
require 'vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use twig\twig;

$route = new RouteCollector();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$route->get('/', function() use ($twig) {
  if(isset($_COOKIE['user'])) {
    $_SESSION['user'] = $_COOKIE['user'];
  }
  if(!isset($_SESSION['user'])) {
    header("location: /signin");
  }
  echo $twig->render('homepage.html', array('user' => $_SESSION['user']));
});

$route->get('/signin', function() use ($twig) {
  if(isset($_SESSION['user'])) {
    header('location: /');
  }
  echo $twig->render('signin.html');
});

$route->get('/signup', function() use ($twig) {
  echo $twig->render('signup.html');
});

$route->post('/signin', function(){
  setcookie('user', null, -1);

  global $conn;

  $user = $_POST['user'];
  $pass = $_POST['pass'];

  $sql = $conn->prepare("SELECT password FROM user WHERE username = :user");
  $sql->bindParam(':user', $user, PDO::PARAM_STR);
  $sql->execute();
  
  if($sql->rowCount() == 1) {
    $hash = $sql->fetch();
    if(password_verify($pass, $hash['password'])) {
      $_SESSION['user'] = $user;
      if($_POST['remember'] == 'on') {
        setcookie('user', $user, time()+60*60*24*7);
      }
      header('location: /');
    } else {
      echo 'Password incorrect';
    }
  } else {
    echo 'Username incorrect';
  }
});

$route->post('/signup', function(){
  global $conn;

  $user = $_POST['user'];
  $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
  $email = $_POST['email'];

  $sql = $conn->prepare("INSERT INTO `user`(`username`, `password`, `email`) VALUES (:user, :pass, :email)");
  $sql->bindParam(':user', $user, PDO::PARAM_STR);
  $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
  $sql->bindParam(':email', $email, PDO::PARAM_STR);
  if($sql->execute()){
      header("location: /signin");
  }
});

$route->get('/validateuser', function(){  
  global $conn;
  
  $user = $_GET['user'];
  //check whether the username exists or not
  $check = $conn->prepare("SELECT id FROM user WHERE username = :user");
  $check->bindParam(':user', $user);
  $check->execute();

  if($check->rowCount() > 0) {
    echo json_encode('Username exists');
  } else {
    echo json_encode('true');
  }
});

$route->get('/logout', function(){
  session_destroy();
  setcookie('user', null, -1);
  header("location: /");
});

$dispatcher = new Dispatcher($route->getData());

$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;