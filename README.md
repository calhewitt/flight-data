MightyFlight
============

MightyFlight is an open source, open data visualtisation of the places you can fly to from any airport in the world. The project is hosted at http://mightyflight.tk/ and this repository contains all of the files needed for the full app to run. Once downloaded and installed on a server running PHP it will work out of the box, without any need for databases etc...

Plugins
-------

The following JavaScript plugins are included in the repo which are used in the app
* Chardin.js - Help overlays
*Typeahead.js - Autocomplete
*jQuery - Main Framework

Scraper.rb
----------

Also included in the repository is the file Scraper.rb, which was used to generate the coordinate files used by the app from ourairports.com and the Google Maps API. However, all of this has been done in advance, so you do not need Ruby installed to run the app.  