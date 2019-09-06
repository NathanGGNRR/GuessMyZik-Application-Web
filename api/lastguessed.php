<?php
$content = trim(file_get_contents("php://input"));
if($content != null){
    require_once('ConnectionInfo.php');
    $connectionInfo = new ConnectionInfo();
    $bdd = $connectionInfo->GetConnection();
    if(!$bdd){
        echo 'Connexion Failed';
    } else {
        $stmt = $bdd->query('SELECT id FROM users WHERE username = "'.$content.'"');
        if ($stmt->rowCount() > 0) {
            $list = array();
            $row = $stmt->fetch();
            $stmt2 = $bdd->query('SELECT track_id FROM tracks_guessed WHERE user_id = '.intval($row["id"]).' ORDER BY date_guessed LIMIT 8');
            if ($stmt2->rowCount() > 0) {
                $resultats = $stmt2->fetchAll();
                foreach ($resultats as $resultat){
                    $stmt3 = $bdd->query('SELECT * FROM tracks WHERE id = "'.$resultat["track_id"].'"');
                    if ($stmt3->rowCount() > 0) {
                        $track = $stmt3->fetch();
                        if($track["readable"] == 0){
                            $track["readable"] = false;
                        } else {
                            $track["readable"] = true;
                        }
                        if($track["explicit_lyrics"] == 0){
                            $track["explicit_lyrics"] = false;
                        } else {
                            $track["explicit_lyrics"] = true;
                        }
                        $stmt4 = $bdd->query('SELECT * FROM artists WHERE id = "'.$track["artist_id"].'"');
                        if ($stmt4->rowCount() > 0) {
                            unset($track["artist_id"]);
                            unset($track[12]);
                            $track["artist"] = $stmt4->fetch();
                            if($track["artist"]["radio"] == 0){
                                $track["artist"]["radio"] = false;
                            } else {
                                $track["artist"]["radio"] = true;
                            }
                        }
                        $stmt4 = $bdd->query('SELECT * FROM albums WHERE id = "'.$track["album_id"].'"');
                        if ($stmt4->rowCount() > 0) {
                            unset($track["album_id"]);
                            unset($track[13]);
                            $track["album"] = $stmt4->fetch();
                            if($track["album"]["explicit_lyrics"] == 0){
                                $track["album"]["explicit_lyrics"] = false;
                            } else {
                                $track["album"]["explicit_lyrics"] = true;
                            }
                        }
                        $stmt4 = null;
                    }
                    array_push($list, $track);
                }
                $stmt3 = null;
                echo json_encode($list);
            } 
            $stmt2 =  null;  
        }  
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}