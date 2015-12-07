/* global malarkey:false, toastr:false, moment:false */
(function() {
  'use strict';

  angular
    .module('agro')
    .constant("restConfig", {
        //"url": "http://local.ua"
        "url": "http://localhost:3000/"
        //"url": "http://vybory.epicentr.net.ua"
        //"port": "8081"
     })
    .constant("loginConfig", {
        "url": "http://crm.epicentr.com/api/login/",
        "port": "8081"
    });
})();
