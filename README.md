# GoogleMap-API

A project that uses Google Map API to view Markers that are in the current viewport. Tested with 9000+ markers.

The markers data is exported from database to a XML file. The file 'downloadXML.php' is to download all the markers data into a XML file.

The XML file will then be loaded in Google Maps from 'googleMaps.php'.
A sample XML file is included here.

Added a custom Overlay View to customise the markers icon into a HTML element icon. In this case, I use a label tag as the markers icon.

Each marker will contain an InfoWindow that pop up upon clicking the Marker. The content of the InfoWindow is customizable by using HTML tags.
