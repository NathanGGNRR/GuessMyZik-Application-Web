<?php
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $login = trim(file_get_contents("php://input"));
        $stmt = $bdd->query('SELECT * FROM users U WHERE mail = "'.$login.'" OR username = "'.$login.'"');
        if($stmt->rowCount() > 0){
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultats = $stmt->fetchAll();
            echo json_encode($resultats[0]);
        } else {
            echo "NO";
        }
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }