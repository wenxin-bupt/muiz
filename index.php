<?php
$request = file_get_contents('php://input');
$input = json_decode($request, true);

foreach ($input as $key=>$value) {
    echo $key . ' = ' . $value;
}
?>