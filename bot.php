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
			// $messages = [
			// 	'type' => 'text',
			// 	'text' =>  getMassage($text,$event['source']['userId'])
			// ];

			$messages = [
				"type"=> "template",
  				"altText"=> "this is a buttons template",
  				"template"=> [
					"type"=> "buttons",
					"thumbnailImageUrl"=> "https://www.w3schools.com/css/img_fjords.jpg",
					"title"=> "Menu",
					"text"=> "Please select",
					"type"=> "buttons",
					"thumbnailImageUrl"=> "https://www.w3schools.com/css/img_fjords.jpg",
					"title"=> "Menu",
					"text"=> "Please select",
					"actions"=> [
						[
							"type"=> "postback",
							"label"=> "Buy",
							"data"=> "action=buy&itemid=123"
						]
						,
						[
							"type"=> "postback",
							"label"=> "Add to cart",
							"data"=> "action=add&itemid=123"
						]
						,
						[
							"type"=> "uri",
							"label"=> "View detail",
							"uri"=> "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRLwfosW6PfuMKaPnabKzj1UzAfOQpxZzqVvkFzEonna0iSUgyVWg"
						]
					]
			  	]
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



function getMassage($text,$uid)
{
	$file = file_get_contents('text.json');
	$data = json_decode($file, true);
	unset($file);
	return checkUID_line($text,$uid);
	//prevent memory leaks for large json.
	if (isset($data[$text])) {
		return $data[$text];
	}else{
		$data[$text] = '';
		//save the file
		file_put_contents('text.json',json_encode($data));
		//release memory
		unset($data);
		return $text;
	}
}

function checkUID_line($text,$uid){
	$servername = "ap-cdbr-azure-southeast-b.cloudapp.net";
	$username = "bc4dcc5c7e5a47";
	$password = "7de74729";
	$dbname = "chatbot_db";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
	    return mysqli_connect_error();
	}
	$sql 	= "SELECT * FROM users WHERE uid_line='".$uid."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row["status"] == 0) {
		        if ($row["id_card"] == null) {
					$sql 	= "UPDATE users SET id_card='".$text."' WHERE uid_line='".$uid."'";
					$result = $conn->query($sql);
					return "วาชาบิ อยากทราบชื่อ ตัวเองจัง";
				}else if($row["name"] == null){
					$sql 	= "UPDATE users SET name='".$text."', status=1 WHERE uid_line='".$uid."'";
					$result = $conn->query($sql);
					return "สวัส ดีค่ะ คุณ   ".$text;
				}
			}else if($row["status"] == 1){
				return 'ข้อมูลครบ';
			}
		}
	}else{
		// $sql = " * FROM users WHERE uid_line='".$uid."'";
		$sql 	= "INSERT INTO users (uid_line) VALUES ('".$uid."')";
		$result = $conn->query($sql);
		return "หมายเลขบัตรประชาชนหมายเลขอะไร ค่ะ";
	}
	mysqli_close($conn);
}

echo "OK";