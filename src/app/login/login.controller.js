(function() {
  'use strict';

  angular
    .module('agro')
    .controller('loginController', loginController);

  /** @ngInject */
  function loginController(loginFactory,$log,$location,$scope,$cookies,$timeout) {
    var vm = this;
    // инициализируем масив алертов
    vm.alerts = [];


    //Функция ручного закрытия алерта
    $scope.closeAlert = function(index) {
      vm.alerts.splice(index, 1);
    };

    //Функция пережачи логина и пароля на фабрику
    vm.loginSubmit = function(){
      var data = {
        username:  vm.username,
        password:  vm.password
      };
      $log.debug(data);
      loginFactory.sendLogin(data)
          .success(function (data, status) {
            console.log('status = ' + status);
            console.log('data = ' + angular.toJson(data));
              if(status == 200){
                $cookies.put('session_id',data.cookies);

                switch (data.access_type){
                  case 1:
                    $location.path("/client/regions/");
                break;
                  case 2:
                    $location.path("/client/monitoring/");
                break;
                }
              }
          })
          .error(function (data, status) {
            if(status == 401){
              //Если длина масива алертов равна нулю,
              // то в него добавляем новый алерт и закрываем его через 5 секонд
              if(vm.alerts.length == 0){

                //Функция добавления алерта
                vm.alerts.push({ type: 'danger', msg: data.messages });
                //Указываем таймаут закрытия алерта
                $timeout(function deleteAlert(){
                  vm.alerts.splice(0);
                }, 5000);
              }

              $log.debug("Неверный логин или пароль status = "+ status);
              $log.debug("Неверный логин или пароль data = "+  data);
            }
          });
    };
  }
})();
