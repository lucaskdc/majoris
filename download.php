<?php
require('config.php');
require('pdo.php');
if(isset($_GET['action']) && isset($_GET['hashid'])){
    if( ($_GET['action']=='download') && ($_GET['hashid']!=null) ){
        //DOWNLOAD
        $sql = getConnection()->prepare('SELECT name, size FROM files WHERE hashid = :hashid');
        $sql->bindValue(':hashid', $_GET['hashid']);
        $sql->execute();
        $file = $sql->fetch();
        $name = $file['name'];
        $size = $file['size'];
        if($name!=''){
            $mime = new finfo;            
            header('Content-Description: File Transfer');
            header('Content-type: ' . $mime->file($dir . $_GET['hashid'] , FILEINFO_MIME) );
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Content-Length: ' . $size);
            readfile($dir.$_GET['hashid']);
        }
    }
}
?>