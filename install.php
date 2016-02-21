<html>
<body>
<form action="install.php" method="POST">
    DB User<input name="user" type="text" />
    DB Password<input name="password" type="password"/>
    DB Name<input name="dbname" type="text"/>
    <input type="submit" value="INSTALL"/>
</form>
<pre>
<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if((isset($_POST['user']) && isset($_POST['password'])) && isset($_POST['dbname'])){
    $rdir = getcwd() . '/';
    $configfile = fopen($rdir . 'config.php', 'w') or die ("Config. file open error.");
    fwrite($configfile, 
        "<?php"
        ."\n" . "#CONFIG"
        ."\n" . "\$filesdir = '" . $rdir . "files/';"
        ."\n" . "#CONFIG DB"
        ."\n" . "\$dbname = '" . $_POST['dbname'] . "';"
        ."\n" . "\$host = 'localhost';"
        ."\n" . "\$user = '" . $_POST['user'] . "';"
        ."\n" . "\$password = '" . $_POST['password'] . "';"
        ."\n" . "?>"
    ) or die ("Config. file write error.");
    fclose($configfile) or die ("Config. file close error.");
    echo("Config. file has been created.");
  
    mkdir("files/", 0755); 
    echo("\nfiles/ folder has been created.");
    $filesfolderindex = fopen('files/index.html', 'w');
    fclose($filesfolderindex);
    echo("\nBlank index.html has been created in files/ folder.");
    
    try{
        $pdo = new PDO('mysql:host=127.0.0.1;dbname='. $_POST['dbname'] , $_POST['user'], $_POST['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
    } catch (PDOException $e){
        echo $e->getMessage();
        die($e);
    }
    $create = $pdo->exec("
    CREATE TABLE files (
        id INT(8) NOT NULL AUTO_INCREMENT,
        uniqid VARCHAR(64) NOT NULL,
        name VARCHAR(256) NOT NULL,
        size INT(8) NOT NULL,
        uploaddate DATETIME NOT NULL,
        password VARCHAR(512) NULL,
        PRIMARY KEY (id)
    )
    CHARACTER SET utf8
    ENGINE = MyISAM");
    echo("\nFiles table has been created");
}
?>

</body>
</html>