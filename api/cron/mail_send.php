<?php

include_once(__DIR__ . "/../../config.php");

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/../../vendor/autoload.php';

function getPositiveResultSentence(string $r_id) {
    global $conn;
    $sql = "SELECT `sentences` FROM `sentence` WHERE `search_result_id` = ? AND `sentence_grade` > 0.0 ORDER BY `sentence_grade` DESC";
    $sth = $conn->prepare($sql);
    $sth->execute(array($r_id));
    $result = $sth->fetchAll();
    return $result[0]['sentences'];
}

function getPositiveResult(string $r_id, string $title) {
    global $HOST;
    $url = $HOST . '/article.php?id=' . $r_id;
    $title = mb_strimwidth($title, 0, 80, "...");
    $sentence = getPositiveResultSentence($r_id);
    $sentence = mb_strimwidth($sentence, 0, 160, "...");
    $ret = <<<EOD
    <tr>
        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
            <div style="font-family:Helvetica Neue;font-size:20px;line-height:1;text-align:left;color:#626262;"><a href="{$url}">{$title}</a></div>
        </td>
    </tr>
    <tr>
        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
            <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:16px;line-height:20px;text-align:left;color:#525252;">
                $sentence
            </div>
        </td>
    </tr>
    EOD;
    return $ret;
}

function getPositiveContent(string $id) {
    global $conn;
    $sql = "SELECT `SearchResultId`, `SearchResultRate`, `Title` FROM `searchresult` WHERE `SearchId` = ? AND `SearchResultRate` < 0.0 ORDER BY `SearchResultRate` DESC";
    $sth = $conn->prepare($sql);
    $sth->execute(array($id));
    $result = $sth->fetchAll();
    // print_r($result);
    if (count($result) == 0) {
        $ret = <<<EOD
        <tr>
            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                <div style="font-family:Helvetica Neue;font-size:24px;line-height:1;text-align:center;color:#626262;">無相關結果</div>
            </td>
        </tr>
        EOD;
        return $ret;
    }
    else {
        $ret = "";
        for ($i = 0; $i < 3 && $i < count($result); $i++) {
            $ret .= getPositiveResult($result[$i]['SearchResultId'], $result[$i]['Title']);
        }
        return $ret;
    }
}

function getNegativeResultSentence(string $r_id)
{
    global $conn;
    $sql = "SELECT `sentences` FROM `sentence` WHERE `search_result_id` = ? AND `sentence_grade` < 0.0 ORDER BY `sentence_grade` ASC";
    $sth = $conn->prepare($sql);
    $sth->execute(array($r_id));
    $result = $sth->fetchAll();
    return $result[0]['sentences'];
}

function getNegativeResult(string $r_id, string $title)
{
    global $HOST;
    $url = $HOST . '/article.php?id=' . $r_id;
    $title = mb_strimwidth($title, 0, 80, "...");
    $sentence = getNegativeResultSentence($r_id);
    $sentence = mb_strimwidth($sentence, 0, 160, "...");
    $ret = <<<EOD
    <tr>
        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
            <div style="font-family:Helvetica Neue;font-size:20px;line-height:1;text-align:left;color:#626262;"><a href="{$url}">{$title}</a></div>
        </td>
    </tr>
    <tr>
        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
            <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:16px;line-height:20px;text-align:left;color:#525252;">
                $sentence
            </div>
        </td>
    </tr>
    EOD;
    return $ret;
}

function getNegativeContent(string $id)
{
    global $conn;
    $sql = "SELECT `SearchResultId`, `SearchResultRate`, `Title` FROM `searchresult` WHERE `SearchId` = ? AND `SearchResultRate` < 0.0 ORDER BY `SearchResultRate` ASC";
    $sth = $conn->prepare($sql);
    $sth->execute(array($id));
    $result = $sth->fetchAll();
    // print_r($result);
    if (count($result) == 0) {
        $ret = <<<EOD
        <tr>
            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                <div style="font-family:Helvetica Neue;font-size:24px;line-height:1;text-align:center;color:#626262;">無相關結果</div>
            </td>
        </tr>
        EOD;
        return $ret;
    } else {
        $ret = "";
        for ($i = 0; $i < 3 && $i < count($result); $i++) {
            $ret .= getNegativeResult($result[$i]['SearchResultId'], $result[$i]['Title']);
        }
        return $ret;
    }
}

