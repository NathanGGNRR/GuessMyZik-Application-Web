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
            $kdr = array();
            $stmt = $bdd->query('SELECT COUNT(*) AS win FROM duels D INNER JOIN parties P ON P.id = D.party_id WHERE D.user_id = '.$iduser["id"].' AND D.points > P.points');
            $row = $stmt->fetch();
            array_push($kdr, $row["win"]);
            $stmt = $bdd->query('SELECT COUNT(*) AS loose FROM duels D INNER JOIN parties P ON P.id = D.party_id WHERE D.user_id = '.$iduser["id"].' AND D.points < P.points');
            $row = $stmt->fetch();
            array_push($kdr, $row["loose"]);
            $stmt = null;
            echo json_encode($kdr);
        }
        $user = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}