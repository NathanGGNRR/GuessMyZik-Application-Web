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
        var_dump($value);
        $stmt = $bdd->query('UPDATE users SET password="'.$value->password.'" WHERE username = "'.$value->username.'"');
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}