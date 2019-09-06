<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $values = json_decode($content);
        foreach($values as $value){
            $stmt = $bdd->query('SELECT * FROM tracks WHERE id = "'.$value->id.'"');
            if ($stmt->rowCount() > 0) {
                $value->stocked = 1;
            } else {
                $value->stocked = 0;
            }
        }
        echo json_encode($values);
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}