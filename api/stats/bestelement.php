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
            $stmt = $bdd->query('SELECT COUNT(element) AS nbType, element FROM tracks_guessed T INNER JOIN elements_found E ON T.element_found_id = E.id WHERE T.user_id = '.$iduser["id"].' GROUP BY element_found_id  ORDER BY nbType DESC LIMIT 1');
            $row = $stmt->fetch();
            echo $row["element"];
            $stmt = null;
        }
        $user = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}