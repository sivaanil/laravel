var app = angular.module('menu', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('{!!');
    $interpolateProvider.endSymbol('!!}');
});