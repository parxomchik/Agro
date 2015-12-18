(function() {
  'use strict';

  angular
    .module('agro')
    .controller('mapController', mapController);

  /** @ngInject */
  function mapController($scope, $log, uiGmapGoogleMapApi, $http,mapFactory) {
    //$log.currentLevel = $log.LEVELS.debug;
    var polyFillCtr = 0;
    $scope.polywindow = {
      closeClick: function(){
        $scope.polywindow.show = false;
      },
      coords: {
        latitude: 53,
        longitude: 20
      },
      show: false
    };
    $scope.map = {
      center: {
        latitude: 26.153215225012733,
        longitude: -81.80121597097774
      },
      pan: true,
      zoom: 15,
      refresh: false,
      clickable: true,
      events: {
        //click: function (marker) {
        //  marker.showWindow = true;
        //  $scope.$apply();
        //  //window.alert("Marker: lat: " + marker.latitude + ", lon: " + marker.longitude + " clicked!!")
        //},
        //dblclick: function (marker) {
        //  alert("Double Clicked!");
        //}
        //click: function (mapModel, eventName, originalEventArgs) {
        //  var e = originalEventArgs[0];
        //  var lat = e.latLng.lat(),
        //      lon = e.latLng.lng();
        //  $scope.map.clickedMarker = {
        //    id: 0,
        //    title: 'You clicked here ' + 'lat: ' + lat + ' lon: ' + lon,
        //    latitude: lat,
        //    longitude: lon
        //  };
        //  //scope apply required because this event handler is outside of the angular domain
        //  $scope.$apply();
        //}
      },
      bounds: {},
      polys: [
      ],
      getPolyFill: function(model){
        if(!model){
          $log.debug("model undefined!");
          return;
        }
        polyFillCtr += 1;
        $log.debug("polyFillCtr: " + polyFillCtr + ", id: " + model.id);
        return { color: '#2c8aa7', opacity: '0.3' };
      },
      polyEvents: {

        //dblclick: function (marker) {
        //  alert("Double Clicked!");
        //} ,
        //click: function () {
        //  alert("click Clicked!");
        //}
        click: function (gPoly, eventName, polyModel) {

          var bounds = new google.maps.LatLngBounds();
          gPoly.getPath().forEach(function(latLng){bounds.extend(latLng)});
          var gCenter = bounds.getCenter();
          $scope.polywindow.coords = {
            latitude:gCenter.lat(),
            longitude:gCenter.lng()
          };

          $scope.polywindow.show = true;


          //alert($scope.map.polys);
          $log.debug(polyModel.id);
          $scope.polywindow.content = polyModel.id;


          $log.debug(polyModel);
          //$log.debug(polyModel.geom.coordinates);

          $log.debug('gPoly = '+gPoly);

        }
      },
      draw: undefined
    };
    var rawPolys = [];
    uiGmapGoogleMapApi.then(function () {
      mapFactory.getMap2().then(function (data) {
        $log.debug("poly length: " + data.data.length);
        $scope.map.polys = data.data;
        $log.debug("$scope.map.polys =  " + $scope.map.polys);

      });


      //$http.get('app/map/many_polygons.json').then(function (data) {
      //  $log.debug("poly length: " + data.data.length);
      //  $scope.map.polys = data.data;
      //  $log.debug("$scope.map.polys =  " + $scope.map.polys);
      //});

    });
  }
})();
