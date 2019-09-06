<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $value = json_decode($content);
        $stmt = $bdd->query('SELECT id FROM users WHERE username = "'.$value->username.'"');
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $user_id =  $row["id"];
            $stmt2 = $bdd->query('SELECT id FROM tracks WHERE id = "'.$value->track_id.'"');
            if ($stmt2->rowCount() > 0) {
                $stmt3 = $bdd->query('SELECT id FROM elements_found WHERE element = "'.$value->element.'"');
                if ($stmt3->rowCount() > 0) {
                    $row2 = $stmt3->fetch();
                    $element_id =  $row2["id"];
                    $stmt4 = $bdd->query('INSERT INTO tracks_guessed(user_id, track_id, element_found_id, date_guessed) VALUES ('.$user_id.', "'.$value->track_id.'",'.$element_id.',"'.$value->date.'")');
                }
            }
        }  
        $stmt = null;
        $stmt2 = null;
        $stmt3 = null;
        $stmt4 = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}