<?php
session_start();
if (isset($_POST['uname']) and isset($_POST['psw'])) {
  $user=$_POST['uname'];
  $pass=$_POST['psw'];
  if (preg_match('/^[a-z0-9]*$/',$user) && preg_match('/^[0-9]*$/',$pass)) {
    // Check if the post request comes from the login page
    if (isset($_POST['auth2']) and hash_equals($_POST['auth2'],hash_hmac('sha256', '/ActionPage.php', $_SESSION['auth_token']))) {
      require_once 'php/GoogleAuthenticator.php';
      $siausr = trim(shell_exec('source /boot/storij/parameters.txt; echo $SIAUSR'));
      $tfsecret = trim(file_get_contents('/etc/storij/2fa-secret'));
      $tfauth = new PHPGangsta_GoogleAuthenticator();
      $checkResult=$tfauth->verifyCode($tfsecret, $pass, 0);
      if ( $checkResult and strcmp($siausr,$user) === 0 ) {
	session_regenerate_id(true);
	$_SESSION['uname'] = $user;
	$_SESSION['timeout'] = time();
	$_SESSION['authenticator']=bin2hex(random_bytes(32));
	$hash=$_SESSION['authenticator'];
    file_put_contents ('/etc/storij/hashval',$hash);
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
  $hash="logout";
  file_put_contents ('/etc/storij/hashval',$hash);
  unset($hash);
  header('Location: index.html?status=loggedout');
  exit();
}
if (isset($_POST['twoFAsec'])) {
  $twoFAsec=$_POST['twoFAsec'];
  $tzone=$_POST['TimezoneList'];
  file_put_contents ('/etc/storij/2fa-secret',$twoFAsec);
  file_put_contents ('/etc/storij/timezone',$tzone);
  exec("sudo /usr/local/bin/settimezone");
  unset($twoFAsec);
  header('Location: index.html?status=2FA_EN');
  exit();
}

?>
