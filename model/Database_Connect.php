<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database_Connect
 *
 * @author ead-luis
 */
class Database_Connect {
    
    private $connect;
    private $database;
    private $host;
    private $passwd;
    private $user;
    
    

    function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $this->attrValue();
        
        $database = $this->getDatabase();
        $host = $this->getHost();
        $passwd = $this->getPasswd();
        $user = $this->getUser();
        $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
        
        try {
            $connect = new PDO("mysql:host=$host;dbname=$database", $user, $passwd, $option);

            $this->setConnect($connect);
        } catch (PDOException $exc) {
            echo "<h3 class='page-header' style='margin-top:50px;text-align:center; color:red'><i class='fa fa-fw fa-cogs fa-5x'></i>SISTEMA TEMPORARIAMENTE FORA DO AR</h3>";
        }
            
        
    }
    
    private function attrValue() {
        $this->setDatabase('#');
        $this->setHost('#');
        $this->setPasswd('#');
        $this->setUser('#');
    }
    
    //GET E SET
    public function getConnect() {
        return $this->connect;
    }

    private function getDatabase() {
        return $this->database;
    }

    private function getHost() {
        return $this->host;
    }

    private function getPasswd() {
        return $this->passwd;
    }

    private function getUser() {
        return $this->user;
    }

    private function setConnect($connect) {
        $this->connect = $connect;
    }

    private function setDatabase($database) {
        $this->database = $database;
    }

    private function setHost($host) {
        $this->host = $host;
    }

    private function setPasswd($passwd) {
        $this->passwd = $passwd;
    }

    private function setUser($user) {
        $this->user = $user;
    }
}
