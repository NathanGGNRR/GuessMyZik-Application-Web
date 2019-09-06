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
        $stmt = $bdd->query('SELECT id FROM users WHERE username = "'.$value->username.'"');
        $partyid = 0;
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $stmt2 = $bdd->query('INSERT INTO parties(user_id, date, classType, nb_tracks, game_duel) VALUES ("'.$row["id"].'", "'.$value->date.'",'.$value->classType.','.$value->nb_tracks.','.$value->game_duel.')');
            $stmt2 = $bdd->query("SELECT LAST_INSERT_ID()");
            $partyid = $stmt2->fetchColumn();
        }  
        foreach($value->listTrack as $track){
            $artist = $track->artist;          
            $stmt = $bdd->query('SELECT id FROM artists WHERE id = "'.$artist->id.'"');
            if ($stmt->rowCount() == 0) {
                $stmt2 = $bdd->query('INSERT INTO artists(id, name, link, picture, picture_small, picture_medium, picture_big, picture_xl, nb_album, nb_fan, radio, tracklist, type, position) VALUES ("'.$artist->id.'", "'.$artist->name.'","'.$artist->link.'","'.$artist->picture.'","'.$artist->picture_small.'","'.$artist->picture_medium.'","'.$artist->picture_big.'","'.$artist->picture_xl.'",'.$artist->nb_album.','.$artist->nb_fan.',0,"'.$artist->tracklist.'","'.$artist->type.'",'.$artist->position.')');
            }
            $album = $track->album;
            $stmt = $bdd->query('SELECT id FROM albums WHERE id = "'.$album->id.'"');
            if ($stmt->rowCount() == 0) {
                $stmt2 = $bdd->query('INSERT INTO albums(id, title, link, cover, cover_small, cover_medium, cover_big, cover_xl, genre_id, nb_tracks, release_date, record_type, tracklist, explicit_lyrics, artist_id, type) VALUES ("'.$album->id.'", "'.str_replace("\"","",$album->title).'","'.$album->link.'","'.$album->cover.'","'.$album->cover_small.'","'.$album->cover_medium.'","'.$album->cover_big.'","'.$album->cover_xl.'",'.$album->genre_id.','.$album->nb_tracks.',"'.$album->release_date.'","'.$album->record_type.'","'.$album->tracklist.'",0,"'.$artist->id.'","'.$album->type.'")');
            }
            $stmt = $bdd->query('SELECT id FROM tracks WHERE id = "'.$track->id.'"');
            if ($stmt->rowCount() == 0) {
                $stmt2 = $bdd->query('INSERT INTO tracks(id, readable, title, title_short, title_version, link, duration, rank, explicit_lyrics, explicit_content_lyrics, explicit_content_cover, preview, artist_id, album_id, type) VALUES ("'.$track->id.'", '.$track->readable.',"'.str_replace("\"","",$track->title).'","'.str_replace("\"","",$track->title_short).'","'.str_replace("\"","",$track->title_version).'","'.$track->link.'","'.$track->duration.'","'.$track->rank.'",0,'.$track->explicit_content_lyrics.','.$track->explicit_content_cover.',"'.$track->preview.'","'.$track->artist->id.'","'.$album->id.'","'.$track->type.'")');
            }
            $stmt2 = $bdd->query('INSERT INTO party_tracks(party_id, tracks_id) VALUES ('.$partyid.',"'.$track->id.'")');
        }
        echo $partyid;
        $stmt = null;
        $bdd = null;
        $connectionInfo->CloseConnection();
    }
}