<?php

// fidion GmbH mainChat
// $Id: functions-registerglobals.php,v 1.3 2012/10/17 06:16:53 student Exp $

if (ini_get('register_globals') == 0) {
    
    // Initialisierung der Variablen
    $frame = 0;
    $aktion = "";
    $los = "";
    
    // Lade alle Variablen in Lokale Variablen
    foreach ($_GET as $varname => $value) {
        global $$varname;
        $$varname = $value;
    }
    
    foreach ($_POST as $varname => $value) {
        global $$varname;
        $$varname = $value;
    }
    
    // für home.php argc & argv
    foreach ($_SERVER as $varname => $value) {
        global $$varname;
        $$varname = $value;
    }
    
    //    if (isset($_POST["http_host"])) $http_host = $_POST["http_host"];
    //    else if (isset($_GET["http_host"])) $http_host = $_GET["http_host"];
    //    if (isset($_POST["id"])) $id = $_POST["id"];
    //    else if (isset($_GET["id"])) $id = $_GET["id"];
    //    if (isset($_POST["frame"])) $frame = $_POST["frame"];
    //    else if (isset($_GET["frame"])) $frame = $_GET["frame"];
    //    if (isset($_POST["aktion"])) $aktion = $_POST["aktion"];
    //    else if (isset($_GET["aktion"])) $aktion = $_GET["aktion"];
    
}

if (($frame <> 1) && ($frame <> 0)) {
    $frame = 0;
}

?>
