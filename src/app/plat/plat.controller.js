(function() {
    'use strict';

angular
    .module('agro')
    .controller('platController', platController);

/** @ngInject */
function platController($log,platFactory) {

    $log.debug("platController start");
    var vm = this;

    platFactory.getPlat()
        .success(function (result) {
            $log.debug('ui-grid res = '+angular.toJson(result));
            vm.gridOptions1.data = result;
             vm.clonedGridOptions1 = angular.copy(vm.gridOptions1.data);
            $log.debug('vm.clonedGridOptions1 = '+angular.toJson(vm.clonedGridOptions1));
        });


    vm.showPlats = function () {
        var selected = vm.gridApi.selection.getSelectedRows();
        $log.debug('showPtals = '+angular.toJson(selected));
        platFactory.sendPlat(selected)
            .success(function(result){
            $log.debug('platFactory.sendPlat result = '+result);
        });
    };


    vm.gridOptions1 = {
        paginationPageSizes: [25, 50, 75],
        paginationPageSize: 25,
        enableRowSelection: true,
        enableSelectAll: true,
        enableColumnMenus: false,
        enablePaginationControls: true,
        rowHeight: 35,
        onRegisterApi: function(gridApi) { //register grid data first within the gridOptions
            vm.gridApi = gridApi;
        },
        columnDefs: [
            //{ name: "index",displayName:'#', cellTemplate: '<div class="ui-grid-cell-contents">{{grid.renderContainers.body.visibleRowCache.indexOf(row)}}</div>'},
            { name: "cadastrNumber", displayName: "Кадастровий №" },
            { name: "area", displayName: "Площа" },
            { name: "propertyLaw", displayName: "Речове право №" },
            { name: "dateOfRegistration", displayName: "Дата регістрації" },
            { name: "reason", displayName: "Підстава"},
            { name: "propertyOfPropertyLaw", displayName: "Характеристики реч. права" },
            { name: "personOfPropertyLaw", displayName: "Субєкт реч. права" },
            { name: 'editScope', displayName: "Ред.", width: "80",
                cellTemplate:'<button class="btn btn-danger" ng-click="grid.appScope.plat.deleteRow(grid.renderContainers.body.visibleRowCache.indexOf(row))">{{row.getProperty(col.id)}}' +
                '<span class="glyphicon glyphicon-remove" ></span>' +
                '</button>'
            }
        ]
    };

    vm.addData = function() {
        vm.gridOptions1.data.unshift({
            "cadastrNumber": 111 ,
            "area": 121 ,
            "propertyLaw": "Moroni2",
            "dateOfRegistration": "Moroni2",
            "reason": "Moroni2",
            "propertyOfPropertyLaw": "Moroni2",
            "personOfPropertyLaw": "Moroni2"
        });
    };
    vm.saveChangedPlats = function() {
        $log.debug('saveChangedPlats = '+angular.toJson(vm.gridOptions1.data));
        platFactory.savePlat(vm.gridOptions1.data)
            .success(function(result){
                $log.debug('platFactory.sevePlat result = '+result);
            });
    };

    vm.cancelChangedPlats = function() {
        alert(vm.clonedGridOptions1);
        $log.debug('cancelChangedPlats = '+angular.toJson(vm.gridOptions1.data));
        vm.gridOptions1.data = vm.clonedGridOptions1;


        //platFactory.savePlat(vm.gridOptions1.data)
        //    .success(function(result){
        //        $log.debug('platFactory.sevePlat result = '+result);
        //    });
    };

    vm.deleteRow = function(row) {
        $log.debug('row = '+row);
        $log.debug('data = '+data);
        vm.gridOptions1.data.splice(row, 1);
    };

    //vm.getTableHeight = function() {
    //    var rowHeight = 35; // your row height
    //    var headerHeight = 65; // your header height
    //    return {
    //        height: (vm.gridOptions1.data.length * rowHeight + headerHeight) + "px"
    //    };
    //};
}
})();