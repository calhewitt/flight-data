MightyFlight
============

MightyFlight is a web app which visualises the air routes from a given airport in the world, using open data and the Google Maps Api. A working copy of the project is hosted at http://www.mightyflight.tk/, and this repository contains all of the files necessary for a full version of the app to run. Once downloaded and installed on a web server running PHP, the software will work out of the box, without any need for databases etc.

License
-------

The whole MightyFlight project, including all of the files within this repository, is distributed under the terms oof the GNU General Public License, a copy of which can be found in LICENSE.txt.

Open Data
---------

The list of cities for each airport was obtained from ouraiports.com, a website which provides public domain information about airports around the world. The location data used (latitudes and longitudes of each airport) was generated by the Google Maps Geocoding API.

Scraper.rb
----------

Also included in the repository is the file Scraper.rb, which was used to generate the coordinate files used by the app from ourairports.com and the Google Maps API. However, all of this has been done in advance, so you do not need Ruby installed on your server to run the app.  

Plugins
-------

The following 3rd party JavaScript plugins are included with the software and are needed to run the app:

*Chardin.js - Help overlays
*Typeahead.js - Autocomplete
*jQuery - Main Framework