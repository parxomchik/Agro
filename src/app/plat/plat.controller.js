(function() {
    'use strict';

angular
    .module('agro')
    .controller('platController', platController);

/** @ngInject */
function platController($log,platFactory,NgTableParams,ngTableParams,$scope,$http) {

    $log.debug("platController start");
    var self = this;
    var data = [];
    //platFactory.getPlat()
    //    .success(function (result, status) {
    //        $log.debug('platFactory.getPlat() success = '+angular.toJson(result));
    //        data = result;
    //        $log.debug('data success = '+angular.toJson(data));
    //        self.tableParams = new NgTableParams({
    //            page: 1,            // show first page
    //            count: 10           // count per page
    //        }, {
    //            total: data.length, // length of data
    //            getData: function($defer, params) {
    //                //params.total();
    //                $defer.resolve(data.slice((params.page() - 1) * params.count(), params.page() * params.count()));
    //                //$defer.resolve($scope.dataset.slice((params.page() - 1) * params.count(), params.page() * params.count()));
    //            }
    //        });
    //    });
    //$http.get("app/plat/data2.json").success(function(result){
    platFactory.getPlat()
        .success(function (result, status) {

        //$scope.dataset = data;
        data = result;

        self.tableParams = new ngTableParams({
            page: 1,            // show first page
            count: 10           // count per page
        }, {
            total: data.length, // length of data
            getData: function($defer, params) {
                //params.total();
                $defer.resolve(data.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                //$defer.resolve($scope.dataset.slice((params.page() - 1) * params.count(), params.page() * params.count()));

            }
        });
    });


    //$http.get("data.json").success(function(result){
    //    //$scope.dataset = data;
    //    data = result;
    //
    //    self.tableParams = new NgTableParams({
    //        page: 1,            // show first page
    //        count: 10           // count per page
    //    }, {
    //        total: data.length, // length of data
    //        getData: function($defer, params) {
    //            //params.total();
    //            $defer.resolve(data.slice((params.page() - 1) * params.count(), params.page() * params.count()));
    //            //$defer.resolve($scope.dataset.slice((params.page() - 1) * params.count(), params.page() * params.count()));
    //        }
    //    });
    //});



    //var data = [
    //    {
    //        cadastrNumber: 111,
    //        area: 121,
    //        propertyLaw: "Moroni",
    //        dateOfRegistration: "Moroni",
    //        reason: "Moroni",
    //        propertyOfPropertyLaw: "Moroni",
    //        personOfPropertyLaw: "Moroni",
    //    },
    //    {
    //        cadastrNumber: 111,
    //        area: 122,
    //        propertyLaw: "Moroni",
    //        dateOfRegistration: "Moroni",
    //        reason: "Moroni",
    //        propertyOfPropertyLaw: "Moroni",
    //        personOfPropertyLaw: "Moroni",
    //    },
    //    {
    //        cadastrNumber: 111,
    //        area: 123,
    //        propertyLaw: "Moroni",
    //        dateOfRegistration: "Moroni",
    //        reason: "Moroni",
    //        propertyOfPropertyLaw: "Moroni",
    //        personOfPropertyLaw: "Moroni",
    //    }
    //];
    //var Api = $resource("app/plat/data.json");
    //this.tableParams = new NgTableParams({
    //    page: 1,            // show first page
    //    count: 10          // count per page
    //}, {
    //    getData: function(params) {
    //        // ajax request to api
    //        return Api.get(params.url())
    //            .$promise.then(function(data) {
    //                $log.debug(data);
    //                $log.debug('data.result = '+data.results);
    //            params.total(data.inlineCount); // recal. page nav controls
    //                $log.debug(data.inlineCount);
    //            return data.results;
    //        });
    //    }
    //});


//    self.tableParams = new NgTableParams(
//        //{ count: 5},
//        //
//        //{ counts: [5, 10, 25],
//        //total: data.length,
//        //    dataset: data
//        //});
}
})();