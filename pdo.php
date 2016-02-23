<?php
function getConnection (){
    require('config.php');
    try{
        $pdo = new PDO('mysql:host='.$host.';dbname='. $dbname , $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        $pdo->exec('SET NAMES utf8');
    } catch (PDOException $e){
        echo $e->getMessage();
        die($e);
    }
    return $pdo;
}
?>
