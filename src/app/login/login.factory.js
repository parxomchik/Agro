(function() {
    'use strict';

    angular
        .module('agro')
        .factory('loginFactory', loginFactory);

    /** @ngInject */
    function loginFactory($http,$cookies,restConfig){
        return {
            sendLogin: function (data) {
                return $http({
                    method: 'POST',
                    url: restConfig.url+'api/login',
                    data: {
                        username:  data.username,
                        password:  data.password
                    }
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
