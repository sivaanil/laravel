angular.module('mainApp').factory('mainService', ['$rootScope', '$compile', '$filter',  function ($rootScope, $compile, $filter) {
    return {
        findParentWithTarget: function (calledBy, target) {
            var curLevel = calledBy;
            while (curLevel != null) {
                if (curLevel[target] !== undefined) {
                    return curLevel;
                } else {
                    if (curLevel.$parent !== undefined) {
                        curLevel = curLevel.$parent;
                    } else {
                        return false;
                    }
                }
            }
            return false;
        },
        setPreference: function (preferenceName, preferenceValue) {
        $.ajax({
            type: 'POST',
            url: baseUrl + '/setPreference',
            data: {name: preferenceName, value: preferenceValue},

            success: function (result) {
                //console.log(result);
            }
        });

         },
        getPreference: function (preferenceName, callback) {
            $.ajax({
                type: 'POST',
                url: baseUrl + '/getPreference',
                data: {name: preferenceName},

                success: callback
            });
        },
        loadPanel: function (panelId, url, onPanelLoad) {
            $('#'+panelId).html('');
            var getPanelData = $.get(url, function(data) {
                var $scope = $('#'+panelId).html(data).scope();
                $compile($('#'+panelId))($scope || $rootScope);
                $rootScope.$digest();
                if (typeof onPanelLoad !== 'undefined') {
                    onPanelLoad();
                }
            });
        },
        legibleDate: function (stringDate){
            if (stringDate == null || stringDate == '2000-01-01 00:00:00' || stringDate == '0000-00-00 00:00:00') {
                readable = "N/A";
            } else {
                readable = moment(stringDate).format("dddd, MMM Do, YYYY [at] h:mm A");
            }
            return readable;
        },
        launchWebInterface: function(url) {
            // the URL should be either an error message or a real URL,
            // but if for some unanticipated reason URL wasn't set at all, show a generic error
            if (typeof url == 'undefined' || url == null) {
                alert('Unable to launch the web interface.');
            }
            // if URL is an error message, alert the error message to user
            // TODO: change to friendly alert dialog, maybe with retry button
            else if(url.indexOf("Error: ") == 0) {
                alert(url);
            }
            // open the URL in a new window
            else {
                window.open(url, '_blank');
            }
        }
};

}]);