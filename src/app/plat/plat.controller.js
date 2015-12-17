(function() {
    'use strict';

angular
    .module('agro')
    .controller('platController', platController)
    .controller('platController2', platController2);

/** @ngInject */
function platController($log,platFactory,NgTableParams,ngTableParams,$scope,$filter,i18nService) {

    $log.debug("platController start");
    var vm = this;
    var data = [];
    //$scope.langs = i18nService.getAllLangs();
    //$scope.lang = 'nl';

    //i18nService.setCurrentLang("it");

    vm.cols = [
        { field: "name", title: "Name", show: true },
        { field: "age", title: "Age", show: true },
        { field: "money", title: "Money", show: true }
    ];
    vm.myData = [
        {
            "firstName": "Cox",
            "lastName": "Carney",
            "company": "Enormo",
            "employed": true
        },
        {
            "firstName": "Lorraine",
            "lastName": "Wise",
            "company": "Comveyer",
            "employed": false
        },
        {
            "firstName": "Nancy",
            "lastName": "Waters",
            "company": "Fuelton",
            "employed": false
        }
    ];
    vm.gridOptions1 = {
        paginationPageSizes: [25, 50, 75],
        paginationPageSize: 25,
        enableRowSelection: true,
        enableSelectAll: true,
        enablePaginationControls: true,
        onRegisterApi: function(gridApi) { //register grid data first within the gridOptions
            vm.gridApi = gridApi;
        },
        columnDefs: [
            { name: "cadastrNumber", displayName: "Кадастровий №" },
            { name: "area", displayName: "Площа" },
            { name: "propertyLaw", displayName: "Речове право №" },
            { name: "dateOfRegistration", displayName: "Дата регістрації" },
            { name: "reason", displayName: "Підстава"},
            { name: "propertyOfPropertyLaw", displayName: "Характеристики реч. права" },
            { name: "personOfPropertyLaw", displayName: "Субєкт реч. права" },
            { name: 'editScope', displayName: "Ред.", width: "80",
                cellTemplate:'<button class="btn btn-danger" ng-click="grid.appScope.showMe()">' +
                '<span class="glyphicon glyphicon-remove" ></span>' +
                '</button>' }
        ]
    };
    vm.showPlats = function () {
        //var selected = vm.gridApi.selection.getSelectedRows();
        var selected = vm.gridApi.selection.getSelectedRows();
        $log.debug('showPtals = '+angular.toJson(selected));
    };
    platFactory.getPlat()
        .success(function (result, status) {
            $log.debug('ui-grid res = '+angular.toJson(result));
            vm.gridOptions1.data = result;
        });


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

    /** @ngInject */
    function platController2(NgTableParams,$log,platFactory,$scope,$filter) {

    $log.debug("platController2 start");
    var vm = this;
    var data = [];
        vm.cols = [
            { field: "isSelected", title: "#", show: true },
            { field: "cadastrNumber", title: "Кадастровий №",sortable: "cadastrNumber", show: true },
            { field: "area", title: "Площа",sortable: "area", show: true },
            { field: "propertyLaw", title: "Речове право №",sortable: "propertyLaw", show: true },
            { field: "dateOfRegistration", title: "Дата регістрації", sortable: "dateOfRegistration",show: true },
            { field: "reason", title: "Підстава",sortable: "reason", show: true },
            { field: "propertyOfPropertyLaw", title: "Характеристики реч. права",sortable: "propertyOfPropertyLaw", show: true },
            { field: "personOfPropertyLaw", title: "Субєкт реч. права",sortable: "personOfPropertyLaw", show: true },
            //{ field: "edit", title: "Редагування", show: true }
        ];


        //vm.cols = [
        //    { field: "name", title: "Name", show: true },
        //    { field: "age", title: "Age", show: true },
        //    { field: "money", title: "Money", show: true },
        //    { field: "honey", title: "Honey", show: true }
        //];

        //var data = [
        //    {"name": "Moroni", "age": 50,"money":20,"honey":12},
        //    //{"name": "Tiancum", "age": 43},
        //    //{"name": "Jacob", "age": 27},
        //    //{"name": "Nephi", "age": 29},
        //    //{"name": "Enos", "age": 34},
        //    //{"name": "Tiancum", "age": 43},
        //    //{"name": "Jacob", "age": 27},
        //    //{"name": "Nephi", "age": 29},
        //    //{"name": "Enos", "age": 34},
        //    //{"name": "Tiancum", "age": 43},
        //    //{"name": "Jacob", "age": 27},
        //    //{"name": "Nephi", "age": 29},
        //    //{"name": "Enos", "age": 34},
        //    //{"name": "Tiancum", "age": 43},
        //    //{"name": "Jacob", "age": 27},
        //    //{"name": "Nephi", "age": 29},
        //    //{"name": "Enos", "age": 34}
        //]  ;
        //
        //vm.tableParams = new NgTableParams({}, {
        //dataset: data
    //});


    //vm.showPtals = function(ptalsData){
    //    var res = [];
    //    $log.debug('showPtals = '+angular.toJson(ptalsData));
    //
    //    angular.forEach(ptalsData,function(value1, key1){
    //        $log.debug('value = '+angular.toJson(value1),'key ='+key1);
    //        angular.forEach(value1, function (value2, key2) {
    //            $log.debug('value2 = '+angular.toJson(value2),'key2 ='+key2);
    //            if(key2 == 'isSelected' &&  value2 == true){
    //                $log.debug('ok');
    //                $log.debug(value1);
    //                res.push(value1);
    //            }
    //        })
    //    });
    //    $log.debug('res = '+angular.toJson(res));
    //};
    //
    platFactory.getPlat2()
        .success(function (result, status) {
            data = result;
            vm.tableParams = new NgTableParams({
            page: 1,            // show first page
            //sorting: {
            //    name: ''     // initial sorting
            //}
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