<?php
require('config.php');
require('pdo.php');
?>

<html>

<head>
    <title>File Server</title>
    <style type="text/css">
        table, th, td {
            border: 1px solid black;
	    font-weight: bold;
        }
    
    </style>
</script>
    
    
</head>

<body>
<p>File Server</p>

<hr />
<?php
    if(isset($_GET['action']) && isset($_GET['hashid'])){ //Show a form to send the password to delete.
        if( ($_GET['action']=='delete' ) && ($_GET['hashid']!=null) ){
            echo'<form action="index.php" method=POST>
                PASSWORD to delete: <input type="text" name="password"/>
                <input type="hidden" name="action" value="delete"/>
                <input type="hidden" name="hashid" value="'.$_GET['hashid'].'"/>
                <input type="submit">
            </form>';
        }
    }
    if( isset($_POST['action']) && isset($_POST['hashid']) ){ //Delete file
        if( ($_POST['action'] == 'delete') && ($_POST['hashid'] != '') ){
            $passwordquery = getConnection()->prepare('SELECT password FROM files WHERE hashid = :hashid');
            $passwordquery->bindValue(':hashid', $_POST['hashid']);
            $passwordquery->execute();
            $password = $passwordquery->fetch();

            if($password['password'] == $_POST['password']){
                $deletequery = getConnection()->prepare('DELETE FROM files WHERE hashid = :hashid');
                $deletequery->bindValue(':hashid', $_POST['hashid']);
                $deletequery->execute() or die('DB ERROR');
                unlink($dir . $_POST['hashid']) or die('UNLINK ERROR');
            }
        }
    }
?>    
<hr />

<p>File upload(max100MB):</p>
<form action="upload.php" method="POST" enctype="multipart/form-data" id="formupload">
    <p>File: <input name="file" type="file" /> </p>
    <!-- <p>Change Name: <input name="changename" type="text" /> </p> -->
    <p>Password (to delete): <input name="password" type="text" /> </p>
    <p> <input type="submit" value="Send" /> </p>
</form>

<hr>


<p>File download</p>
<table>
    <tr>
        <td>Name</td>
        <td>Size</td>
        <td>Upload Date</td>
        <td>Actions</td>
    </tr>
    <?php
        $queryfiles = getConnection()->prepare("SELECT name,size,uploaddate,hashid FROM files");
        $queryfiles->execute();
        $file = $queryfiles->fetch();
        while( $file['name']!='' ){
            echo'<tr>
                            <td>' .$file['name']. '</td>
                            <td>' .$file['size']. '</td>
                            <td>' .$file['uploaddate']. '</td>
                            <td> <a href="download.php?action=download&hashid=' .$file['hashid']. '">Download</a> <a href="index.php?action=delete&hashid=' .$file['hashid']. '">Delete</a> </td>
            </tr>';
            $file = $queryfiles->fetch();
        }
    ?>       
</table>
</body>
</html>