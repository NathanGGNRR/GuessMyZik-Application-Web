<?php
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $stmt = $bdd->query('SELECT * FROM levels');
        if($stmt->rowCount() > 0){
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultats = $stmt->fetchAll();
            echo json_encode($resultats);
        }
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }