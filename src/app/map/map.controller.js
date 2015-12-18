(function() {
  'use strict';

  angular
    .module('agro')
    .controller('mapController', mapController);

  /** @ngInject */
  function mapController($scope, $log, uiGmapGoogleMapApi,mapFactory) {
    //$log.currentLevel = $log.LEVELS.debug;
    var polyFillCtr = 0;
    $scope.polywindow = {
      closeClick: function(polyModel){

        $scope.polywindow.show = false;
        $scope.map.color = { color: '#2c8aa7', weight: 2, opacity: '0.5' };
        $scope.map.fill = { color: '#2c8aa7', weight: 2, opacity: '0.5' };
          //polyModel.fill.color = '#2c8aa7';

        $log.debug('closeClick polyModel = '+polyModel);

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
      color:{ color: '#2c8aa7', weight: 2, opacity: '0.5' },
      fill:{ color: '#2c8aa7', weight: 2, opacity: '0.5' },
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
        click: function (gPoly, eventName, polyModel,model) {

          var bounds = new google.maps.LatLngBounds();
          gPoly.getPath().forEach(function(latLng){bounds.extend(latLng)});
          var gCenter = bounds.getCenter();
          $scope.polywindow.coords = {
            latitude:gCenter.lat(),
            longitude:gCenter.lng()
          };

          $scope.polywindow.show = true;


          //alert($scope.map.polys);
          $log.debug('polyModel = '+angular.toJson(polyModel));
          $log.debug('model = '+angular.toJson(model));


          $scope.polywindow.content = polyModel.$id;


          $log.debug('polyModel stroke = '+angular.toJson(polyModel.stroke));

          //polyModel.stroke = 'red';
          $log.debug('polyModel fill = '+angular.toJson(polyModel.fill));

          polyModel.fill.color = 'red';
          $log.debug('polyModel fill = '+angular.toJson(polyModel.fill));

          //$log.debug(polyModel.geom.coordinates);

          //$log.debug('gPoly = '+gPoly);

          //$log.debug('map.shapes = '+angular.toJson($scope.map));


          //$log.debug('map.shapes = '+angular.toJson($scope.map.color));


          //$scope.map.color.color = 'red';


          //$scope.map.
          //polyModel.$id.stroke = 'red'
          //polyModel.stroke = 'red';


          //map.setOptions({strokeWeight: 2.0, fillColor: 'green'});
        },
        mouseout:function (gPoly, eventName, polyModel) {
          polyModel.fill.color = '#2c8aa7';
        }
      },
      draw: undefined
    };
    uiGmapGoogleMapApi.then(function () {
      mapFactory.getMap2().then(function (data) {
        $log.debug("poly length: " + data.data.length);
        $scope.map.polys = data.data;
        //$log.debug("$scope.map.polys =  " + $scope.map.polys);
        //$log.debug("$scope.map.polys[0] =  " + angular.toJson($scope.map.polys[0]));

      });


      //$http.get('app/map/many_polygons.json').then(function (data) {
      //  $log.debug("poly length: " + data.data.length);
      //  $scope.map.polys = data.data;
      //  $log.debug("$scope.map.polys =  " + $scope.map.polys);
      //});

    });
  }
})();
