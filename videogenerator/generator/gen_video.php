<?php
// Backend called in index.php by ajax call

// share.php - functions
require_once "share.php";

$image_dir = $_REQUEST['resource'];
$image_duration = $_REQUEST['duration'];
$file_name = $_REQUEST['filename'];

// share.php / generateVideo
// image_dir: resource image folder name
// file_name: generating video file name
// image_duration: duration of one image in seconds
$result = generateVideo($image_dir, $file_name, $image_duration + 0);

if ($result === true) {
    echo json_encode(array('success' => true, 'filename' => $file_name));
} else {
    echo json_encode(array('success' => false, 'message' => $result));
}
