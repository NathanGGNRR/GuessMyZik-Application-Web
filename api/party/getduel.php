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
            $stmt = $bdd->query('SELECT * FROM parties WHERE user_id != '.$iduser["id"].' AND game_duel = 1 AND id NOT IN(SELECT id FROM parties WHERE id IN (SELECT party_id FROM duels WHERE user_id = '.$iduser["id"].')) LIMIT 10');
            $parties = array();
            if ($stmt->rowCount() > 0) {
                $resultats = $stmt->fetchAll();
                foreach ($resultats as $resultat){
                    $resultat["party_id"] = $resultat["id"];
                    $stmt2 = $bdd->query('SELECT username FROM users WHERE id = '.$resultat["user_id"]);
                    if ($stmt2->rowCount() > 0) {
                        $row = $stmt2->fetch();
                        $resultat["username"] = $row["username"];
                    }
                    unset($resultat[1]);
                    unset($resultat["user_id"]);
                    unset($resultat[0]);
                    unset($resultat["id"]);
                    $stmt2 = $bdd->query('SELECT T.id, readable, title, title_short, title_version, link, duration, rank, explicit_lyrics, explicit_content_lyrics, explicit_content_cover, preview, artist_id, album_id, type FROM party_tracks P INNER JOIN tracks T ON P.tracks_id = t.id WHERE party_id = '.$resultat["party_id"]);
                    if ($stmt2->rowCount() > 0) {
                        $resultat["listTrack"] = array();
                        $tracks = $stmt2->fetchAll();
                        foreach ($tracks as $track){
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
                            $stmt3 = $bdd->query('SELECT * FROM artists WHERE id = "'.$track["artist_id"].'"');
                            if ($stmt3->rowCount() > 0) {
                                unset($track["artist_id"]);
                                unset($track[12]);
                                $track["artist"] = $stmt3->fetch();
                                if($track["artist"]["radio"] == 0){
                                    $track["artist"]["radio"] = false;
                                } else {
                                    $track["artist"]["radio"] = true;
                                }
                            }
                            $stmt3 = $bdd->query('SELECT * FROM albums WHERE id = "'.$track["album_id"].'"');
                            if ($stmt3->rowCount() > 0) {
                                unset($track["album_id"]);
                                unset($track[13]);
                                $track["album"] = $stmt3->fetch();
                                if($track["album"]["explicit_lyrics"] == 0){
                                    $track["album"]["explicit_lyrics"] = false;
                                } else {
                                    $track["album"]["explicit_lyrics"] = true;
                                }
                            }
                            $stmt3 = null;
                            array_push($resultat["listTrack"], $track);
                        }
                    }
                    $stmt2 = null;
                    array_push($parties, $resultat);
                }
                echo json_encode($parties);
            }  
            $stmt = null;
        }
        $user = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}