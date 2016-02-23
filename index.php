<?php
require('config.php');
require('pdo.php');
$maxsize=100;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono" rel="stylesheet" type="text/css">
        <style type="text/css">
            body{background-color:#FFF;margin: 16px;padding: 0px;
                font-family: 'Share Tech Mono';}
            header{font-size: 26px;}
            #bunito{margin: 0px;width:900px;margin-top: 16px;margin-bottom: 15px;height: 1px;}
            #fum100{font-size: 18px;}
            table, th, td {
                border: 1px solid black;
                    font-weight: bold;
            }
            #tudo{padding: 20px;margin: 0 auto;margin-top:2%;width:900px;
                border:2px solid grey;border-radius: 4px;}

        </style>
    <title>File Server</title>
</head>
<body><section id="tudo">
    <header>
        <b>File Server</b>
        <hr id="bunito" size="1" color="grey" />
    </header>
        
    <section id="forms" style="border-size:4px;border-color:#000;">
        <p>File upload(Max: <?php echo $maxsize; ?> MiB):</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data" id="formupload">
            <p>File: <input name="file" type="file" /> </p>
            <p>Password <b>(to delete):</b> <input name="password" type="text" /> </p>
            <p> <input type="submit" value="Enviar" /> </p>
        </form>
    </section>
    <hr id="bunito" size="1" color="grey" />
    <div id="log"></div>
    <hr id="bunito" size="1" color="grey" />

    <section id="filed">
        <p><b>File Download</b></p>
        <table>
            <tr>
                <td>Name</td>
                <td>Size</td>
                <td>Upload Date</td>
                <td>Actions</td>
            </tr>
    <?php
        $queryfiles = getConnection()->prepare("SELECT name,size,uploaddate,hashid FROM files ORDER BY uploaddate DESC");
        $queryfiles->execute();
        $file = $queryfiles->fetch();
        while( $file['name']!='' ){
            echo'<tr>
                            <td>' .$file['name']. '</td>
                            <td>' .$file['size']. '</td>
                            <td>' .$file['uploaddate']. '</td>
                            <td> <a href="download.php?action=download&hashid=' .$file['hashid']. '">Download</a> <a href="javascript:deletefile(\'' .$file['hashid']. '\',\'' .$file['name']. '\');">Delete</a> </td>
            </tr>';
            $file = $queryfiles->fetch();
        }
    ?>
        </table>
    </section></section>
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
function deletefile(hashid, nome){
    $forms.innerHTML='<p>Are you sure you want to delete "' + nome + '"?'
    +'<form id="formdelete" action="delete.php" method="POST">'
    +'Password: <input type="text" name="password"/>'
    +'<input type="hidden" name="hashid" value="' + hashid + '"/>'
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
