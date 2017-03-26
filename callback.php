<?php
  require("pub.php");
 
  $channelId = "1489340478";
  $channelSecret = "485c3c6490bd5068e6ac2dc015c868be"; 
  $mid = "U22927034d0fecb3684486fc9a042765a"; 
 
   
  $requestBodyString = file_get_contents('php://input');
  $requestBodyObject = json_decode($requestBodyString);
  $requestContent = $requestBodyObject->result{0}->content;
  $requestText = $requestContent->text; 
  $requestFrom = $requestContent->from; 
  $contentType = $requestContent->contentType; 
 
  getMqttfromlineMsg($requestText);
   
  $headers = array(
    "Content-Type: application/json; charset=UTF-8",
    "X-Line-ChannelID: {$channelId}", // Channel ID
    "X-Line-ChannelSecret: {$channelSecret}", // Channel Secret
    "X-Line-Trusted-User-With-ACL: {$mid}", // MID
  );
 
   
  $responseText = <<< EOM
「{$requestText}」 this is msg echo from Line Bot API。http://binahead.com
EOM;
 
   
  $responseMessage = <<< EOM
    {
      "to":["{$requestFrom}"],
      "toChannel":1383378250,
      "eventType":"138311608800106203",
      "content":{
        "contentType":1,
        "toType":1,
        "text":"{$responseText}"
      }
    }
EOM;
   
   
  
  $curl = curl_init('https://api.line.me/v2/bot/message/reply');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $responseMessage);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
  curl_setopt($curl, CURLOPT_PROXY, getenv('FIXIE_URL'));
  $output = curl_exec($curl);
 
?>
