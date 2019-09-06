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
        $stmt = $bdd->query('UPDATE parties SET points = '.$value->points.' WHERE id ='.$value->party_id);
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}