<?php
require('php/CheckSession.php');
if (!$scheck) {
  session_start();
  session_unset();
  session_destroy();
  echo "0";
  // If more than one session is active, the following will cause all of them to expire
  //$hash="expired";
  //exec("sudo bin/writehash $hash");
  //unset($hash);
  exit();
} else {
  echo "1";
}
?>