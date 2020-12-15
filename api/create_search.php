<?php

include_once("../config.php");

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if (isset($_GET['q']) && $_GET['q'] != "") {
    $query = $_GET['q'];

    $connection = new AMQPStreamConnection($MQServer, $MQPort, $MQUsername, $MQPassword);
    $channel = $connection->channel();

    $channel->queue_declare($MQQueue, false, true, false, false);

    $sql = "INSERT INTO `search`(`SearchString`) VALUES (?)";
    $sth = $conn->prepare($sql);
    $sth->execute(array($query));
    $insert_id = $conn->lastInsertId();

    $data = array('searchText' => $query, 'id' => $insert_id);
    $msg = new AMQPMessage(
            json_encode($data),
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

    $channel->basic_publish($msg, '', $MQQueue);

    

    header("Location: /search.php?id=" . $insert_id);
}
