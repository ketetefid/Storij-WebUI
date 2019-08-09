<?php
session_start(['read_and_close' => true]);
$hashval=trim(file_get_contents('/etc/storij/hashval'));
// As well as setting the JS function for autologout, we still need to check if the user has manually entered this page.
(boolean)$scheck=(isset($_SESSION['uname']) and isset($_SESSION['authenticator']) and
		  isset($_SESSION['timeout']) and time() - $_SESSION['timeout'] < 1200 and
		  hash_equals($_SESSION['authenticator'],$hashval));
?>