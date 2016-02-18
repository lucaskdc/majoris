<?php
#####
#CONFIG
$dir = '/srv/http/files/';
$hashmethod = 'sha512';
#####
#CONFIG DB
$db = 
    //'/usr/share/nginx/fileserver/files.sqlite'; //SQLITE DB
    'fileserver'; //MYSQLDB
$host = 'localhost';
$dbservertype =
    //'sqlite';
    'mysql';
$user = 'root';
$pass = '071169deusehtudo';
####
?>