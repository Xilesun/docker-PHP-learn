<?php
session_start();
require 'vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
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

include('includes/Routes/User.php');

$route->post('/comments', function(){
  $new_comment = new Comment();

  $user = $_SESSION['user'];
  $comment = $_POST['comment'];
  $date = date('Y/m/d');

  $new_comment->username = $user;
  $new_comment->comment = $comment;
  $new_comment->date = $date;

  $new_comment->save();
  header("location: /");
});

$route->put('/comments/{id:\d+}', function($id){
  $new_comment = new Comment();

  $user = $_SESSION['user'];
  $comment = $_POST['comment'];
  $date = date('Y/m/d');

  $new_comment->id = $id;
  $new_comment->username = $user;
  $new_comment->comment = $comment;
  $new_comment->date = $date;

  $new_comment->save();
  header("location: /");
});

$route->post('/comments/{name}', function($name){
  $new_comment = new Comment();

  $user = $_SESSION['user'];
  $comment = $_POST['comment'];
  $date = date('Y/m/d');

  $new_comment->username = $user;
  $new_comment->comment = $comment;
  $new_comment->date = $date;

  $new_comment->save();

  $new_msg = new Message();

  $target_user = $name;

  $new_msg->username = $target_user;
  $new_msg->comment_user = $user;

  $new_msg->save();

  header("location: /");
});

$route->delete('/comments/{id:\d+}', function($id){
  $new_comment = new Comment();
  $comment_id = $id;

  $new_comment->del($comment_id);
  return true;
});

$dispatcher = new Dispatcher($route->getData());
$method = isset($_REQUEST['_method']) ? $_REQUEST['_method'] : $_SERVER['REQUEST_METHOD'];
$response = $dispatcher->dispatch($method, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;