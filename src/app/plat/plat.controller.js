(function() {
    'use strict';

angular
    .module('agro')
    .controller('platController', platController);

/** @ngInject */
function platController($log,platFactory,NgTableParams,ngTableParams,$scope,$filter) {

    $log.debug("platController start");
    var vm = this;
    var data = [];
    vm.showPtals = function(ptalsData){
        var res = [];
        $log.debug('showPtals = '+angular.toJson(ptalsData));
        angular.forEach(ptalsData,function(value1, key1){
            $log.debug('value = '+angular.toJson(value1),'key ='+key1);
            angular.forEach(value1, function (value2, key2) {
                $log.debug('value2 = '+angular.toJson(value2),'key2 ='+key2);
                if(key2 == 'isSelected' &&  value2 == true){
                    $log.debug('ok');
                    $log.debug(value1);
                    res.push(value1);
                }
            })
        });
        $log.debug('res = '+angular.toJson(res));
    };

    platFactory.getPlat()
        .success(function (result, status) {
            data = result;
            vm.tableParams = new ngTableParams({
            page: 1,            // show first page
            sorting: {
                name: ''     // initial sorting
            }
            },{
            counts: [5, 10, 20],
            total: data.length, // length of data

            getData: function($defer, params) {
                var filteredData = params.filter() ? $filter('filter')(data, params.filter()) : data;
                $log.debug('filteredData = '+angular.toJson(filteredData));
                var orderedData = params.sorting() ? $filter('orderBy')(filteredData, params.orderBy()) : filteredData;
                params.total(filteredData.length);
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
            vm.tableParams.reload();
        });

}
})();