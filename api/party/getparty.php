<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $user = $bdd->query('SELECT id FROM users WHERE username = "'.$content.'"');
        if ($user->rowCount() > 0) {
            $iduser = $user->fetch();
            $stmt = $bdd->query('SELECT * FROM parties WHERE user_id = '.$iduser["id"]);
            if ($stmt->rowCount() > 0) {
                $resultats = $stmt->fetchAll();
                foreach ($resultats as $resultat){
                    $resultat["party_id"] = $resultat["id"];
                    $resultat["username"] = $content;
                    unset($resultat[1]);
                    unset($resultat["user_id"]);
                    unset($resultat[0]);
                    unset($resultat["id"]);
                    $resultat["listTrack"] = null;
                }
                echo json_encode($resultats);
            }  
            $stmt = null;
        }
        $user = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}