<?php
    require_once('../ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $content = trim(file_get_contents("php://input"));
        $value = json_decode($content);
        $username = $value[0];
        $level_id = $value[1];
        $xp = $value[2];
        $stmt = $bdd->query('UPDATE users SET xp='.intval($xp).', level_id='.intval($level_id).'  WHERE username="'.$username.'"');
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }