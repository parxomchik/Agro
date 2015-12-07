(function() {
  'use strict';

  angular
    .module('agro')
    .controller('loginController', loginController);

  /** @ngInject */
  function loginController(loginFactory,$log,$location,$scope,$cookies,$timeout) {
    var vm = this;

    vm.user = {};

    // note, these field types will need to be
    // pre-defined. See the pre-built and custom templates
    // http://docs.angular-formly.com/v6.4.0/docs/custom-templates
    vm.userFields = [
      {
        key: 'email',
        type: 'input',
        templateOptions: {
          type: 'email',
          //label: 'Email address',
          placeholder: 'Enter email'
        }
      },
      {
        key: 'password',
        type: 'input',
        templateOptions: {
          type: 'password',
          //label: 'Password',
          placeholder: 'Password'
        }
      }
    ];

  // инициализируем масив алертов
    vm.alerts = [];


    //Функция ручного закрытия алерта
    //$scope.closeAlert = function(index) {
    //  vm.alerts.splice(index, 1);
    //};

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
