<?php
use DB\User;
use Security\Cookie;
use Security\SessionSecurity;

$route->get('/signin', function() use ($twig) {
  if(!empty(SessionSecurity::find())) {
    header('location: /');
  }
  echo $twig->render('signin.html');
});

$route->get('/signup', function() use ($twig) {
  echo $twig->render('signup.html');
});

$route->post('/signin', function(){
  Cookie::delete('user');

  $user = $_POST['user'];
  $pass = $_POST['pass'];

  $result = User::findByUser('password', $user);

  if($result) {
    if(password_verify($pass, $result['password'])) {
      $_SESSION['user'] = $user;
      if($_POST['remember'] == 'on') {
        Cookie::set('user', $user, time()+60*60*24*7);
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
  Cookie::delete('user');
  header("location: /");
});