<?php
error_reporting(E_ERROR); 
ini_set('display_errors', 0);

require('config.php');
require('pdo.php');
if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
    if($_FILES['file']!=null){
        $uniqid = hash('sha256', uniqid(mt_rand(), true) );
        if(move_uploaded_file($_FILES['file']['tmp_name'], $filesdir . $uniqid ) ){
            try{
            $insertfile = getConnection()->prepare('INSERT INTO files (name, size, uploaddate, uniqid, password) VALUES (:name, :size, :uploaddate, :uniqid, :password)');
            $insertfile->bindValue( ':name', $_FILES['file']['name'] );
            $insertfile->bindValue( ':size' , filesize($filesdir . $uniqid) );
            $insertfile->bindValue( ':uploaddate' , date('Y-m-d H:m:s', time()) );
            $insertfile->bindValue( ':uniqid' , $uniqid);
            $insertfile->bindValue( ':password' , $_POST['password'] );
            $insertfile->execute();
            }catch (Exception $e){
                echo $e->getMessage();
                die($e);
            }
            $ret = array('status' => 'ok');
        }else{
            $ret = array('error' => 'move_file_error');
        }
    }
} else {
    $ret = array('error' => 'no_file');
}
header('Content-Type: application/json');
echo json_encode($ret);
exit;