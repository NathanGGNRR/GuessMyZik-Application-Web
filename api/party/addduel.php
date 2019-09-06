<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $value = json_decode($content);
        $stmt = $bdd->query('INSERT INTO duels(party_id, user_id, points) VALUES('.intval($value[0]).','.intval($value[1]).','.intval($value[2]).')');
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}