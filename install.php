<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
require('config.php');
require('pdo.php');
?>
<html>
<body>
<form action="install.php" method="POST">
    <input name="user" type="text" />
    <input name="password" type="password"/>
    <input type="submit" value="CREATE TABLE"/>
</form>
<pre>
<?php
try{
if(isset($_POST['user']) && isset($_POST['password'])){
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $create = getConnection()->exec("
    CREATE TABLE IF NOT EXISTS files (
        id INT(8) NOT NULL AUTO_INCREMENT,
        hashid VARCHAR(512) NOT NULL,
        name VARCHAR(256) NOT NULL,
        size INT(8) NOT NULL,
        uploaddate DATETIME NOT NULL,
        password VARCHAR(512) NULL,
        PRIMARY KEY (id)
    )");
    print_r($create);
}
}catch (Exception $e){
    print $e;
}
?>

</body>
</html>