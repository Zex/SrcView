<?php
/*
 * srcviewsimple.php
 *
 * Simple version of source view without the Zend Framework
 *
 * Author: Zex <top_zlynch@yahoo.com>
 */

    $filename = "message.cpp";

    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=project-x.doc");
    
    echo "<html>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
    
    echo "<style>";
    echo "body {";
    echo "    font-size: 0.5em;";
    echo "    font-family: verdana;";
    echo "    margin-left: 10; ";
    echo "    margin-right: 10; ";
    echo "    width: 80%;";
    echo "}";
    echo "</style>";
    
    echo "<body>";
    
    $buf = file($filename);
    
    foreach ($buf as $nr => $line ) {
        echo $line,"<br>";
    }
    
    echo "</body>";
    echo "</html>";
?>
