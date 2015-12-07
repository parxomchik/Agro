(function() {
    'use strict';

    angular
        .module('agro')
        .factory('loginFactory', loginFactory);

    /** @ngInject */
    function loginFactory($http,$cookies,restConfig){
        return {
            getLogin: function (data) {
                return $http({
                    method: 'GET',
                    url: restConfig.url+'api/login'
                })
            },
            sendLogin: function (data) {
                return $http({
                    method: 'POST',
                    url: restConfig.url+'api/login',
                    data: data
                })
            },
            userStatus: function ($state,$q) {
                var cookie =  $cookies.getObject('session_id');
                $log.debug(cookie);
                return cookie;
            }
        }

    }
})();
