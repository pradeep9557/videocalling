<!DOCTYPE html>
<html>

<head>
    <title> Agora.io Web Quickstart </title>
    <link href="css/app.css" rel="stylesheet" type="text/css">

    <script src="js/AgoraRTCSDK-2.1.0.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</head>

<body>

    <div id="videos">
        <div class="video" id="remote-video"></div>
        <div class="video" id="local-video"></div>
        <div id="toolbar" class=flex-container>
            <div class ="image-container">
                <input id="video-mute-button" class="circle-button" type="image" src="images/cameraoff.png" onClick="onVideoMuteButtonClicked()">
            </div>
            <div class ="image-container">
                <input id="mute-button" class="circle-button" type="image" src="images/mute.png" onClick="onMuteButtonClicked()">
            </div>
            <div class ="image-container">
                <input id="endcall-button" class="circle-button" type="image" src="images/hangup.png" onclick="onEndCallButtonClicked()">
            </div>
        </div>
    </div>
</body>

</html>
