<?php

define("IMAGE_FOLDER_PATH", "/var/www/html/videogenerator/resources/");
define("VIDEO_FOLDER_PATH", "/var/www/html/videogenerator/videos/");
define("TEMP_PATH", "/var/www/html/videogenerator/tmp"); // should be end without slash

// get files or directories
function dirlist($dir, $bool = "dirs")
{
    $truedir = $dir;
    $dir = scandir($dir);
    if ($bool == "files") {
        $direct = 'is_dir';
    } elseif ($bool == "dirs") {
        $direct = 'is_file';
    }
    foreach ($dir as $k => $v) {
        if (($direct($truedir . $dir[$k])) || $dir[$k] == '.' || $dir[$k] == '..') {
            unset($dir[$k]);
        }
    }
    $dir = array_values($dir);
    return $dir;
}

// load all image folders existing in resources folder.
function loadImageLibrary()
{
    $resources = dirlist(IMAGE_FOLDER_PATH, "dirs");
    return $resources;
}

// load all videos existing in videos folder.
function loadVideos()
{
    $videos = dirlist(VIDEO_FOLDER_PATH, "files");
    return $videos;
}

// core function to generate video from slides.
// this function create description file which diascope tool execute to generate video with.
// parameters -
//            image_dir: resource image folder name
//            file_name: generating video file name
//            image_duration: duration of one image in seconds
// returns -
//            true: on succes
//            message: on failure
function generateVideo($imagedir, $filename, $duration)
{
    $source_url = IMAGE_FOLDER_PATH . $imagedir . "/";
    $target_url = VIDEO_FOLDER_PATH . $filename;

    $files = dirlist($source_url, "files");

    if (count($files) == 0) {
        return "No image files.";
    }

    if ($duration < 1) {
        return "Invalid duration(>1).";
    }

    $base_name = str_replace(".mp4", "", $filename);

    $description =
        "format 1280x720@25 quality=1.0 mpeg4=3,192 aspect=16:9" . "\r\n" .
        "base " . $base_name . " " . TEMP_PATH . "\r\n" .
        "set dur=sec" . "\r\n";

    $first_file = $files[0];
    list($width, $height, $type, $attr) = getimagesize($source_url . $first_file);
    $zoom_offset_x = round($width * 0.1);
    $zoom_offset_y = round($height * 0.1);
    $zoom_width = $width - $zoom_offset_x * 2;
    $fade_startup_duration = 1;
    $transition_duration = 1;
    $hold_pre_sec = 0;
    $hold_after_sec = 0;
    $hold_sec = $duration - $hold_pre_sec - $hold_after_sec;
    $description .= "create 1 black" . "\r\n";
    $description .= "trns " . $transition_duration . " cross" . "\r\n";
    $description .= "kbrn " . $hold_pre_sec . "," . $hold_sec . "," . $hold_after_sec . " '" . $first_file . "' accel=1 xyw=0,0," . $width . " xyw=" . $zoom_offset_x . "," . $zoom_offset_y . "," . $zoom_width . "\r\n";
    $mode = 0;
    for ($i = 1; $i < count($files); $i++) {
        $description .= "trns " . $transition_duration . " cross" . "\r\n";
        while (($new_mode = rand(0, 3)) == $mode) {
        }
        $mode = $new_mode;
        switch ($mode) {
            case 0: //zoom in
                $description .= "kbrn " . $hold_pre_sec . "," . $hold_sec . "," . $hold_after_sec . " '" . $files[$i] . "' accel=1 xyw=0,0," . $width . " xyw=" . $zoom_offset_x . "," . $zoom_offset_y . "," . $zoom_width . "\r\n";
                break;
            case 1: //zoom out
                $description .= "kbrn " . $hold_pre_sec . "," . $hold_sec . "," . $hold_after_sec . " '" . $files[$i] . "' accel=1 xyw=" . $zoom_offset_x . "," . $zoom_offset_y . "," . $zoom_width . "\r\n";
                break;
            case 2: //to right
                $description .= "kbrn " . $hold_pre_sec . "," . $hold_sec . "," . $hold_after_sec . " '" . $files[$i] . "' accel=1 xyw=0,0," . ($width - $zoom_offset_x) . " xyw=" . $zoom_offset_x . ",0," . ($width - $zoom_offset_x) . "\r\n";
                break;
            case 3: //to left
                $description .= "kbrn " . $hold_pre_sec . "," . $hold_sec . "," . $hold_after_sec . " '" . $files[$i] . "' accel=1 xyw=" . $zoom_offset_x . ",0," . ($width - $zoom_offset_x) . " xyw=0,0," . ($width - $zoom_offset_x) . "\r\n";
                break;
        }
    }

    $description_file = $source_url . $base_name . ".txt";
    file_put_contents($description_file, $description);
    $script = "diascope -- -clean '" . $description_file . "'";

    $original_max_exe_time = ini_get("max_execution_time");
    ini_set("max_execution_time", -1);
    $cwd = getcwd();
    chdir($source_url);
    exec($script);
    exec("rm " . $base_name . ".wav");
    exec("rm " . $base_name . "_slideshow.sh");
    exec("rm " . $base_name . ".txt");
    exec("rm -rf /tmp/diascope-" . $base_name);
    exec("rm -rf /tmp/" . $base_name . "_TMP");
    exec("mv " . $filename . " " . $target_url);
    chdir($cwd);
    ini_set("max_execution_time", $original_max_exe_time);

    return true;
}
