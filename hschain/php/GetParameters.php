<?php
$expand = shell_exec('source /boot/storij/parameters.txt; echo $expand');
$setup_xsc = shell_exec('source /boot/storij/parameters.txt; echo $setup_xsc');
$siadir = shell_exec('source /boot/storij/parameters.txt; echo $XSCDIR');
$siausr = shell_exec('source /boot/storij/parameters.txt; echo $SIAUSR');
$int_ip = shell_exec('source /boot/storij/parameters.txt; echo $int_ip');
$ext_ip = shell_exec('source /boot/storij/parameters.txt; echo $ext_ip');
$domain_name = shell_exec('source /boot/storij/parameters.txt; echo $domain_name');
$rpc_port = shell_exec('source /boot/storij/parameters.txt; echo $xsc_rpc_port');
$host_port = shell_exec('source /boot/storij/parameters.txt; echo $xsc_host_port');
$auto_unlock = shell_exec('source /boot/storij/parameters.txt; echo $xsc_auto_unlock');
$ext_ip_type = shell_exec('source /boot/storij/parameters.txt; echo $ext_ip_type');
$freedns_login = shell_exec('source /boot/storij/parameters.txt; echo $freedns_login');
$freedns_domain = shell_exec('source /boot/storij/parameters.txt; echo $freedns_domain');
$use_domain = shell_exec('source /boot/storij/parameters.txt; echo $use_domain');
$disk_layout = shell_exec('source /boot/storij/parameters.txt; echo $disklayout');
?>
