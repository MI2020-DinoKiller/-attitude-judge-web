<?php

include_once("../config.php");

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPRuntimeException;

// if (isset($_GET['q']) && $_GET['q'] != "" && isset($_GET['q']) && $_GET['q'] != "") {
//     $query = $_GET['q'];

//     $connection = new AMQPStreamConnection($MQServer, $MQPort, $MQUsername, $MQPassword);
//     $channel = $connection->channel();
//     $channel->queue_declare($MQQueue, false, true, false, false);

//     $sql = "INSERT INTO `search`(`SearchString`, `email`) VALUES (?, ?)";
//     $sth = $conn->prepare($sql);

//     $sth->execute(array($query));
//     $insert_id = $conn->lastInsertId();

//     $data = array('searchText' => $query, 'id' => $insert_id);
//     $msg = new AMQPMessage(
//             json_encode($data),
//             array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
//         );

//     $channel->basic_publish($msg, '', $MQQueue);
//     header("Location: /search.php?id=" . $insert_id);
// }

try {
    if (!isset($_POST['q'])) {
        throw new Exception("搜尋欄位必須有文字");
    }
    if (!isset($_POST['e'])) {
        throw new Exception("必須填寫 Email");
    }
    $query = $_POST['q'];
    $email = $_POST['e'];
    $query = trim($query);
    $email = trim($email);
    if (!isset($query) || $query == "") {
        throw new Exception("搜尋欄位必須有文字");
    }
    else if (!isset($email) || $email == "") {
        throw new Exception("必須填寫 Email");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email 為無效格式");
    }

    $sql = "SELECT `SearchId` FROM `search` WHERE `SearchString` = ? AND `email` = ? AND `hasFinish` = FALSE";
    $sth = $conn->prepare($sql);
    $sth->execute(array($query, $email));
    $result = $sth->fetchAll();
    if (count($result) >= 1) {
        throw new Exception("您的相同查詢句正在處理中！");
    }

    $connection = new AMQPStreamConnection($MQServer, $MQPort, $MQUsername, $MQPassword);
    $channel = $connection->channel();
    $channel->queue_declare($MQQueue, false, true, false, false);

    $sql = "INSERT INTO `search`(`SearchString`, `email`) VALUES (?, ?)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($query, $email));
    $insert_id = $conn->lastInsertId();
    $data = array('searchText' => $query, 'id' => $insert_id);
    $msg = new AMQPMessage(
        json_encode($data),
        array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
    );
    $channel->basic_publish($msg, '', $MQQueue);
    $ret = array('ok' => 1, 'msg' => "");
    echo json_encode($ret);
} catch (AMQPRuntimeException $e) {
    $ret = array('ok' => 0, 'msg' => "有些東西出錯了！請跟開發者聯絡 QAQ！");
    echo json_encode($ret);
} catch (Exception $e) {
    $ret = array('ok' => 0, 'msg' => $e->getMessage());
    echo json_encode($ret);
}
