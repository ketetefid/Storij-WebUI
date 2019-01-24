<?php
session_start();
if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  $hash="logout";
  file_put_contents ('/etc/SiaBerry/hashval',$hash);
  unset($hash);
  $domain = trim(shell_exec('source /etc/conf.d/hostname && echo $hostname')).'.local';
  header("Location: http://$domain/index.html?status=loggedout");
  exit();
}
?>
