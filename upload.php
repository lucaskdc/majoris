<?php
require('config.php');
require('pdo.php');
if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
    if($_FILES['file']!=null){
        $hashid = hash($hashmethod, uniqid());
        if(move_uploaded_file($_FILES['file']['tmp_name'], $dir . $hashid ) ){
            try{
            $includefile = getConnection()->prepare('INSERT INTO files (name, size, uploaddate, hashid, password) VALUES (:name, :size, :uploaddate, :hashid, :password)');
            $includefile->bindValue( ':name', $_FILES['file']['name'] );
            $includefile->bindValue( ':size' , filesize($dir . $hashid) );
            $includefile->bindValue( ':uploaddate' , date('Y-m-d H:m:s', time()) );
            $includefile->bindValue( ':hashid' , $hashid );
            $includefile->bindValue( ':password' , $_POST['password'] );
            $includefile->execute();
            }catch (Exception $e){ print_r ($e);}
            echo 'OK';
            echo '<script>document.location.href="index.php";</script>';
        }else{
            echo 'Move Error';
        }
    }
} else {
    echo 'Upload Error';
}
exit;