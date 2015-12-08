
(function() {
    'use strict';

    angular
        .module('agro')
        .factory('platFactory', platFactory);

    /** @ngInject */
    function platFactory($cookies,$http,restConfig) {
        return {

            getPlat: function () {
                return $http({
                    method: 'GET',
                    url: restConfig.url+'plat',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: {
                      session_id: $cookies.getObject('session_id')
                    }
                });
            },
            sendPlat: function (saveData) {
                return $http({
                  method: "POST",
                  url: restConfig.url+'plat',
                  headers:{
                    'Content-Type': "application/x-www-form-urlencoded;charset=utf-8"
                  },
                  data:saveData
                });

            }
        };
    }
})();