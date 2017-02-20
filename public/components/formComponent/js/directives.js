angular.module('form').directive('formComponent', ['$window', 'mainService', function ($window, mainService) {
    return {
        restrict: 'A',
        scope: true,
        link: function(scope, element, attr) {
            scope.dataUrl = attr.url;
            scope.loadFormData();
        }
    };
}]);

angular.module('form').directive('formValidationField', ['$compile', function ($compile) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            var el = angular.element('<div ng-show="formErrors.' + attr.formValidationField + '" ng-repeat="formError in formErrors.' + attr.formValidationField + '" class="form-field-error">' +
                    '{!!formError!!}' +
                '</div>');
            $compile(el)(scope);
            element.append(el);
        }
    };
}]);

angular.module('form').directive('formMessages', ['$compile', function ($compile) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            var el = angular.element('<div ng-show="formErrors.formError" class="form-errors">' + 
                    '<div class="form-error">{!!formErrors.formError!!}</div>' +
                    '</div>' +
                    '<div ng-show="formSuccess" class="form-success">' + 
                    'Your changes have been saved' + 
                    '</div>'                    
                    );
            $compile(el)(scope);
            element.append(el);
        }
    };
}]);