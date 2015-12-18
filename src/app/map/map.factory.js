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
            sendLogin: function (data) {
                return $http({
                    method: 'POST',
                    url: restConfig.url+'api/map',
                    data: data
                })
            }
        }

    }
})();
