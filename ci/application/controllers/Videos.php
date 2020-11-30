<?php

function acquire($uid, $channel) {
    //include(__DIR__."/../helpers/RtcTokenBuilder.php");
    include(__DIR__."/../helpers/defines.php");
    $ch = curl_init();
    $url = "https://api.agora.io/v1/apps/$appId/cloud_recording/acquire";
    $json = json_encode(array(
        "cname" => $channel,
        "uid" =>  $uid,
        "clientRequest" => array(
            "resourceExpiredHour" => 24
    	)
    ));
    $base64Credentials = base64_encode("c3f0c9b3ebf54a31ad5f2183c5fe4724:b0da4b16ab9c48fab973a67e64a14dc1");
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Basic '.$base64Credentials));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    if ( $httpcode >= 200 && $httpcode <= 299 ) {
        return json_decode($server_output, TRUE);
    }
    return FALSE;

}

function stop($uid, $channel, $sid,$resourceId) {
    //include(__DIR__."/../helpers/RtcTokenBuilder.php");
    include(__DIR__."/../helpers/defines.php");
    $ch = curl_init();
    $url = "https://api.agora.io/v1/apps/$appId/cloud_recording/resourceid/$resourceId/sid/$sid/mode/mix/query";
    $json = json_encode(array(
        "cname" => $channel,
        "uid" =>  $uid,
        "clientRequest" => (object) array()
    ));
    $base64Credentials = base64_encode("c3f0c9b3ebf54a31ad5f2183c5fe4724:b0da4b16ab9c48fab973a67e64a14dc1");
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 0);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Basic '.$base64Credentials));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    print_r($server_output);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    if ( $httpcode >= 200 && $httpcode <= 299 ) {
        return json_decode($server_output, TRUE);
    }
    return FALSE;

}

function start($uid, $channelName, $resourceId) {
    include(__DIR__."/../helpers/defines.php");
    $ch = curl_init();
    $userId = "1";
    $file = uniqid(TRUE);

    $mode = "mix";
    $url = "https://api.agora.io/v1/apps/$appId/cloud_recording/resourceid/$resourceId/mode/$mode/start";
    //echo $url;
    $array = array(
        "cname" => $channelName,
        "uid" => $uid,
        "clientRequest" => array(
            "recordingConfig" => array(
                "channelType" => 0,
                "streamTypes" => 2,
                "audioProfile"=>1,
                "videoStreamType" => 0,
                "maxIdleTime" => 30,
                "transcodingConfig" => array(
                    "height" => 640,
                    "width" => 360,
                    "bitrate" => 500,
                    "fps" => 15,
                    "mixedVideoLayout" => 1,
                    "maxResolutionUid"=>"1",
                ),
                /*"subscribeVideoUids" => array(
                    "123",
                    "456"
                ),
                "subscribeAudioUids" => array(
                    "123",
                    "456"
                ),
                "subscribeUidGroup" => 0*/
            ),
            /*"recordingFileConfig" => array(
                "avFileType" => array(
                    "hls"
                )
            ),*/
            "storageConfig" => array(
            "accessKey" => "AKIAIJRUH3FGZM6UW6RQ",
            "region" => 1,
            "bucket" => "nov29-2020-bucket",
            "secretKey" => "rQDBauC03kztgcaqgwbhUZlr1UZuyTpaGExIQhUu",
            "vendor" => 1,
            "fileNamePrefix" => array(
                $userId,
                $file
            )
        )
    )
);
$base64Credentials = base64_encode("c3f0c9b3ebf54a31ad5f2183c5fe4724:b0da4b16ab9c48fab973a67e64a14dc1");
    $json = json_encode($array);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Basic '.$base64Credentials));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    if ( $httpcode >= 200 && $httpcode <= 299 ) {
        return json_decode($server_output, TRUE);
    }
    return FALSE;

}

/**
 * Class Videos
 * @property Videos_model $news_model The Videos model
 * @property CI_Form_validation $form_validation The form validation lib
 * @property CI_Input $input The input lib
 */
class Videos extends CI_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        
        $data['title'] = 'Videos archive';

        //$this->load->view('templates/header', $data);
        $this->load->view('videos/index', $data);
        //$this->load->view('templates/footer');
    }

    public function view($slug = NULL)
    {
        $data['news_item'] = $this->news_model->get_news($slug);

        if (empty($data['news_item']))
        {
            show_404();
        }

        $data['title'] = $data['news_item']['title'];

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $data['title'] = 'Create a news item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');
        }
        else
        {
            $this->news_model->set_news($this->input->post_get('title', true), $this->input->post_get('text', true));
            $this->load->view('templates/header', $data);
            $this->load->view('news/success');
            $this->load->view('templates/footer');
        }
    }
    public function app_id()
	    {
	include(__DIR__."/../helpers/RtcTokenBuilder.php");
	include(__DIR__."/../helpers/defines.php");

	$channelName = "7d72365eb983485397e3e3f9d460bddac";
	$uid = 2882341273;
	$uidStr = "2882341273";
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
    }


	public function start_recording()
	    {
	include(__DIR__."/../helpers/RtcTokenBuilder.php");
	include(__DIR__."/../helpers/defines.php");
$channel = $_REQUEST['channel'];
$uid = $_REQUEST['uid'];
$result = acquire($uid, $channel);
if (!$result) {
    die(json_encode(array("success" => FALSE, "msg" => "Failed to acquire..")));
}
$result = start($uid, $channel, $result['resourceId']);
if (!$result) {
    die(json_encode(array("success" => FALSE, "msg" => "Failed to start.")));
}
echo json_encode(array(
    "sid" => $result['sid'],
    "resourceId" => $result['resourceId'],
));
    }

    public function stop_recording()
	{
        include(__DIR__."/../helpers/RtcTokenBuilder.php");
        include(__DIR__."/../helpers/defines.php");
        $channel = $_REQUEST['channel'];
        $uid = $_REQUEST['uid'];
        $sid = $_REQUEST['sid'];
        $resourceId = $_REQUEST['resourceId'];
        $result = stop($uid, $channel, $sid,$resourceId);
        
        if (!$result) {
            die(json_encode(array("success" => FALSE, "msg" => "Failed to start.")));
        }
        print_r($result);
    }


}
