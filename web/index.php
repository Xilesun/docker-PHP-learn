<?php
require 'vendor/autoload.php';
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use DB\Comment;
use DB\Message;
use Security\SessionSecurity;
use Security\Cookie;
$handler = new SessionSecurity();
session_set_save_handler($handler, true);
session_start();

$route = new RouteCollector();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$route->get('/', function() use ($twig) {
  $cookie = Cookie::get('user');
  if(isset($_COOKIE['user'])) {
    $_SESSION['user'] = $cookie;
  }
  if(empty(SessionSecurity::find())) {
    header("location: /signin");
  }

  $comments = Comment::findByPage(1, 5);
  if(!$comments) {
    $comments = [];
    $nextPage = -1;
  } else {
    $comments_count = Comment::count();
    if ($comments_count > 5) {
      $nextPage = 2;
    }
  }

  $all_msgs = Message::all();
  if(!$all_msgs) {
    $all_msgs = [];
    $usr_msgs = [];
  } else {
    foreach ($all_msgs as $value) {
      if($value['username'] == $_SESSION['user']) {
        $usr_msgs[] = $value;
      }
    }
  }
  echo $twig->render('homepage.html', array('user' => $_SESSION['user'], 'comments' => $comments, 'msgs' => $usr_msgs, 'page' => 1, 'nextPage' => $nextPage));
});

$route->get('/page/{page:\d+}', function($page) use ($twig) {
  $cookie = Cookie::get('user');
  if(isset($_COOKIE['user'])) {
    $_SESSION['user'] = $cookie;
  }
  if(empty(SessionSecurity::find())) {
    header("location: /signin");
  }

  $comments = Comment::findByPage($page, 5);
  if(!$comments) {
    $comments = [];
    $lastPage = -1;
    $nextPage = -1;
  } else {
    $comments_count = Comment::count();
    if ($comments_count > $page * 5) {
      $nextPage = $page + 1;
    }
    if ($page != 1) {
      $lastPage = $page - 1;
    }
  }

  $all_msgs = Message::all();
  if(!$all_msgs) {
    $all_msgs = [];
    $usr_msgs = [];
  } else {
    foreach ($all_msgs as $value) {
      if($value['username'] == $_SESSION['user']) {
        $usr_msgs[] = $value;
      }
    }
  }

  if($_GET['rerender']) {
    return json_encode(array($comments, $comments_count));
    exit;
  } 
  echo $twig->render('homepage.html', array('user' => $_SESSION['user'], 'comments' => $comments, 'msgs' => $usr_msgs, 'lastPage' => $lastPage, 'page' => $page, 'nextPage' => $nextPage));
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

  $commentArray = Comment::all();
  return json_encode($commentArray);
});

$route->put('/comments/{id:\d+}', function($id){
  $new_comment = new Comment($id);
  $user = $_SESSION['user'];
  $comment = $_POST['comment'];
  $date = date('Y/m/d');

  $new_comment->username = $user;
  $new_comment->comment = $comment;
  $new_comment->date = $date;

  $new_comment->save();
  echo $comment;
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

  $commentArray = Comment::all();
  return json_encode($commentArray);
});

$route->delete('/comments/{id:\d+}', function($id){
  Comment::delete($id);
  return true;
});

$dispatcher = new Dispatcher($route->getData());
$method = isset($_REQUEST['_method']) ? $_REQUEST['_method'] : $_SERVER['REQUEST_METHOD'];
$response = $dispatcher->dispatch($method, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;