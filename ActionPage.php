<?php
session_start();
if (isset($_POST['uname']) and isset($_POST['psw'])) {
  if( strpos($_POST['uname'],'/') === false && strpos($_POST['uname'],';') === false &&
      strpos($_POST['psw'],'/') === false && strpos($_POST['psw'],';') === false ) {
    // Check if the post request comes from the login page
    if (isset($_POST['auth2']) and hash_equals($_POST['auth2'],hash_hmac('sha256', '/ActionPage.php', $_SESSION['auth_token']))) {
      $siausr = trim(shell_exec('source /boot/parameters.txt; echo $SIAUSR'));
      $user=$_POST['uname'];
      $pass=$_POST['psw'];
      exec("sudo bin/checker $user $pass", $output, $exitcode);
      if ( $exitcode === 0 and strcmp($siausr,$user) === 0 ) {
	session_regenerate_id(true);
	$_SESSION['uname'] = $user;
	$_SESSION['timeout'] = time();
	$_SESSION['authenticator']=bin2hex(random_bytes(32));
	$hash=$_SESSION['authenticator'];
	exec("sudo bin/writehash $hash");
	unset($hash);
	header('Location: home.html');
	exit();
      } else {
	session_unset();
	session_destroy();
	header('Location: index.html?status=invalid');
	exit();
      }
    } else {
      session_unset();
      session_destroy();
      header('Location: index.html?status=invalid');
      exit();
    }
  } else {
    session_unset();
    session_destroy();
    header('Location: index.html?status=invalid');
    exit();
  }
}

if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  $hash="loggout";
  exec("sudo bin/writehash $hash");
  unset($hash);
  header('Location: index.html?status=loggedout');
  exit();
}
?>