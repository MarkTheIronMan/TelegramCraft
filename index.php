<?php
  include('vendor/autoload.php'); 
  use Telegram\Bot\Api; 
  use Telegram\Bot\Keyboard\Keyboard;

  const RESP = "Введите название города";
  const apikey = '2ef74c382ac90947f76e48a4cb24fca2';

 
  $telegram = new Api('698149481:AAFNPsmhDJ2a_dzbVFjiiGCc3TgpGft-0Xk');
  $result = $telegram -> getWebhookUpdates(); 
  $text = $result["message"]["text"];
  $chat_id = $result["message"]["chat"]["id"]; 
  $name = $result["message"]["from"]["username"]; 
  $wasStart = FALSE;
  $city_name = 'moscow';
  $url = 'https://api.openweathermap.org/data/2.5/weather?q='.$city_name.'&appid='.apikey.'';
  $mark = FALSE;
 

   function getInfo($arg): string {
   	   $newurl = 'https://api.openweathermap.org/data/2.5/weather?q='.$arg.'&appid='.apikey.'';
       $ch = curl_init($newurl);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $r = curl_exec($ch);
       curl_close($ch);
       $request = json_decode($r, true);
       $name = $request["name"];
       $temp = $request["main"]["temp"];
       $tempfloat = 0 + $temp;
       $tempfloat = round($tempfloat - 273.15); 
       $wind = $request["wind"]["speed"];
       $str = 'Температура в '.$name.' составляет '.$tempfloat.' градусов. Скорость ветра '.$wind.' метров в секунду.';
       return $str;
    }

  if (($text == "/start") and ($wasStart == false)) {
  	$wasStart = TRUE;
  	if ($name) {
      $reply = "Здравствуй, " .$name;
    }
    else {
      $reply = "Здравствуй, Cтранник";
    }
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
  }

  if ($text == "/sayHello") {
  	if ($name) {
      $reply = "Здравствуй, " .$name;
    }
    else {
      $reply = "Здравствуй, Cтранник";
    }
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
  }
  

  if ($text == "/info") {
  	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $r = curl_exec($ch);
    curl_close($ch);
    $request = json_decode($r, true);
    $reply = $request["main"]["temp"];
    $response = "temperature in Moscow is about ". $reply. 'K';
    echo($reply);
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $response/*, 'reply_markup' => $reply_markup*/ ]);
  } 

  if ($text == "/nw") {
  	$reply = RESP;
  	$mark = TRUE;
  	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
  }
/*  if (($text != "/nw") and ($mark)) {
   	  $city = $text;   
  	  $va = getInfo($city);
  	  $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $va ]);
  	  $mark = FALSE;	
  }  */



  if ($text == "/погода") {
  	 $reply = "Окей, я могу рассказать тебе про погоду";
  	 $keyboard = [["Увидеть последние запросы"],["Новый город"]];
  	 $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
     $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
     if ("$text" == "Увидеть последние запросы") {
     	$telegram->sendMessage([ 'chat_id' => $chat_id, "Как скажешь, мой повелитель"]);
     }
     elseif ("$text" == "Новый город") {
     	$isNewSearch = TRUE;
     	$telegram->sendMessage([ 'chat_id' => $chat_id, "Введите название города"]);
     }
     if (($isNewSearch) and ($text)) {
     	$isNewSearch = false;
        $telegram->sendMassage(['chat_id' => $chat_id, 'text' => getInfo($text)]);
     }
  }

  
  echo("test");
?>