function getMailContent(string $id, string $query) {
    global $HOST;
    $positiveContent = getPositiveContent($id);
    $negativeContent = getNegativeContent($id);
    $url = $HOST . "/search.php?id=" . $id;
    $result = <<<EOD
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <title> </title>
  <!--[if !mso]><!-- -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
    #outlook a {
      padding: 0;
    }

    body {
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table,
    td {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    p {
      display: block;
      margin: 13px 0;
    }
  </style>
  <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
  <!--[if lte mso 11]>
        <style type="text/css">
          .mj-outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
  <!--[if !mso]><!-->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
  <style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
  </style>
  <!--<![endif]-->
  <style type="text/css">
    @media only screen and (min-width:480px) {
      .mj-column-per-100 {
        width: 100% !important;
        max-width: 100%;
      }
      .mj-column-per-50 {
        width: 50% !important;
        max-width: 50%;
      }
    }
  </style>
  <style type="text/css">
  </style>
</head>

<body>
  <div style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;"> 親愛的使用者您好：感謝您使用本系統查詢「{$query}」，以下內容為本次查詢結果 </div>
  <div style="">
    <!-- Company Header -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#f0f0f0;background-color:#f0f0f0;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f0f0f0;background-color:#f0f0f0;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:24px;line-height:1;text-align:left;color:#626262;">網路上正反意向健康保健資訊推薦系統</div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Introduction Text -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#fafafa;background-color:#fafafa;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fafafa;background-color:#fafafa;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Helvetica Neue;font-size:22px;line-height:40px;text-align:left;color:#000000;">親愛的使用者您好：感謝您使用本系統查詢「{$query}」，以下內容為本次查詢結果</div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- 2 columns section -->
    <!-- Side image -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:white;background-color:white;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:white;background-color:white;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      <![endif]-->
              <!-- Left image -->
              <!--[if mso | IE]>
            <td
               class="" style="vertical-align:top;width:300px;"
            >
          <![endif]-->
              <div class="mj-column-per-50 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Helvetica Neue;font-size:30px;line-height:1;text-align:center;color:#2185d0;">正向文章</div>
                    </td>
                  </tr>
                  {$positiveContent}
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          <![endif]-->
              <!-- right paragraph -->
              <!--[if mso | IE]>
            <td
               class="" style="vertical-align:top;width:300px;"
            >
          <![endif]-->
              <div class="mj-column-per-50 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Helvetica Neue;font-size:30px;line-height:1;text-align:center;color:#f2711c;">反向文章</div>
                    </td>
                  </tr>
                  {$negativeContent}
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Icons -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#fbfbfb;background-color:#fbfbfb;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fbfbfb;background-color:#fbfbfb;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:20px;line-height:1;text-align:center;color:#000000;">想看更多結果嗎？點擊這裡導向我們的網站</div>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                        <tr>
                          <td align="center" bgcolor="#F63A4D" role="presentation" style="border:none;border-radius:3px;cursor:auto;mso-padding-alt:10px 25px;background:#F63A4D;" valign="middle"> <a href="{$url}" style="display:inline-block;background:#F63A4D;color:#ffffff;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:24px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:3px;"
                              target="_blank">
              點我觀看更多結果
            </a> </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Social icons -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#f0f0f0;background-color:#f0f0f0;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f0f0f0;background-color:#f0f0f0;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:16px;line-height:1;text-align:center;color:#525252;">© 2021 網路上正反意向健康保健資訊之推薦小組</div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
  </div>
</body>

</html>
EOD;
    // echo $result;
    return $result;
}

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
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "您的搜尋結果：「" . $query . "」已完成！";
        $mail->Body    = getMailContent($id, $query);
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