<?php
global $token;
    $url = 'https://oauth.vk.com/token?grant_type=password&scope=video,offline&client_id=2274003&client_secret=hHbZxrka2uZ6jB1inYsH&username=' .$_REQUEST['user']. '&password=' .$_REQUEST['password']. '&expires_in=0';
    $res = file_get_contents($url);
    $token = json_decode($res, true);

function api($method, $params = array(), $token1)
{
    $params['access_token'] = $token1;
    
    $url = 'https://api.vk.com/method/' . $method . '?' . http_build_query($params);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$api = api('video.get', array('videos' => $_REQUEST['u']), $token['access_token']);

$clip_url = $api['response'][1]['files']['mp4_720'];;
echo file_get_contents(urlencode($clip_url));
?>
