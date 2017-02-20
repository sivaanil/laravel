


function sensor_init() {

       var loader = function() {
            window.sensorUI.ccGrid.relaod();
        }

    $('#sensorTabs').jqxTabs({
        width: '100%',
        height: '100%'
    });
    // TODO - temporary, until relays/analog are implemtned
    $('#sensorTabs').jqxTabs('removeAt', 2);
    $('#sensorTabs').jqxTabs('removeAt', 1);

    window.sensorUI = new SensorUI();
    window.sensorUI.init();
};


