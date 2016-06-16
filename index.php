<html>
<head>
  <title>网易云音乐歌单信息查询 | 负荷领域</title>
</head>
<body>
<?php 
$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
$url=dirname($url);
?>
<a href="http://www.loadfield.com/" target="_blank"><img src="http://7.arczs.com/wp-content/themes/QAQ/img/logo.png" width="220" height="50" alt="负荷领域"></a>
<a style="text-decoration:none;color:#6495ED;" href="./song.php">音乐信息查询</a>
<form method ="get" action="">
	<p>歌单ID或分享链接:
	<input id="id" name="id" type="text" autocomplete="off" value="118978296"/>
	<input type="submit" value="查询" />
	</p>
</form>
<?php
$mid = $_GET['id'];
preg_match('/[1-9]([0-9]{3,11})/', $mid, $matches);
$mid = $matches[0];
if (!$mid){
	goto tabEnd;
}
$json = file_get_contents('http://music.163.com/api/playlist/detail?id='.$mid);
// mp3Url
if (!strpos($json,'mp3Url')){
	exit('查询失败');
}
$array = json_decode($json,true);
$coverImgUrl = $array['result']['coverImgUrl'];
$name = $array['result']['name'];
$nickname = $array['result']['creator']['nickname'];
$userId = $array['result']['creator']['userId'];
$tracks = $array['result']['tracks'];
echo '歌单作者：<a style="text-decoration:none;color:#6495ED;" href="http://music.163.com/#/user/home?id='.$userId.'"  target="_blank">'.$nickname.'</a>';
echo '<br>歌单名称：<a style="text-decoration:none;color:#6495ED;" href="http://music.163.com/#/m/playlist?id='.$mid.'"  target="_blank">《'.$name.'》</a>';
echo '<br><a href="'.$coverImgUrl.'" target="_blank" title="点击查看大图">'.'<img src="'.$coverImgUrl.'?param=180y180" border="0"></a>';
?>
<table >
	<tbody>
		<?php
		foreach($tracks as $key => $value){
		?>
		<tr>
			<td>
				<?php
				echo '<a style="text-decoration:none;color:#6495ED;" href="'.$url.'/song.php?id='.$value['id'].'" target="_blank">'.$value['name'].'</a>';
				?>
			</td>
			<td>
				<?php
				echo '&nbsp;'.'<a style="text-decoration:none;color:#6495ED;" href="http://music.163.com/#/artist?id='.$value['artists']['0']['id'].'" target="_blank">'.$value['artists']['0']['name'].'</a>';
				?>
			</td>
			<td>
				<?php
				// echo '&nbsp;'.$value['album']['name'];
				echo '&nbsp;'.'<a style="text-decoration:none;color:#6495ED;" href="http://music.163.com/#/album?id='.$value['album']['id'].'" target="_blank">《'.$value['album']['name'].'》</a>';
				?>
			</td>
			<td>
				<?php
				$mp3Url = $value['mp3Url'];
				echo '&nbsp;'.'<a style="text-decoration:none;" href="'.$mp3Url.'" download="'.$mp3Url.'">下载</a>';
				echo '&nbsp;'.'<a style="text-decoration:none;color:#6495ED;" href="'.$mp3Url.'" target="_blank">播放</a>';
				?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php 
tabEnd:
?>
</body>
</html>