<?php
require('config.php');

function getConnection (){
    global $dbservertype, $host, $db, $user, $pass;
    /*   if($dbservertype == 'sqlite'){
        try{
            $pdo = new PDO('sqlite:'.$db); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo $e->getMessage();
            die($e);
        }
    }
    else*/if($dbservertype == 'mysql' || $dbservertype == 'mariadb' ){
        try{
            $pdo = new PDO('mysql:host='.$host.';dbname='. $db , $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        } catch (PDOException $e){
            echo $e->getMessage();
            die($e);
        }
    }else{die("FATAL ERROR: var $dbservertype is not valid!");}
    
    return $pdo;
}
?>
