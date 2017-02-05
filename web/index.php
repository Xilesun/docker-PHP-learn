<?php
session_start();
include('includes/model.php');
require 'vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

$route = new RouteCollector();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

class User extends db_model {
  protected $table_name = 'user';
}

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

  $new_user = new User();

  $user = $_POST['user'];
  $pass = $_POST['pass'];

  $result = $new_user->findByUser('password', $user);

  if($result) {
    if(password_verify($pass, $result['password'])) {
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

  $new_user = new User();

  $user = $_POST['user'];
  $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
  $email = $_POST['email'];

  $new_user->username = $user;
  $new_user->password = $pass;
  $new_user->email = $email;

  $new_user->save();
  # header("location: /signin");
});

$route->get('/validateuser', function(){  
  global $conn;
  
  $new_user = new User();

  $user = $_GET['user'];
  //check whether the username exists or not
  $result = $new_user->findByUser('id', $user);

  if($result) {
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