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
      });

      $urlRouterProvider.otherwise('/');
      $logProvider.debugEnabled(false);

  }

})();
