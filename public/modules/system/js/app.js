var app = angular.module('system', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('{!!');
    $interpolateProvider.endSymbol('!!}');
});