<?php

include_once("../config.php");
if (isset($_GET['id']) && intval($_GET['id']))
{
    $ret = array();  // 結果
    $q_id = intval($_GET['id']);
    $sql = "SELECT SearchResultRate, Link, Title FROM searchresult WHERE SearchResultId = ?";
    $sth = $conn->prepare($sql);  // 查詢文章
    $sth->execute(array($q_id));
    $result = $sth->fetchAll();
    $ret['title'] = $result[0]['Title'];
    $ret['url'] = $result[0]['Link'];
    $ret['score'] = $result[0]['SearchResultRate'];

    $sql_search_sentence = "SELECT sentences, sentence_grade FROM sentence WHERE search_result_id = ? ORDER BY sentence_id";
    $sth2 = $conn->prepare($sql_search_sentence);  // 查詢文章
    $sth2->execute(array($q_id));
    $result = $sth2->fetchAll();
    $ret['sentences'] = $result;
    
    echo json_encode($ret);
}