﻿<?php
//$request = file_get_contents('php://input');
//$input = json_decode($request, true);
$input = array('0'=>'28798261');
$res = array();

foreach ($input as $key=>$value) {
	$url = 'http://music.163.com/api/song/detail/?id='.$value.'&ids=['.$value.']&csrf_token=Method=GET';
    
	$json = netease_http($url);
	$array = $json;
	$name = $array['songs']['0']['name'];
	$alias = $array['songs']['0']['alias']['0'];
	$artists = $array['songs']['0']['artists']['0']['name'];
	$artistsId = $array['songs']['0']['artists']['0']['id'];
	$album = $array['songs']['0']['album']['name'];
	$albumId = $array['songs']['0']['album']['id'];
	$mp3Url = getMp3Url($albumId,$array['songs']['0']['id']);
	$mp3Url = str_replace("http://m", "http://p", $mp3Url);
	$picUrl = $array['songs']['0']['album']['picUrl'];
	
	$tmpArray = array(
		'songName'=>$name,
		'artists'=>$artists,
		'artistsId'=>$artistsId,
		'album'=>$album,
		'albumId'=>$albumId,
		'mpsUrl'=>$mp3Url,
		'picUrl'=>$picUrl
	)
	
	array_push($res, $key=>$tmpArray);
}

echo json_encode($res, JSON_UNESCAPED_UNICODE);

function netease_http($url)
{
	$refer = "http://music.163.com/";
	$header[] = "Cookie: appver=1.5.0.75771;";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_REFERER, $refer);
	$cexecute = curl_exec($ch);
	curl_close($ch);
	if ($cexecute) {
		$result = json_decode($cexecute, true);
		return $result;
    }else{
        return false;
	}
}

function getMp3Url($album_id,$song_id){
	$result = netease_http("http://music.163.com/api/album/$album_id?id=$album_id");
	if ($result['code'] == '200'){
		$songs = $result['album']['songs'];
	}
	foreach ($songs as $key => $song) {
		if ( $song['id'] == $song_id ) $result = $song;
	}
	return $result['mp3Url'];
}
?>