<?php
function connexpdo($base, $param)
{
    include_once($param . ".inc.php");
    $dsn = "mysql:host=localhost;dbname=bookstore";
    $user = "root";
    $pass = '';
    try {
        $idcom = new PDO($dsn, $user, $pass,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],);
        return $idcom;
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
        //return false;
    }
}

?>
