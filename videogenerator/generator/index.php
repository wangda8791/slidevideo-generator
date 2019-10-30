<?php
	require_once("share.php");

	// load image directories existing in resources folder.
	$resources = loadImageLibrary();
	// load video files existing in videos folder.
	$videos = loadVideos();
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Video Generator</title>
	<script>
	    // called to generate video with images of selected image folder.
		// it calls gen_video.php backend with ajax.
		function generateVideo() {
			$("#btnGenerate").val("Generating... Please wait...");
			var strResource = $("#resource").val();
			var iDuration = $("#duration").val();
			var strFilename = $("#filename").val();
			$.ajax({
                url : "./gen_video.php",
                data : { resource:strResource, duration:iDuration, filename:strFilename },
                dataType : "json",
                type : "get",
                success : function(rData) {
                    if (rData.success) {
                    	alert("Video is successfully generated.");
                    	var newlink = $("<br/><a href='../videos/" + rData.filename + "' target='_blank'>" + rData.filename + "</a>");
                    	$("#videoContainer").append(newlink);
                    } else {
                    	alert(rData.message);
                    }
                },
                error : function() {
                	alert("Video generation is failed.");
                },
                complete : function() {
                	$("#btnGenerate").val("Generate");
                }
            });
		}
	</script>
</head>
<body>
	<table>
	<tr>
	<td>Image resource folder: </td>
	<td><select id="resource">
	<?php foreach ($resources as $resource) { ?>
		<option value='<?php echo $resource; ?>'><?php echo $resource; ?></option>
	<?php } ?>
	</select></td>
	<tr/><tr>
	<td>Duration per image: </td>
	<td><input type="text" value="5" id="duration" /></td>
	</tr><tr>
	<td>Video file name: </td>
	<td><input type="text" value="6200-Curzon-Ave_Fort-Worth.mp4" id="filename" /></td>
	</tr>
	</table>
	<input type="button" id="btnGenerate" value="Generate" onclick="generateVideo()" />
	<hr/>
	<div id="videoContainer">
	Result: 
	<?php foreach ($videos as $video) { ?>
	<br/><a href='../videos/<?php echo $video; ?>' target='_blank'><?php echo $video; ?></a>
	<?php } ?>
	</div>

	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.js"></script>
</body>
</html>