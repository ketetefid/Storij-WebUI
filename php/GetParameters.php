<?php
$expand = shell_exec('source /boot/parameters.txt; echo $expand');
$format_flash = shell_exec('source /boot/parameters.txt; echo $format_flash');
$siadir = shell_exec('source /boot/parameters.txt; echo $SIADIR');
$siausr = shell_exec('source /boot/parameters.txt; echo $SIAUSR');
$int_ip = shell_exec('source /boot/parameters.txt; echo $int_ip');
$ext_ip = shell_exec('source /boot/parameters.txt; echo $ext_ip');
$domain_name = shell_exec('source /boot/parameters.txt; echo $domain_name');
$rpc_port = shell_exec('source /boot/parameters.txt; echo $rpc_port');
$host_port = shell_exec('source /boot/parameters.txt; echo $host_port');
$auto_unlock = shell_exec('source /boot/parameters.txt; echo $auto_unlock');
$ext_ip_type = shell_exec('source /boot/parameters.txt; echo $ext_ip_type');
$freedns_login = shell_exec('source /boot/parameters.txt; echo $freedns_login');
$freedns_domain = shell_exec('source /boot/parameters.txt; echo $freedns_domain');
?>