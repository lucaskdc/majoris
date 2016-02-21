<?php
require('config.php');
require('pdo.php');
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if(isset($_POST['uniqid']) ){ //Delete file
    try{
        $filequery = getConnection()->prepare('SELECT password FROM files WHERE uniqid = :uniqid');
        $filequery->bindValue(':uniqid', $_POST['uniqid']);
        $filequery->execute();
        $file = $filequery->fetch();
    }catch(Exception $e){ 
        echo $e->getMessage();
        die($e);
    }
    if($file['password'] == $_POST['password']){
        try{
            $deletequery = getConnection()->prepare('DELETE FROM files WHERE uniqid = :uniqid');
            $deletequery->bindValue(':uniqid', $_POST['uniqid']);
            $deletequery->execute() or die('DB ERROR');
            unlink($filesdir . $_POST['uniqid']) or die('UNLINK ERROR');
        }catch(Exception $e){
            echo $e->getMessage();
            die($e);
        }
        $ret = array('status' => 'ok');
    }else{
        $ret = array('error' => 'invalid_password'); 
    }
}else{ 
    $ret = array('error' => 'post_error');
}
header('Content-Type: application/json');
echo json_encode($ret);
exit;