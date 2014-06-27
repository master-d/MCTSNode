<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=${encoding}">
<title>MCTS Map</title>
<style type="text/css">
	html,body { height: 100%; margin: 0; padding: 0 }
	#map-canvas { height: 500px; width: 500px }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>    
<script src="//cdn.socket.io/socket.io-1.0.0.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd4CDT5fgcRK8Ur_VbOpk6RSRrCoZ4kTg"></script>
<script type="text/javascript">
var socket = io.connect('http://corpulenthorse.com:8001');	
var vehicles = {};
var map = null;
$(document).ready(function() {
	var mapOptions = {
		center: new google.maps.LatLng(43.0500, -87.9500),
		zoom: radiusToZoom(10)
	};
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	$("#route").change(function() {
		socket.emit("getRoute", { rt: $(this).val() });
	});
	$("#radius").change(function() {
		map.setZoom(radiusToZoom($(this).val()));
	});
	socket.emit("getRoute", { rt: 80 });
	socket.on("getRoute", function(data) {
		$("#json").text(JSON.stringify(data));
		if ($.isArray(data)) {
		for(var x=0; x<data.length; x++) {
			if (vehicles[data[x].vid])
				vehicles[data[x].vid].marker.setPosition(new google.maps.LatLng(data[x].lat,data[x].lon));
			else {
				vehicles[data[x].vid] = data[x];
				vehicles[data[x].vid].marker = new google.maps.Marker({
				icon: "css/img/bus.svg",
				position: new google.maps.LatLng(data[x].lat,data[x].lon),
				map: map,
				title: data[x].rt + " - " + data[x].spd + " MPH"
				});
			}
	
			//vehicles[data[x].vid].marker.getIcon().setAttribute("tranform","rotate(" + data[x].hdg +")");
		}
		}
	});
});
function radiusToZoom(radius){
	radius = (+radius);
	return Math.round(14-Math.log(radius)/Math.LN2);
}
</script>
</head>
<body>
	<div>
		Route <select id="route">
			<option value="80">80</option>
			<option value="21">21</option>
			<option value="15">15</option>
			<option value="27">27</option>
		</select> 
		Radius <select id="radius">
			<option value="10">10</option>
			<option value="5">5</option>
			<option value="20">20</option>
		</select>
	</div>
			
	
	<div id="map-canvas"></div>
	<div id="json">json div</div>
</body>
</html>
