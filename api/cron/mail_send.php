<?php

include_once(__DIR__ . "/../../config.php");

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../vendor/autoload.php';

function SendMail(string $id, string $receiver_email, string $query) {
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    global $HOST;
    global $MAILHost, $MAILUsername, $MAILPassword;
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $MAILHost;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $MAILUsername;                     // SMTP username
        $mail->Password   = $MAILPassword;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($MAILUsername, '網路上正反意向健康保健資訊之推薦小組');
        $mail->addAddress($receiver_email);     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "您的搜尋結果：「" . $query . "」已完成！";
        $mail->Body    = "<a href=\"${HOST}/search.php?id=${id}\">${HOST}/search.php?id=${id}</a>";
        $mail->AltBody = "${HOST}/search.php?id=${id}";

        $mail->send();
        echo 'Message has been sent';
        searchIdLock($id);
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function searchIdLock(string $id) {
    global $conn;
    $sql = "UPDATE `search` SET `hasSendMail` = TRUE WHERE `search`.`SearchId` = ?";
    $sth = $conn->prepare($sql);
    $sth->execute(array($id));
    $result = $sth->rowCount();
    if ($result == '0') {
        echo "lock failed";
    }
    else {
        echo "lock Success";
    }
}
function getDatabase()
{
    global $conn;
    $sql = "SELECT * FROM `search` WHERE hasFinish = TRUE AND hasSendMail = FALSE";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();
    foreach ($result as $element) {
        SendMail($element["SearchId"], $element["email"], $element["SearchString"]);
    }
}

getDatabase();