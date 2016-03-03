<?php
     require_once "controller/ControllerCommentsClass.php";
     require_once "model/ModelCommentsClass.php";

     $defaultData = array(
            "host"            => "localhost",
            "user"            => "root",
            "pass"            => "",
            "db"              => "bd_comments",
            "table"           => "comments",
            "dir"             => "upload",
        );

     $comments = new ControllerCommentsClass($defaultData);
     require_once "view/commentsView.php";
?>