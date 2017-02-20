var app = angular.module('alarms', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('{!!');
    $interpolateProvider.endSymbol('!!}');
});