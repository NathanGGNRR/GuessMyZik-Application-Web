<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        if($content == "ADD"){
            $stmt = $bdd->query('INSERT INTO guests() VALUES ()');
            $stmt = $bdd->query('SELECT id FROM guests ORDER BY id DESC LIMIT 1');
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                echo $row["id"];
            }
        } else {
            $stmt = $bdd->query('DELETE FROM guests WHERE id = '.intval($content));
        }
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}