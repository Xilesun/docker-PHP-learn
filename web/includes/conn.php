<?php
function getPDO(){
  return new PDO('mysql:host=mysql;dbname=web', 'root', 'yangqia');
}
