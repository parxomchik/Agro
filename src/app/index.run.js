(function() {
  'use strict';

  angular
    .module('agro')
    .run(runBlock);

  /** @ngInject */
  function runBlock($log) {

    $log.debug('runBlock end');
  }

})();
