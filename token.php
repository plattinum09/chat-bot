<?php
$access_token = 'sHz/dZyhpuQncaEOWgeIG8yremjGmquQytEq45zTcd+m+nnZKbB0lmWzl7s6w7X0lWM0QOxVve43PRgPikPOBSUwm0jvuDrg32YbsEyhrbz4+5EDfo1Gv361klxz6ucTco+3r8DfejKsZTOnsVhxhwdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;