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
            $stmt = $bdd->query('SELECT COUNT(T.id) AS nbtrackguessed FROM tracks_guessed T INNER JOIN elements_found E ON E.id = T.element_found_id WHERE user_id = '.$iduser["id"].' AND element ="track"');
            $row = $stmt->fetch();
            echo $row["nbtrackguessed"];
            $stmt = null;
        }
        $user = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}