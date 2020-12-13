<?php

include_once("../config.php");
if (isset($_GET['q']) && $_GET['q'] != "") {
    $query = $_GET['q'];
    $cmd = implode(" ", array($PYTHON_PATH, $GOOGLE_SEARCH_APP_PATH, escapeshellarg($query)));
    shell_exec($cmd);
}
