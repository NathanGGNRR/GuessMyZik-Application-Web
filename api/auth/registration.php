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
        $arrayInfo = array();
        $stmt = $bdd->query('SELECT username FROM users WHERE username = "'.$value->username.'"');
        if ($stmt->rowCount() > 0) {
            $arrayInfo["username"] = "NO";
        }
        $stmt = $bdd->query('SELECT mail FROM users WHERE mail = "'.$value->mail.'"');
        if ($stmt->rowCount() > 0) {
            $arrayInfo["mail"] = "NO";
        }
        if(empty($arrayInfo)){
            $stmt = $bdd->query('INSERT INTO users(username, password, mail) VALUES ("'.$value->username.'", "'.$value->password.'", "'.$value->mail.'")');
            echo "YES";
        } else {
            echo json_encode($arrayInfo);
        }
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}