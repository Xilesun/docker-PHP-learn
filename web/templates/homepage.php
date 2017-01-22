<?php
ob_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
  </head>
<body>
<?
if(isset($_SESSION['user'])){
?>
<div class="content">Hello, <?= $_SESSION['user'] ?></div><a class="btn" href="/logout">Logout</a>
<?
} else {
header("location: /signin");
}
ob_end_flush();
?>
</body>
</html>

