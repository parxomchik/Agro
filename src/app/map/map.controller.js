(function() {
  'use strict';

  angular
    .module('agro')
    .controller('mapController', mapController);

  /** @ngInject */
  function mapController($scope, $log, uiGmapGoogleMapApi, $http) {
    //$log.currentLevel = $log.LEVELS.debug;
    var polyFillCtr = 0;
    $scope.map = {
      center: {
        latitude: 26.153215225012733,
        longitude: -81.80121597097774
      },
      pan: true,
      zoom: 16,
      refresh: false,
      events: {},
      bounds: {},
      polys: [],
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
        click: function (gPoly, eventName, polyModel) {
          window.alert("Poly Clicked: id:" + polyModel.$id + ' ' + JSON.stringify(polyModel.path));
        }
      },
      draw: undefined
    };
    var rawPolys = [];
    uiGmapGoogleMapApi.then(function () {
      $http.get('app/map/many_polygons.json').then(function (data) {
        $log.debug("poly length: " + data.data.length);
        $scope.map.polys = data.data;
        $log.debug("$scope.map.polys =  " + $scope.map.polys);

      });

    });
  }
})();
