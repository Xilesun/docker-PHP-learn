<?php
session_start();
require 'vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use DB\User;
use DB\Comment;
use DB\Message;

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

  $comments = Comment::all();
  if(!$comments) {
    $comments = [];
  }

  $all_msgs = Message::all();

  if(!$all_msgs) {
    $all_msgs = [];
  } else {
    foreach ($all_msgs as $value) {
      if($value['username'] == $_SESSION['user']) {
        $usr_msgs[] = $value;
      }
    }
  }
  
  echo $twig->render('homepage.html', array('user' => $_SESSION['user'], 'comments' => $comments, 'msgs' => $usr_msgs));
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

  $user = $_POST['user'];
  $pass = $_POST['pass'];

  $result = User::findByUser('password', $user);

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
  $new_user = new User();

  $user = $_POST['user'];
  $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
  $email = $_POST['email'];

  $new_user->username = $user;
  $new_user->password = $pass;
  $new_user->email = $email;

  $new_user->save();
  header("location: /signin");
});

$route->get('/validateuser', function(){  
  $user = $_GET['user'];
  //check whether the username exists or not
  $result = User::findByUser('id', $user);

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

$route->post('/post', function(){
  $new_comment = new Comment();

  $user = $_SESSION['user'];
  $comment = $_POST['comment'];
  $date = date('Y/m/d');

  if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $new_comment->id = $id;
  }

  if(isset($_GET['user'])) {
    $new_msg = new Message();

    $target_user = $_GET['user'];

    $new_msg->username = $target_user;
    $new_msg->comment_user = $user;

    $new_msg->save();
  }

  $new_comment->username = $user;
  $new_comment->comment = $comment;
  $new_comment->date = $date;

  $new_comment->save();
  header("location: /");
});

$route->get('/del', function(){
  $new_comment = new Comment();
  $id = $_GET['id'];

  $new_comment->del($id);
  header("location: /");
});

$dispatcher = new Dispatcher($route->getData());

$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;