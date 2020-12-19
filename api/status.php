<?php

include_once("../config.php");

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPRuntimeException;

try {
    $connection = new AMQPStreamConnection($MQServer, $MQPort, $MQUsername, $MQPassword);
    $channel = $connection->channel();
    list($queue, $searchMessageCount, $consumerCount) = $channel->queue_declare($MQQueueSearch, false, true, false, false);
    list($queue, $taskMessageCount, $consumerCount) = $channel->queue_declare($MQQueueTask, false, true, false, false);
    $ret = array('ok' => 1, 'msg' => "",
    'search_queue' => $searchMessageCount, 'task_queue' => $taskMessageCount);
    echo json_encode($ret);
} catch (AMQPRuntimeException $e) {
    $ret = array('ok' => 0, 'msg' => "有些東西出錯了！請跟開發者聯絡 QAQ！");
    echo json_encode($ret);
} catch (Exception $e) {
    $ret = array('ok' => 0, 'msg' => $e->getMessage());
    echo json_encode($ret);
}
