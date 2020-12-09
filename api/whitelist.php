<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
include_once("../config.php");

function to_FUI_json($arr)
{
    $len = count($arr);
    $ret = array();
    for ($i = 0; $i < $len; $i++) {
        $w = array('name' => $arr[$i], 'value' => $i + 1, 'text' => $arr[$i]);
        array_push($ret, $w);
    }
    return $ret;
}

function whitelist_to_FUI_json($arr)
{
    $len = count($arr);
    $ret = array();
    for ($i = 0; $i < $len; $i++) {
        $w = array('name' => $arr[$i]['WhiteListName'], 'value' => $arr[$i]['WhiteListId'], 'text' => $arr[$i]['WhiteListName']);
        array_push($ret, $w);
    }
    return $ret;
}

function default_return()
{
    $arrayName = array("政府機構與研究單位", "醫療機構", "基金會", "大專院校", "新聞媒體", "雜誌");
    $ret = array('success' => true, 'results' => to_FUI_json($arrayName));
    return json_encode($ret);
}

if (isset($_GET['q']) && intval($_GET['q']))
{
    $q = intval($_GET['q']);
    $sql = "SELECT WhiteListId, WhiteListName, WhiteListLink FROM WhiteList WHERE WhiteListClass = ?;";
    $sth = $conn->prepare($sql);
    if ($q >= 1 && $q <= 6) {
        $sth->execute(array($q));
        $result = $sth->fetchAll();
        $ret = array('success' => true, 'results' => whitelist_to_FUI_json($result));
        echo json_encode($ret);
    }
    else {
        echo default_return();
    }
}
else if (isset($_GET['r']) && intval($_GET['r'])) {
    $r = intval($_GET['r']);
    $sql = "SELECT WhiteListLink FROM WhiteList WHERE WhiteListId = ?;";
    $sth = $conn->prepare($sql);
    $sth->execute(array($r));
    $result = $sth->fetchAll();
    $ret = $result[0][0];
    // print_r($ret);
    header("Location: " . $ret);
}
else {
    echo default_return();
}




?>