<?php
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
$path = $api['response'][1]['files']['mp4_720'];

function curl_get_file_size( $url ) {
  $result = -1;
  $curl = curl_init( $url );

  curl_setopt( $curl, CURLOPT_NOBODY, true );
  curl_setopt( $curl, CURLOPT_HEADER, true );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

  $data = curl_exec( $curl );
  curl_close( $curl );

  if( $data ) {
    $content_length = "unknown";
    $status = "unknown";

    if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
      $status = (int)$matches[1];
    }

    if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
      $content_length = (int)$matches[1];
    }

    if( $status == 200 || ($status > 300 && $status <= 308) ) {
      $result = $content_length;
    }
  }

  return $result;
}

header("Content-type: video/mpeg");
header("Content-Length: ".curl_get_file_size($path));
header("Expires: -1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

readfile($path);
?>
