<?php
require('config.php');
require('pdo.php');
if(isset($_POST['hashid']) ){ //Delete file
    try{
        $passwordquery = getConnection()->prepare('SELECT password FROM files WHERE hashid = :hashid');
        $passwordquery->bindValue(':hashid', $_POST['hashid']);
        $passwordquery->execute();
        $password = $passwordquery->fetch();
    }catch(Exception $e){ print_r ($e);}
    if($password['password'] == $_POST['password']){
        try{
            $deletequery = getConnection()->prepare('DELETE FROM files WHERE hashid = :hashid');
            $deletequery->bindValue(':hashid', $_POST['hashid']);
            $deletequery->execute() or die('DB ERROR');
            unlink($dir . $_POST['hashid']) or die('UNLINK ERROR');
        }catch(Exception $e){ print_r ($e);}
        $ret = array('status' => 'ok');
    }else{ $ret = array('error' => 'invalid_password'); }
}else{ $ret = array('error' => 'post_error');}
header('Content-Type: application/json');
echo json_encode($ret);
exit;