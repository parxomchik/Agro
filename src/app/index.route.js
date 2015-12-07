(function() {
  'use strict';

  angular
    .module('agro')
    .config(routeConfig);

  /** @ngInject */
  function routeConfig($stateProvider, $urlRouterProvider,$logProvider) {
    $stateProvider
        .state('login', {
        url: '/',
        templateUrl: 'app/login/login.html',
        controller: 'loginController',
        controllerAs: 'login'
      })
        .state('client', {
            template: '<div ui-view></div>',
            resolve:
            {
                user : function (loginFactory,$q,$log) {
                    var res = loginFactory.userStatus();
                    $log.debug('res ='+res);
                    return loginFactory.userStatus() || $q.reject();
                }
            }
        })


      $urlRouterProvider.otherwise('/');
      $logProvider.debugEnabled(false);

  }
})();
