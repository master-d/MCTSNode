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
</head>
<body>
        <div>
                Route <select id="route">
                        <option value="GRE">Green Line</option>
                        <option value="80">80</option>
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

<script type="text/javascript">
var socket = io.connect('http://corpulenthorse.com:8001');
var vehicles = {};
var map = null;
//var imgcanvas = document.getElementById("img-rot-canvas");
var imgcanvas = $("<canvas height='40' width='40'></canvas>")[0];
var imgctx = imgcanvas.getContext("2d");
var busimg = document.createElement("img");
busimg.src="css/img/bus-icon.png";

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
socket.emit("getRoute", { rt: $("#route").val() });
socket.on("getRoute", function(data) {
	$("#json").text(JSON.stringify(data));
	if ($.isArray(data)) {
	for(var x=0; x<data.length; x++) {
		if (vehicles[data[x].vid])
			vehicles[data[x].vid].marker.setPosition(new google.maps.LatLng(data[x].lat,data[x].lon));
		else {
			vehicles[data[x].vid] = data[x];
			vehicles[data[x].vid].marker = new google.maps.Marker({
			icon: drawRotated(data[x].hdg-90),
			//icon: { url: drawRotated(data[x].hdg-90), anchor: getAnchorPoint(data[x].hdg) },
			position: new google.maps.LatLng(data[x].lat,data[x].lon),
			map: map,
			title: data[x].rt + " - " + data[x].spd + " MPH hdg:" + data[x].hdg
			});
		}

		//vehicles[data[x].vid].marker.getIcon().setAttribute("tranform","rotate(" + data[x].hdg +")");
	}
	}
});

function getAnchorPoint(hdg) {
var x=10;
var y=10;
//if (false)

}
function radiusToZoom(radius){
	radius = (+radius);
	return Math.round(14-Math.log(radius)/Math.LN2);
}
function drawRotated(degrees){
    imgctx.clearRect(0,0,imgcanvas.width,imgcanvas.height);
    imgctx.save();
    imgctx.translate(imgcanvas.width/2,imgcanvas.height/2);
    imgctx.rotate(degrees*Math.PI/180);
    if (degrees > 45 && degrees < 225) {
        imgctx.scale(1,-1);
    }

    imgctx.drawImage(busimg,-busimg.width/2,-busimg.width/2);
    imgctx.restore();
    //return imgctx.getImageData(0,0,imgcanvas.width,imgcanvas.height);

	return imgcanvas.toDataURL();
}
</script>
</body>
</html>
