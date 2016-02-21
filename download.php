<?php
require('config.php');
require('pdo.php');
if(isset($_GET['action']) && isset($_GET['uniqid'])){
    if( ($_GET['action']=='download') && ($_GET['uniqid']!=null) ){
        //DOWNLOAD
        $sql = getConnection()->prepare('SELECT name, size FROM files WHERE uniqid = :uniqid');
        $sql->bindValue(':uniqid', $_GET['uniqid']);
        $sql->execute();
        $file = $sql->fetch();
        $name = $file['name'];
        $size = $file['size'];
        if($name!=''){
            $mime = new finfo;            
            header('Content-Description: File Transfer');
            header('Content-type: ' . $mime->file($filesdir . $_GET['uniqid'] , FILEINFO_MIME) );
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Content-Length: ' . $size);
            readfile($dir.$_GET['uniqid']);
        }
    }
}
?>