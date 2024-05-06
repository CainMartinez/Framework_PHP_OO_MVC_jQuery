<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once "model/Conf.class.singleton.php";

    try {
        $conf = Conf::getInstance();
        $host = $conf->getHostDB();
        $db = $conf->getDB();
        $user = $conf->getUserDB();
        $pass = $conf->getPassDB();

        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

        $sql = "SELECT * FROM `large_people` ORDER BY id_large_people;";
        $stmt = $pdo->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
            echo "<br>";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
?>