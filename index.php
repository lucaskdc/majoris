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

</head>

<body>



<p>File Server</p>

<hr />
</hr>

<div id="forms">
<p>File upload(max100MB):</p>
<form action="upload.php" method="POST" enctype="multipart/form-data" id="formupload">
    <p>File: <input name="file" type="file" /> </p>
    <!-- <p>Change Name: <input name="changename" type="text" /> </p> -->
    <p>Password (to delete): <input name="password" type="text" /> </p>
    <p> <input type="submit" value="Send" /> </p>
</form>
</div>

<hr>
    <div id="log"></div>
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
        $queryfiles = getConnection()->prepare("SELECT name,size,uploaddate,uniqid FROM files ORDER BY uploaddate DESC");
        $queryfiles->execute();
        $file = $queryfiles->fetch();
        while( $file['name']!='' ){
            echo'<tr>
                            <td>' .$file['name']. '</td>
                            <td>' .$file['size']. '</td>
                            <td>' .$file['uploaddate']. '</td>
                            <td> <a href="download.php?action=download&uniqid=' .$file['uniqid']. '">Download</a> <a href="javascript:deletefile(\'' .$file['uniqid']. '\',\'' .$file['name']. '\');">Delete</a> </td>
            </tr>';
            $file = $queryfiles->fetch();
        }
    ?>       
</table>
</body>
<script>
    var $formUpload = document.getElementById('formupload'),
        $preview = document.getElementById('log'),
        i = 0;

    $formUpload.addEventListener('submit', function(event){
        event.preventDefault();

        var xhr = new XMLHttpRequest();

        xhr.open("POST", $formUpload.getAttribute('action'));

        var formData = new FormData($formUpload);
        formData.append("i", i++);
        xhr.send(formData);

        xhr.addEventListener('readystatechange', function() {
            if (xhr.readyState === 4 && xhr.status == 200) {
            var json = JSON.parse(xhr.responseText);

            if (!json.error && json.status === 'ok') {
                $preview.innerHTML += '<br />Enviado!!';
                setTimeout(location.reload(true), 1500);
            } else {
                $preview.innerHTML = 'Arquivo não enviado';
            }

            }
        });

        xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
            var percentage = Math.round((e.loaded * 100) / e.total);
            $preview.innerHTML = String(percentage) + '%';
            }
        }, false);

        xhr.upload.addEventListener("load", function(e){
            $preview.innerHTML = String(100) + '%';
        }, false);

    }, false);
</script>    

<script>
$forms = document.getElementById("forms");
function deletefile(uniqid, nome){
    $forms.innerHTML='<p>Are you sure you want to delete "' + nome + '"?'
    +'<form id="formdelete" action="delete.php" method="POST">'
    +'Password: <input type="text" name="password"/>'
    +'<input type="hidden" name="uniqid" value="' + uniqid + '"/>'
    +'<input type="submit" value="Delete">'
    +'</form>';
    var $formDelete = document.getElementById('formdelete'),
        $log = document.getElementById('log'),
        i = 0;

    $formDelete.addEventListener('submit', function(event){
        event.preventDefault();

        var xhr = new XMLHttpRequest();

        xhr.open("POST", $formDelete.getAttribute('action'));

        var formData = new FormData($formDelete);
        formData.append("i", i++);
        xhr.send(formData);

        xhr.addEventListener('readystatechange', function() {
                if (xhr.readyState === 4 && xhr.status == 200) {
                var json = JSON.parse(xhr.responseText);

                if (!json.error && json.status === 'ok') {
                    $log.innerHTML += '<br />Deletado!!';
                    setTimeout(location.reload(true), 1500);
                } else {
                    $log.innerHTML = 'Arquivo não deletado';
                }
            }
        });
    }, false);
}
</script>
</html>
