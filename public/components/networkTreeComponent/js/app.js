var app = angular.module('tree', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('{!!');
    $interpolateProvider.endSymbol('!!}');
});