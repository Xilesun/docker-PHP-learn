<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
<body>
<?
if($_SESSION['user']){
?>
<div class="content">Hello, <?= $_SESSION['user'] ?></div><a class="btn" href="function.php?logout=true">Logout</a>
<?
} else {
header("location: signin.html");
}
ob_end_flush();
?>
</body>
</html>

