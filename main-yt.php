<?php
header('Content-Type: application/json');

$url = $_POST['url'];
$id = '';

if (strpos($url,'youtube.com/watch?') !== false) {
	$query_str = parse_url($url, PHP_URL_QUERY);
	parse_str($query_str, $query_params);
	$id =$query_params['v'];
}
else {
    $getid= explode('/',$url);
	$id = $getid[count($getid) - 1];
}


parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id='.$id), $video_data);
$streams = $video_data['url_encoded_fmt_stream_map'];
$streams = explode(',',$streams);
$counter = 1;

$videolist = array();
foreach ($streams as $streamdata) {
	parse_str($streamdata,$streamdata);
	$mediatype = explode(';',$streamdata['type']);
	$finaltype = explode('/', $mediatype[0]);
	$quality = $counter . '-' . $streamdata['quality'] . ' ' . $finaltype[1];
	$url = urldecode($streamdata['url']);
	$videolist[$quality] = $url;
	$counter = $counter+1;
}


$message = array();
$message['type'] ='success';
$message['title'] = urldecode($video_data['title']);
$message['thumbnail'] = urldecode($video_data['thumbnail_url']);
$message['videolist'] = json_encode($videolist);





echo json_encode($message,JSON_PRETTY_PRINT);

?>