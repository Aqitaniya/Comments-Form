<?php

class ModelCommentClass
{
    private $defaultData = array();
    private $commentData = array();

    function __construct($defaultData)
    {
        $this->defaultData = $defaultData;

        if($this->mysql_connect())
        if($this->bd_create())
        $this->table_create();
    }
    private function mysql_connect($db = ""){
        $this->mysqli = new mysqli($this->defaultData['host'], $this->defaultData['user'], $this->defaultData['pass'], $db);
        if ($this->mysqli->connect_errno && $db == "" ) {
            $error_str = "Could not connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
            $this->write_errors($error_str);
            return false;
        }
        else if ($this->mysqli->connect_errno && $db != "" ) {
            $error_str = "Could not connect to database '".$db."': (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
            $this->write_errors($error_str);
            return false;
        }
        else{
            return true;
        }
    }
    private function bd_create(){
        $this->mysqli->real_query('CREATE DATABASE IF NOT EXISTS `'.$this->defaultData['db'].'`');
        if ($this-> mysql_connect($this->defaultData['db'])) {
            return true;
        }else{
            return false;
        }
    }
    private function table_create(){
        if (!$this->mysqli->query("CREATE TABLE IF NOT EXISTS ".$this->defaultData['table']."(
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `email` VARCHAR( 50 ) NOT NULL,
        `fullname` VARCHAR( 50 ) NOT NULL,
        `phone` VARCHAR( 15 ) NOT NULL,
        `namefile` VARCHAR( 50 ) DEFAULT '',
        `comment` VARCHAR( 300 ) NOT NULL,
        `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)")) {
            $error_str = "Could not create a table '".$this->$defaultData['table']."': (" . $this->mysqli->errno . ") " . $this->mysqli->error;
            $this->write_errors($error_str);
        }
    }
    function insert_records($commentData){

        $this->commentData=$commentData;

        $sql = "INSERT INTO " . $this->defaultData['table'] . "(`email`, `fullname`, `phone`, `namefile`, `comment`) VALUES ('" . $this->commentData['email'] . "','".$this->commentData['fullname']."','".$this->commentData['phone']."','".$this->commentData['filename']."','".$this->commentData['comment']."');";
        if (!$this->mysqli->query($sql)){
            $error_str= "No data can be stored in the table'".$this->defaultData['table']."': (" . $this->mysqli->errno . ") " . $this->mysqli->error;
            $this->write_errors($error_str);
            return false;
        }
        else
            return $this->mysqli->insert_id;
    }
    function get_comments(){
         $array_comments = $this->mysqli->query("SELECT * FROM ".$this->defaultData['table']);
         return  $array_comments;
    }
    function get_last_comment($id){
         $array_comments = $this->mysqli->query("SELECT * FROM ".$this->defaultData['table']." WHERE id=".$id);
         return  $array_comments;
    }
    private function write_errors($str){
         $fp = fopen('errors.txt', 'a+');
         fwrite($fp, $str."\r\n");
         fclose($fp);
    }
    function __destruct(){
        $this->mysqli->close;
    }

}
?>