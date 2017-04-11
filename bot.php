<?php
$access_token = 'sHz/dZyhpuQncaEOWgeIG8yremjGmquQytEq45zTcd+m+nnZKbB0lmWzl7s6w7X0lWM0QOxVve43PRgPikPOBSUwm0jvuDrg32YbsEyhrbz4+5EDfo1Gv361klxz6ucTco+3r8DfejKsZTOnsVhxhwdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => getMassage($text)
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}

function getMassage($text)
{
	// $tempArray = json_decode("text.json", true);
	// array_push($data, array('id' => $row['id']));
	// echo file_put_contents("text.json",$text);
	$massage = '';
	$file = file_get_contents('text.json');
	$data = json_decode($file);
	unset($file);//prevent memory leaks for large json.
	// //insert data here
	// $data[] = array('data'=>'some data');
	// //save the file
	// file_put_contents('data.json',json_encode($data));
	// unset($data);//release memory
	
	// if (empty($data[$text])) {
	// 	$massage = $data[$text];
	// }else{
	// 	$massage = 'none';
	// }
	return $data['a'];
}

echo "OK";