<?php
$expand = shell_exec('source /boot/SiaBerry/parameters.txt; echo $expand');
$setup_sc = shell_exec('source /boot/SiaBerry/parameters.txt; echo $setup_sc');
$siadir = shell_exec('source /boot/SiaBerry/parameters.txt; echo $SCDIR');
$siausr = shell_exec('source /boot/SiaBerry/parameters.txt; echo $SIAUSR');
$int_ip = shell_exec('source /boot/SiaBerry/parameters.txt; echo $int_ip');
$ext_ip = shell_exec('source /boot/SiaBerry/parameters.txt; echo $ext_ip');
$domain_name = shell_exec('source /boot/SiaBerry/parameters.txt; echo $domain_name');
$rpc_port = shell_exec('source /boot/SiaBerry/parameters.txt; echo $sc_rpc_port');
$host_port = shell_exec('source /boot/SiaBerry/parameters.txt; echo $sc_host_port');
$auto_unlock = shell_exec('source /boot/SiaBerry/parameters.txt; echo $sc_auto_unlock');
$ext_ip_type = shell_exec('source /boot/SiaBerry/parameters.txt; echo $ext_ip_type');
$freedns_login = shell_exec('source /boot/SiaBerry/parameters.txt; echo $freedns_login');
$freedns_domain = shell_exec('source /boot/SiaBerry/parameters.txt; echo $freedns_domain');
$use_domain = shell_exec('source /boot/SiaBerry/parameters.txt; echo $use_domain');
$disk_layout = shell_exec('source /boot/SiaBerry/parameters.txt; echo $disklayout');
?>
