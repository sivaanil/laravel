angular.module('system').controller("systemCtrl", ['$scope', '$interval', '$rootScope', '$window', '$compile', '$http', '$timeout', 'mainService', '$resource', function ($scope, $interval, $rootScope, $window, $compile, $http, $timeout, mainService, $resource) {
    $scope.systemLocalization = $window.systemLocalization;

    $scope.openRebootSystemWindow = function () {
        var url = baseUrl + "/modules/system/rebootdialog.html";
        onPanelLoad = function () {
            $('#rebootSystemWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 220,
                maxWidth: 420,
                minHeight: 220,
                minWidth: 300,
                height: 220,
                width: 300,
                showCloseButton: false,
                isModal: true,
                initContent: function () {
                }
            });
            $('#rebootSystemWindow').jqxWindow('open');
        };
        mainService.loadPanel('rebootSystemWindowContent', url, onPanelLoad);
    };

    $scope.closeRebootSystemWindow = function () {
        $('#rebootSystemWindow').jqxWindow('close');
    };

    $scope.exportValidationReport = function () {
        var url = baseUrl + '/dataExport/deviceInventory/' + $rootScope.$nodeId;
        window.open(url, '_blank');
    };

    $scope.submitForm = function (action) {
        $scope.$parent.formData = {'rebootConfirm' : $('#rebootConfirm').val()};
        $scope.$parent.submitForm();
        if (action == 'resetGuacamole') {
            $('.reboot-device-confirm-button').hide();
            $('#resetGuacamoleWindowContent p').text('The remote web interface is being reset. You may now close this window.');
        }
        else if (action == 'rebootSystem') {
            if ($('#rebootConfirm').val() == 'REBOOT') {
                $('.reboot-device-confirm-button').hide();
                $('#rebootSystemWindowContent .dialog-main-content').text('The device is now rebooting and will be unreachable until the reboot has completed.');
            } else {
                $('#rebootSystemWindowContent .type-reboot-prompt').css('color', 'red');
                alert('Please type REBOOT in the box to confirm.');
            }
        }
    };

    $scope.openResetGuacamoleWindow = function () {
        var url = baseUrl + "/modules/system/resetguacamoledialog.html";
        onPanelLoad = function () {
            $('#resetGuacamoleWindow').jqxWindow({
                showCollapseButton: false,
                maxHeight: 140,
                maxWidth: 420,
                minHeight: 140,
                minWidth: 300,
                height: 140,
                width: 300,
                showCloseButton: false,
                isModal: true,
                initContent: function () {
                }
            });
            $('#resetGuacamoleWindow').jqxWindow('open');
        };
        mainService.loadPanel('resetGuacamoleWindowContent', url, onPanelLoad);
    };

    $scope.closeResetGuacamoleWindow = function () {
        $('#resetGuacamoleWindow').jqxWindow('close');
    }

    $scope.updateTimezone = function(item) {
        $.ajax('system/settimezone', {
            method: 'POST',
            data: {
                timezone: item
            }
        });
    }


/*    $scope.timezoneOptions = [
                {
                    value: 'America/Adak',
                    label: 'America/Adak'
                },
                {
                    value: 'America/Anchorage',
                    label: 'America/Anchorage'
                },
                {
                    value: 'America/Anguilla',
                    label: 'America/Anguilla'
                },
                {
                    value: 'America/Antigua',
                    label: 'America/Antigua'
                },
                {
                    value: 'America/Araguaina',
                    label: 'America/Araguaina'
                },
                {
                    value: 'America/Argentina/Buenos_Aires',
                    label: 'America/Argentina/Buenos_Aires'
                },
                {
                    value: 'America/Argentina/Catamarca',
                    label: 'America/Argentina/Catamarca'
                },
                {
                    value: 'America/Argentina/Cordoba',
                    label: 'America/Argentina/Cordoba'
                },
                {
                    value: 'America/Argentina/Jujuy',
                    label: 'America/Argentina/Jujuy'
                },
                {
                    value: 'America/Argentina/La_Rioja',
                    label: 'America/Argentina/La_Rioja'
                },
                {
                    value: 'America/Argentina/Mendoza',
                    label: 'America/Argentina/Mendoza'
                },
                {
                    value: 'America/Argentina/Rio_Gallegos',
                    label: 'America/Argentina/Rio_Gallegos'
                },
                {
                    value: 'America/Argentina/Salta',
                    label: 'America/Argentina/Salta'
                },
                {
                    value: 'America/Argentina/San_Juan',
                    label: 'America/Argentina/San_Juan'
                },
                {
                    value: 'America/Argentina/San_Luis',
                    label: 'America/Argentina/San_Luis'
                },
                {
                    value: 'America/Argentina/Tucuman',
                    label: 'America/Argentina/Tucuman'
                },
                {
                    value: 'America/Argentina/Ushuaia',
                    label: 'America/Argentina/Ushuaia'
                },
                {
                    value: 'America/Aruba',
                    label: 'America/Aruba'
                },
                {
                    value: 'America/Asuncion',
                    label: 'America/Asuncion'
                },
                {
                    value: 'America/Atikokan',
                    label: 'America/Atikokan'
                },
                {
                    value: 'America/Bahia',
                    label: 'America/Bahia'
                },
                {
                    value: 'America/Bahia_Banderas',
                    label: 'America/Bahia_Banderas'
                },
                {
                    value: 'America/Barbados',
                    label: 'America/Barbados'
                },
                {
                    value: 'America/Belem',
                    label: 'America/Belem'
                },
                {
                    value: 'America/Belize',
                    label: 'America/Belize'
                },
                {
                    value: 'America/Blanc-Sablon',
                    label: 'America/Blanc-Sablon'
                },
                {
                    value: 'America/Boa_Vista',
                    label: 'America/Boa_Vista'
                },
                {
                    value: 'America/Bogota',
                    label: 'America/Bogota'
                },
                {
                    value: 'America/Boise',
                    label: 'America/Boise'
                },
                {
                    value: 'America/Cambridge_Bay',
                    label: 'America/Cambridge_Bay'
                },
                {
                    value: 'America/Campo_Grande',
                    label: 'America/Campo_Grande'
                },
                {
                    value: 'America/Cancun',
                    label: 'America/Cancun'
                },
                {
                    value: 'America/Caracas',
                    label: 'America/Caracas'
                },
                {
                    value: 'America/Cayenne',
                    label: 'America/Cayenne'
                },
                {
                    value: 'America/Cayman',
                    label: 'America/Cayman'
                },
                {
                    value: 'America/Chicago',
                    label: 'America/Chicago'
                },
                {
                    value: 'America/Chihuahua',
                    label: 'America/Chihuahua'
                },
                {
                    value: 'America/Costa_Rica',
                    label: 'America/Costa_Rica'
                },
                {
                    value: 'America/Creston',
                    label: 'America/Creston'
                },
                {
                    value: 'America/Cuiaba',
                    label: 'America/Cuiaba'
                },
                {
                    value: 'America/Curacao',
                    label: 'America/Curacao'
                },
                {
                    value: 'America/Danmarkshavn',
                    label: 'America/Danmarkshavn'
                },
                {
                    value: 'America/Dawson',
                    label: 'America/Dawson'
                },
                {
                    value: 'America/Dawson_Creek',
                    label: 'America/Dawson_Creek'
                },
                {
                    value: 'America/Denver',
                    label: 'America/Denver'
                },
                {
                    value: 'America/Detroit',
                    label: 'America/Detroit'
                },
                {
                    value: 'America/Dominica',
                    label: 'America/Dominica'
                },
                {
                    value: 'America/Edmonton',
                    label: 'America/Edmonton'
                },
                {
                    value: 'America/Eirunepe',
                    label: 'America/Eirunepe'
                },
                {
                    value: 'America/El_Salvador',
                    label: 'America/El_Salvador'
                },
                {
                    value: 'America/Fort_Nelson',
                    label: 'America/Fort_Nelson'
                },
                {
                    value: 'America/Fortaleza',
                    label: 'America/Fortaleza'
                },
                {
                    value: 'America/Glace_Bay',
                    label: 'America/Glace_Bay'
                },
                {
                    value: 'America/Godthab',
                    label: 'America/Godthab'
                },
                {
                    value: 'America/Goose_Bay',
                    label: 'America/Goose_Bay'
                },
                {
                    value: 'America/Grand_Turk',
                    label: 'America/Grand_Turk'
                },
                {
                    value: 'America/Grenada',
                    label: 'America/Grenada'
                },
                {
                    value: 'America/Guadeloupe',
                    label: 'America/Guadeloupe'
                },
                {
                    value: 'America/Guatemala',
                    label: 'America/Guatemala'
                },
                {
                    value: 'America/Guayaquil',
                    label: 'America/Guayaquil'
                },
                {
                    value: 'America/Guyana',
                    label: 'America/Guyana'
                },
                {
                    value: 'America/Halifax',
                    label: 'America/Halifax'
                },
                {
                    value: 'America/Havana',
                    label: 'America/Havana'
                },
                {
                    value: 'America/Hermosillo',
                    label: 'America/Hermosillo'
                },
                {
                    value: 'America/Indiana/Indianapolis',
                    label: 'America/Indiana/Indianapolis'
                },
                {
                    value: 'America/Indiana/Knox',
                    label: 'America/Indiana/Knox'
                },
                {
                    value: 'America/Indiana/Marengo',
                    label: 'America/Indiana/Marengo'
                },
                {
                    value: 'America/Indiana/Petersburg',
                    label: 'America/Indiana/Petersburg'
                },
                {
                    value: 'America/Indiana/Tell_City',
                    label: 'America/Indiana/Tell_City'
                },
                {
                    value: 'America/Indiana/Vevay',
                    label: 'America/Indiana/Vevay'
                },
                {
                    value: 'America/Indiana/Vincennes',
                    label: 'America/Indiana/Vincennes'
                },
                {
                    value: 'America/Indiana/Winamac',
                    label: 'America/Indiana/Winamac'
                },
                {
                    value: 'America/Inuvik',
                    label: 'America/Inuvik'
                },
                {
                    value: 'America/Iqaluit',
                    label: 'America/Iqaluit'
                },
                {
                    value: 'America/Jamaica',
                    label: 'America/Jamaica'
                },
                {
                    value: 'America/Juneau',
                    label: 'America/Juneau'
                },
                {
                    value: 'America/Kentucky/Louisville',
                    label: 'America/Kentucky/Louisville'
                },
                {
                    value: 'America/Kentucky/Monticello',
                    label: 'America/Kentucky/Monticello'
                },
                {
                    value: 'America/Kralendijk',
                    label: 'America/Kralendijk'
                },
                {
                    value: 'America/La_Paz',
                    label: 'America/La_Paz'
                },
                {
                    value: 'America/Lima',
                    label: 'America/Lima'
                },
                {
                    value: 'America/Los_Angeles',
                    label: 'America/Los_Angeles'
                },
                {
                    value: 'America/Lower_Princes',
                    label: 'America/Lower_Princes'
                },
                {
                    value: 'America/Maceio',
                    label: 'America/Maceio'
                },
                {
                    value: 'America/Managua',
                    label: 'America/Managua'
                },
                {
                    value: 'America/Manaus',
                    label: 'America/Manaus'
                },
                {
                    value: 'America/Marigot',
                    label: 'America/Marigot'
                },
                {
                    value: 'America/Martinique',
                    label: 'America/Martinique'
                },
                {
                    value: 'America/Matamoros',
                    label: 'America/Matamoros'
                },
                {
                    value: 'America/Mazatlan',
                    label: 'America/Mazatlan'
                },
                {
                    value: 'America/Menominee',
                    label: 'America/Menominee'
                },
                {
                    value: 'America/Merida',
                    label: 'America/Merida'
                },
                {
                    value: 'America/Metlakatla',
                    label: 'America/Metlakatla'
                },
                {
                    value: 'America/Mexico_City',
                    label: 'America/Mexico_City'
                },
                {
                    value: 'America/Miquelon',
                    label: 'America/Miquelon'
                },
                {
                    value: 'America/Moncton',
                    label: 'America/Moncton'
                },
                {
                    value: 'America/Monterrey',
                    label: 'America/Monterrey'
                },
                {
                    value: 'America/Montevideo',
                    label: 'America/Montevideo'
                },
                {
                    value: 'America/Montserrat',
                    label: 'America/Montserrat'
                },
                {
                    value: 'America/Nassau',
                    label: 'America/Nassau'
                },
                {
                    value: 'America/New_York',
                    label: 'America/New_York'
                },
                {
                    value: 'America/Nipigon',
                    label: 'America/Nipigon'
                },
                {
                    value: 'America/Nome',
                    label: 'America/Nome'
                },
                {
                    value: 'America/Noronha',
                    label: 'America/Noronha'
                },
                {
                    value: 'America/North_Dakota/Beulah',
                    label: 'America/North_Dakota/Beulah'
                },
                {
                    value: 'America/North_Dakota/Center',
                    label: 'America/North_Dakota/Center'
                },
                {
                    value: 'America/North_Dakota/New_Salem',
                    label: 'America/North_Dakota/New_Salem'
                },
                {
                    value: 'America/Ojinaga',
                    label: 'America/Ojinaga'
                },
                {
                    value: 'America/Panama',
                    label: 'America/Panama'
                },
                {
                    value: 'America/Pangnirtung',
                    label: 'America/Pangnirtung'
                },
                {
                    value: 'America/Paramaribo',
                    label: 'America/Paramaribo'
                },
                {
                    value: 'America/Phoenix',
                    label: 'America/Phoenix'
                },
                {
                    value: 'America/Port-au-Prince',
                    label: 'America/Port-au-Prince'
                },
                {
                    value: 'America/Port_of_Spain',
                    label: 'America/Port_of_Spain'
                },
                {
                    value: 'America/Porto_Velho',
                    label: 'America/Porto_Velho'
                },
                {
                    value: 'America/Puerto_Rico',
                    label: 'America/Puerto_Rico'
                },
                {
                    value: 'America/Rainy_River',
                    label: 'America/Rainy_River'
                },
                {
                    value: 'America/Rankin_Inlet',
                    label: 'America/Rankin_Inlet'
                },
                {
                    value: 'America/Recife',
                    label: 'America/Recife'
                },
                {
                    value: 'America/Regina',
                    label: 'America/Regina'
                },
                {
                    value: 'America/Resolute',
                    label: 'America/Resolute'
                },
                {
                    value: 'America/Rio_Branco',
                    label: 'America/Rio_Branco'
                },
                {
                    value: 'America/Santarem',
                    label: 'America/Santarem'
                },
                {
                    value: 'America/Santiago',
                    label: 'America/Santiago'
                },
                {
                    value: 'America/Santo_Domingo',
                    label: 'America/Santo_Domingo'
                },
                {
                    value: 'America/Sao_Paulo',
                    label: 'America/Sao_Paulo'
                },
                {
                    value: 'America/Scoresbysund',
                    label: 'America/Scoresbysund'
                },
                {
                    value: 'America/Sitka',
                    label: 'America/Sitka'
                },
                {
                    value: 'America/St_Barthelemy',
                    label: 'America/St_Barthelemy'
                },
                {
                    value: 'America/St_Johns',
                    label: 'America/St_Johns'
                },
                {
                    value: 'America/St_Kitts',
                    label: 'America/St_Kitts'
                },
                {
                    value: 'America/St_Lucia',
                    label: 'America/St_Lucia'
                },
                {
                    value: 'America/St_Thomas',
                    label: 'America/St_Thomas'
                },
                {
                    value: 'America/St_Vincent',
                    label: 'America/St_Vincent'
                },
                {
                    value: 'America/Swift_Current',
                    label: 'America/Swift_Current'
                },
                {
                    value: 'America/Tegucigalpa',
                    label: 'America/Tegucigalpa'
                },
                {
                    value: 'America/Thule',
                    label: 'America/Thule'
                },
                {
                    value: 'America/Thunder_Bay',
                    label: 'America/Thunder_Bay'
                },
                {
                    value: 'America/Tijuana',
                    label: 'America/Tijuana'
                },
                {
                    value: 'America/Toronto',
                    label: 'America/Toronto'
                },
                {
                    value: 'America/Tortola',
                    label: 'America/Tortola'
                },
                {
                    value: 'America/Vancouver',
                    label: 'America/Vancouver'
                },
                {
                    value: 'America/Whitehorse',
                    label: 'America/Whitehorse'
                },
                {
                    value: 'America/Winnipeg',
                    label: 'America/Winnipeg'
                },
                {
                    value: 'America/Yakutat',
                    label: 'America/Yakutat'
                },
                {
                    value: 'America/Yellowknife',
                    label: 'America/Yellowknife'
                }
            ];
*/
}]);
