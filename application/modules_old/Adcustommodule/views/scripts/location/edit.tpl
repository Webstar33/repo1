<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Adcustommodule
 * @version    $Id: IndexController.php 2018-17-02 9:40:21Z $
 */
?>
<!DOCTYPE html>
<html>
  	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<style>
			.wrapper {
				height: 280px;
			}
			.logo img {
				height:30px;
				width:50px;
			}
			.logo {
			    float: right;
			    width: 30%;
			    display: inline-block;
			    text-align: right;
			    margin-top: 7px;
			}
			.bottom {
			    width: 43%;
			    text-align: right;
			    display: inline-block;
			}
			#save-search {
				font-family: 'Open Sans',sans-serif;
				border-radius: 3px;
				padding: 0.2em .5em;
				font-size: 13px;
				border: none;
				background-color: #5561EC;
				color: #fff;
				transition: all .2s ease-in-out;
				cursor: pointer;
			}
			#map {
				height: 80%;
				width: 100%;
			}

			#container {
				text-align: center;
				margin-top: 15px;
			}
			#submit-search {
				padding-top: 10px;
				padding-right: 10px;
			}
			.gm-style-cc {
				display: none;
			}
 			/* Optional: Makes the sample page fill the window. */
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
			}
			.controls {
				margin-top: 10px;
				border: 1px solid transparent;
				border-radius: 2px 0 0 2px;
				box-sizing: border-box;
				-moz-box-sizing: border-box;
				height: 32px;
				outline: none;
				box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			}

			#pac-input {
				background-color: #fff;
				font-family: Roboto;
				font-size: 15px;
				font-weight: 300;
				margin-left: 12px;
				padding: 0 11px 0 13px;
				text-overflow: ellipsis;
				width: 300px;
			}

			#pac-input:focus {
				border-color: #4d90fe;
			}

			.pac-container {
				font-family: Roboto;
			}

			#type-selector {
				color: #fff;
				background-color: #4d90fe;
				padding: 5px 11px 0px 11px;
			}

			#type-selector label {
				font-family: Roboto;
				font-size: 13px;
				font-weight: 300;
			}
			#target {
				width: 345px;
			}
		</style>
	</head>
	<body>
		<div class="wrapper">
	    	<div id="map"></div>
		    	<script>
				    var map;
				    function initAutocomplete() {
				        
				        var allowedBounds = new google.maps.LatLngBounds(
				          new google.maps.LatLng(85, -180), // top left corner of map
				          new google.maps.LatLng(-85, 180)  // bottom right corner
				        );

				        var k = 5.0; 
				        var n = allowedBounds .getNorthEast().lat() - k;
				        var e = allowedBounds .getNorthEast().lng() - k;
				        var s = allowedBounds .getSouthWest().lat() + k;
				        var w = allowedBounds .getSouthWest().lng() + k;
				        var neNew = new google.maps.LatLng( n, e );
				        var swNew = new google.maps.LatLng( s, w );
				        var worldwide = new google.maps.LatLngBounds( swNew, neNew );

				        map = new google.maps.Map(document.getElementById('map'), {
				          center: {lat: -33.8688, lng: 151.2195},
				          mapTypeControl: false,
				          mapTypeId: google.maps.MapTypeId.HYBRID,

				        });

				        map.fitBounds(worldwide);
				        // Create the search box and link it to the UI element.
				        var input = document.getElementById('pac-input');
				        var searchBox = new google.maps.places.SearchBox(input);

				        // Bias the SearchBox results towards current map's viewport.
				        map.addListener('bounds_changed', function() {
				          searchBox.setBounds(map.getBounds());
				        });

				        var markers = [];
				        // Listen for the event fired when the user selects a prediction and retrieve
				        // more details for that place.
				        var search = false;
				        searchBox.addListener('places_changed', function() {
				          var places = searchBox.getPlaces();
				          search = true;
				          if (places.length == 0) {
				            return;
				          }

				          // Clear out the old markers.
				          markers.forEach(function(marker) {
				            marker.setMap(null);
				          });
				          markers = [];

				          // For each place, get the icon, name and location.
				          var bounds = new google.maps.LatLngBounds();
				          places.forEach(function(place) {
				            if (!place.geometry) {
				              console.log("Returned place contains no geometry");
				              return;
				            }

				            // Create a marker for each place.
				            var marker = new google.maps.Marker({
				              map: map,
				              // icon: icon,
				              title: place.name,
				              position: place.geometry.location
				            });

				            markers.push(marker);
				            infowindow = new google.maps.InfoWindow();
				            
				            google.maps.event.addListener(marker, 'click', function() {
				              infowindow.setContent(place.name);
				              infowindow.open(map, this);
				            });

				            if (place.geometry.viewport) {
				              // Only geocodes have viewport.
				              bounds.union(place.geometry.viewport);
				            } else {
				              bounds.extend(place.geometry.location);
				            }

				            if(typeof  place.address_components != 'undefined' && place.address_components.length > 1) {
				              place.address_components.forEach(function(component) {
				                var address = place.formatted_address; 
				                if( component.types[0] === "country" ) {

				                  if(component.short_name == "US") {
				                    address = address.replace( "USA", "" );
				                  } else {
				                    address = address.replace( component.long_name, "" );
				                    address = address.replace( component.short_name, "" );
				                  }
				                  
				                  address = address.trim();
				                  if( address.length-1 == address.lastIndexOf(",") ) {
				                    address = address.replace(new RegExp("," + '$'), "");
				                  }

				                  input.value = address;
				                }

				              });
				            }
				            placeObject = place;
				            map.setZoom(21);
				            map.panTo(marker.getPosition());

				          });
				          map.fitBounds(bounds);
				        });
				        
				        map.addListener('tilesloaded', function () {
				        	if(!search) {
				        		map.setZoom(1);
				        	}
				        	document.getElementById("pac-input").focus();
				        });
				    }

					var placeObject;
					var locationParams;
					function modifyLocation()
					{
						if(!placeObject) {
							console.log('Not Valid Place object',locationParams);
							return;
						}
						
						placeObject.formatted_address = document.getElementById("pac-input").value;
						var place = placeObject;
						var address = '';
						var country = '';
						var state = '';
						var zipcode = '';
						var city = '';
						var len_add= '';
						var types_location = '';
						if (place.address_components) {
							len_add = place.address_components.length;

							for (var i = 0; i < len_add; i++) {
								types_location = place.address_components[i]['types'][0];
								if (types_location === 'country') {
									country = place.address_components[i]['long_name'];
								} else if (types_location === 'administrative_area_level_1') {
									state = place.address_components[i]['long_name'];
								} else if (types_location === 'administrative_area_level_2') {
									city = place.address_components[i]['long_name'];
								} else if (types_location === 'postal_code' || types_location === 'zip_code') { 
									zipcode = place.address_components[i]['long_name'];
								} else if (types_location === 'street_address') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'locality') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'room') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'route') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'sublocality') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'street_number') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'postal_town') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'postal_code') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'subpremise') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'neighborhood') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'post_box') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'park') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								} else if (types_location === 'natural_feature') {
									if (address === '')
										address = place.address_components[i]['long_name'];
									else
										address = address + ',' + place.address_components[i]['long_name'];
								}
							}
						}
						var locationParams = '{"location" :"' + place.formatted_address + '","latitude" :"' + place.geometry.location.lat() + '","longitude":"' + place.geometry.location.lng() + '","formatted_address":"' + place.formatted_address.replace(/\"/g, "") + '","address":"' + address.replace(/\"/g, "") + '","country":"' + country + '","state":"' + state + '","zipcode":"' + zipcode + '","city":"' + city + '"}';
						console.log(locationParams);
						parent["$"]("locationParams").value = locationParams;
						parent["$$"](".sd_feed_worldwide > a")[0].innerHTML = place.formatted_address;
						parent.loadLocationFeed(parent["$$"](".sd_feed_worldwide > a")[0]);
						parent["$"]("TB_overlay").fireEvent("click");
					}
		    	</script>
		    <?php 	$apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey(); ?>
		    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey ?>&libraries=places&callback=initAutocomplete" async defer>
		    </script>
		    <div id="container">
		      	<div id="input-search">
		      		<input id="pac-input" class="controls" type="text" placeholder="Search location here..." >      
		      	</div>
		      	<div class="bottom">
			      	<div id="submit-search">
			      		<button id="save-search" onclick="modifyLocation()" >Enter</button>
			      	</div>	
		      	</div>
		      	<div class="logo">
				    	<img src="<?php echo $this->layout()->staticBaseUrl ?>public/blue_wstar_logo_web-use.png">
				</div>
		    </div>
		</div>
	</body>
</html>