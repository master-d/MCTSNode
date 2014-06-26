<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=${encoding}">
<title>MCTS Map</title>
<style type="text/css">
	html,body { height: 100%; margin: 0; padding: 0 }
	#map-canvas { height: 100% }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>    
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd4CDT5fgcRK8Ur_VbOpk6RSRrCoZ4kTg"></script>
<script type="text/javascript">
var socket = io.connect('wss://corpulenthorse.com:8001');	
$(document).ready(function() {
	var mapOptions = {
		center: new google.maps.LatLng(-34.397, 150.644),
		zoom: 8
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	socket.emit("getRoute", { rt: 80 });
	socket.on("getRoute", function(data) {
		$("#json").text(JSON.stringify(data));
	});
});
</script>
</head>
<body>
	<div id="map-canvas" />
	<div id="json"></div>
</body>
</html>