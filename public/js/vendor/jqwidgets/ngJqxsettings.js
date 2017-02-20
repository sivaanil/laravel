angular.module("ngJqxsettings", []).directive('ngJqxsettings', function ($interval, dateFilter) {
    function link(scope, element, attrs) {
        var widget = attrs.ngJqwidgets;
        var propertyToScopeArray = propertyToScope(attrs.ngJqxsettings);

        for (var p = 0; p < propertyToScopeArray.length; p++) {
            var property = propertyToScopeArray[p].scopeVar;
            eval("var " + property + " = scope." + property + ";");

            // watches for changes in the $scope variables and updates the widget accordingly
            scope.$watch(property, function (newValue, oldValue) {
                if (newValue !== oldValue) {
                    var setting = this.exp;
                    eval(setting + " =   scope." + setting);

                    var updatedProperty;
                    for (var i = 0; i < propertyToScopeArray.length; i++) {
                        if (setting === propertyToScopeArray[i].scopeVar) {
                            updatedProperty = eval("({ " + propertyToScopeArray[i].jqwidgetsProperty + ": " + setting + " })");
                            break;
                        }
                        ;
                    }
                    ;
                    if (updatedProperty !== undefined) {
                        $(element[0])[widget](updatedProperty);
                    }
                    ;
                }
                ;
            });
        }
        ;

        // initializes the widget
        var settings = eval("(" + attrs.ngJqxsettings + ")");
        $(document).ready(function () {
            $(element[0])[widget](settings);
        });
    };

    return {
        link: link
    };
});

// extracts the key-value pairs from the settings string
var propertyToScope = function (settingsString) {
    var newString = settingsString.slice(1, settingsString.length - 1);
    newString = newString.trim();
    var keyValueArray = newString.split(",");
    var keyValueObjects = new Array();
    for (var i = 0; i < keyValueArray.length; i++) {
        var tempString = keyValueArray[i].trim();
        var tempArray = tempString.split(":");
        var scopeVar = tempArray[1].trim();
        if (isNaN(scopeVar) === true && scopeVar != "true" && scopeVar != "false" && scopeVar[0] != "\"" && scopeVar[0] != "\'") {
            keyValueObjects.push({jqwidgetsProperty: tempArray[0].trim(), scopeVar: tempArray[1].trim()});
        }
        ;
    }
    ;

    return keyValueObjects;
};