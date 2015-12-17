(function() {
  'use strict';

  angular
    .module('agro')
    .controller('loginController', loginController);

  /** @ngInject */
  function loginController(loginFactory,$log,$location,$scope,$cookies,$timeout) {
    loginFactory.getLogin()
        .success(function (data, status) {
          $log.debug(data);
          vm.userFields = data.data;
        });
    var vm = this;
    vm.title = 'AGRO';
    vm.user = {};

    //vm.userFields = [
    //  {
    //    key: 'login',
    //    type: 'input',
    //    templateOptions: {
    //      type: 'text',
    //      label: 'Login',
    //      placeholder: 'Enter login'
    //    }
    //  },
    //  {
    //    key: 'password',
    //    type: 'input',
    //    templateOptions: {
    //      type: 'password',
    //      label: 'Password',
    //      placeholder: 'Password'
    //    }
    //  }
    //];

  // инициализируем масив алертов
    vm.alerts = [];


    //Функция ручного закрытия алерта
    //$scope.closeAlert = function(index) {
    //  vm.alerts.splice(index, 1);
    //};

    //Функция пережачи логина и пароля на фабрику
    vm.loginSubmit = function(data){
      //var data = {
      //  username:  vm.user.login,
      //  password:  vm.user.password
      //};
      $log.debug('loginSubmit = '+angular.toJson(data));
      $log.debug('loginSubmit data.login = '+data.login);
      $log.debug('loginSubmit data.password = '+data.password);
      loginFactory.sendLogin(data)
          .success(function (data, status) {
            console.log('status = ' + status);
            console.log('data = ' + angular.toJson(data));
              if(status == 200){
                //$cookies.put('session_id',data.cookies);
                  $log
              }
          })
          .error(function (data, status) {
            if(status == 401){
              //Если длина масива алертов равна нулю,
              // то в него добавляем новый алерт и закрываем его через 5 секонд
              //if(vm.alerts.length == 0){
              //
              //  //Функция добавления алерта
              //  vm.alerts.push({ type: 'danger', msg: data.messages });
              //  //Указываем таймаут закрытия алерта
              //  $timeout(function deleteAlert(){
              //    vm.alerts.splice(0);
              //  }, 5000);
              //}

              $log.debug("Неверный логин или пароль status = "+ status);
              $log.debug("Неверный логин или пароль data = "+  data);
            }
          });
    };
  }
})();
