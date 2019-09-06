<?php

class ConnectionInfo{

    private $serverName;
    private $dbName;
    private $login;
    private $password;
    private $bdd;
    public function GetConnection(){
        $this->serverName = "localhost";
        $this->dbName = "guessmyzik";
        $this->login = "root";
        $this->password = "";
        $this->bdd = new PDO("mysql:host=".$this->serverName.";dbname=".$this->dbName.";charset=utf8", $this->login, $this->password);
        $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->bdd;
    }

    public function CloseConnection(){
        $this->bdd = null;
    }
}