<?php
include(__DIR__."/src/RtcTokenBuilder.php");
include(__DIR__."/defines.php");

$channelName = "7d72365eb983485397e3e3f9d460bddac";
$uid = 2882341274;
$uidStr = "2882341274";
$role = RtcTokenBuilder::RoleAttendee;
$expireTimeInSeconds = 3600;
$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

$tokenUid = RtcTokenBuilder::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
//echo 'Token with int uid: ' . $token . PHP_EOL;

$tokenUser = RtcTokenBuilder::buildTokenWithUserAccount($appId, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
//echo 'Token with user account: ' . $token . PHP_EOL;
//$appId = env('APP_ID');
echo json_encode(array(
    "app_id" => $appId,
    "uid" => $uid,
    "channel" => $channelName,
    "token" => $tokenUid
));