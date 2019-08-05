<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      label {
        background-color: yellow;
        font-size: 15px;
      }
    </style>
  </head>

<html>
  <body>
    <div id="map"></div>

    <script>
      var markersArray = [];

      //Initialization of map
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-33.863276, 151.207977),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;

        //Getting map data from XML file downloaded from database
        downloadUrl('google_markers.xml', function(data) {
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName('marker');
          Array.prototype.forEach.call(markers, function(markerElem) {
            var id = markerElem.getAttribute('id');
            var name = markerElem.getAttribute('name');
            var description = markerElem.getAttribute('description');
            var discount_value = markerElem.getAttribute('discount_value');
            var lat = markerElem.getAttribute('lat');
            var lng = markerElem.getAttribute('lng');
            var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = description;
            infowincontent.appendChild(text);

            function HTMLMarker(lat,lng){
              this.lat = lat;
              this.lng = lng;
              this.div_ = null;
              this.pos = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
            }

            //Custom Icon using Google Maps Overlay View
            HTMLMarker.prototype = new google.maps.OverlayView();
            HTMLMarker.prototype.onRemove= function(){
              this.div_.parentNode.removeChild(this.div_);
              this.div_ = null;
            }

            //Init your html element here
            HTMLMarker.prototype.onAdd= function(){
              div = document.createElement('div');
              div.className = "markers";
              div.style.position = "fixed";
              div.innerHTML = "<label>" + discount_value + "%</label>";

              this.div_ = div;

              var panes = this.getPanes();
              panes.overlayImage.appendChild(div);
            }

            HTMLMarker.prototype.draw = function(){
              var overlayProjection = this.getProjection();
              var position = overlayProjection.fromLatLngToDivPixel(this.pos);
              var panes = this.getPanes();
              var div = this.div_;

              div.style.left = (position.x) + 'px';
              div.style.top = (position.y) + 'px';

              google.maps.event.addDomListener(div, "click", function() {
                infoWindow.setContent(infowincontent);
                infoWindow.setPosition(point);
                infoWindow.open(map);
              });
            }

            //Initialize the custom overlay
            var htmlMarker = new HTMLMarker(lat, lng);
            htmlMarker.setMap(null);
            markersArray.push(htmlMarker);

          });
        });

        google.maps.event.addListener(map, 'idle', function () {
          loadMarkersFromCurrentBounds(map);
        });
      }

      //Load Markers from current viewport
      function loadMarkersFromCurrentBounds(map){
        for (var i = 0; i < markersArray.length; i++ ) {
          if( map.getBounds().contains(markersArray[i].pos) ){
            markersArray[i].setMap(map);
          }
          else{
            markersArray[i].setMap(null);
          }
        }
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"
    async defer></script>
  </body>
</html>