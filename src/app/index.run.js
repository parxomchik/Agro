(function() {
  'use strict';

  angular
    .module('agro')
    .run(runBlock);

  /** @ngInject */
  function runBlock($log,ngTableDefaults) {

    $log.debug('runBlock end');
    //function configureDefaults(ngTableDefaults) {
      ngTableDefaults.params.count = 5;
      ngTableDefaults.settings.counts = [];
    //}
  }

})();
