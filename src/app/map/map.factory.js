(function() {
    'use strict';

    angular
        .module('agro')
        .factory('mapFactory', mapFactory);

    /** @ngInject */
    function mapFactory($http,$cookies,restConfig){
        return {
            getMap: function (data) {
                return $http({
                    method: 'GET',
                    url: restConfig.url+'api/map'
                })
            },
            getMap2: function (data) {
                return $http({
                    method: 'GET',
                    url: 'app/map/many_polygons.json'
                })
            }
        }

    }
})();
