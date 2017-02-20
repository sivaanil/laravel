var app = angular.module('gridwithfilters', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('{!!');
    $interpolateProvider.endSymbol('!!}');
});