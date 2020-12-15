<?php

include_once("../config.php");
if (isset($_GET['id']) && intval($_GET['id']) && isset($_GET['last']) && intval($_GET['last'])) {
    $q_id = $_GET['id'];
    $q_id = intval($q_id);
    $last_id = $_GET['last'];
    $last_id = intval($last_id);
    $sql = "SELECT SearchResultId, Title, SearchResultRate, WhiteListClass FROM searchresult, WhiteList WHERE SearchId = ? AND SearchResultId > ? AND searchresult.WhiteListId = WhiteList.WhiteListId";
    $sth = $conn->prepare($sql);
    $sth->execute(array($q_id, $last_id));
    $result = $sth->fetchAll();
    echo json_encode($result);
} else if (isset($_GET['id']) && intval($_GET['id'])) {
    $q_id = $_GET['id'];
    $q_id = intval($q_id);
    $sql = "SELECT SearchResultId, Title, SearchResultRate, WhiteListClass FROM searchresult, WhiteList WHERE SearchId = ? AND searchresult.WhiteListId = WhiteList.WhiteListId";
    $sth = $conn->prepare($sql);
    $sth->execute(array($q_id));
    $result = $sth->fetchAll();
    echo json_encode($result);
}