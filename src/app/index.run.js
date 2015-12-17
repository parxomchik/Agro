(function() {
  'use strict';

  angular
    .module('agro')
    .run(runBlock);

  /** @ngInject */
  function runBlock($log,i18nService) {

    $log.debug('runBlock end');
    i18nService.setCurrentLang('ua');

  }

})();
