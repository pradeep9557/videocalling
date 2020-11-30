<?php
include(__DIR__."/src/RtcTokenBuilder.php");
include(__DIR__."/defines.php");

function acquire($uid, $channel) {
    global $appId;
    $ch = curl_init();
    $url = "https://api.agora.io/v1/apps/6df2aa6a22cd4b79a604ad5cd503960e/cloud_recording/acquire";
    $json = json_encode(array(
        "cname" => $channel,
        "uid" =>  $uid,
        "clientRequest" => array(
            "resourceExpiredHour" => 24
    )
    ));
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    if ( $httpcode >= 200 && $httpcode <= 299 ) {
        return json_decode($server_output, TRUE);
    }
    return FALSE;

}

function start($uid, $channelName, $resourceId) {
    global $appId;
    $ch = curl_init();
    $userId = "1";
    $file = uniqid(TRUE);

    $mode = "composite";
    $url = "https://api.agora.io/v1/apps/$appId/cloud_recording/resourceid/$resourceId/mode/$mode/start";

    $array = array(
        "cname" => $channelName,
        "uid" => "",
        "clientRequest" => array(
            "token" => "<token if any>",
            "recordingConfig" => array(
                "maxIdleTime" => 30,
                "streamTypes" => 2,
                "channelType" => 0,
                "videoStreamType" => 0,
                "transcodingConfig" => array(
                    "height" => 640,
                    "width" => 360,
                    "bitrate" => 500,
                    "fps" => 15,
                    "mixedVideoLayout" => 1,
                    "backgroundColor" => "#FF0000"
                ),
                "subscribeVideoUids" => array(
                    "123",
                    "456"
                ),
                "subscribeAudioUids": array(
                    "123",
                    "456"
                ),
                "subscribeUidGroup" => 0
            ),
            "recordingFileConfig" => array(
                "avFileType" => array(
                    "hls"
                )
            ),
            "storageConfig" => array(
            "accessKey" => "AKIARPXPWYSMSLBZB4ZT",
            "region" => 3,
            "bucket" => "nov23-2020-bucket",
            "secretKey" => "pxTGdiq2XCir1BWose8BoJEZnzaQ5rNhKawF3pVv",
            "vendor" => 2,
            "fileNamePrefix" => array(
                $userId,
                $file
            )
        )
    )
);
    $json = json_encode($array);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    if ( $httpcode >= 200 && $httpcode <= 299 ) {
        return json_decode($server_output, TRUE);
    }
    return FALSE;

}

$channel = $_REQUEST['channel'];
$uid = $_REQUEST['uid'];
$result = acquire($uid, $channel);
if (!$result) {
    die(json_encode(array("success" => FALSE, "msg" => "Failed to acquire..")));
}
start($uid, $channel, $result['resourceId']);
if (!$result) {
    die(json_encode(array("success" => FALSE, "msg" => "Failed to start.")));
}
echo json_encode(array(
    "sid" => $result['sid'],
    "resourceId" => $result['resourceId'],
));