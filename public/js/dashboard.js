function _createElements($nodeId) {
    $.ajax({
        type: 'GET',
        url: "/tickets/unack",
        data: {nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var unackSettings = {
                title: '',
                description: '',
                padding: {left: 5, top: 25, right: 5, bottom: 5},
                enableAnimations: true,
                legendLayout: {left: '100%', top: 0},
                source: dataAdapter,
                categoryAxis: {
                    dataField: 'less_time',
                    type: 'basic',
                    description: 'Time',
                    textRotationAngle: -55,
                    showGridLines: false
                },
                colorScheme: 'scheme01',
                seriesGroups: [
                    {
                        type: 'column',
                        columnsGapPercent: 30,
                        seriesGapPercent: 0,
                        valueAxis: {
                            description: 'Tickets'
                        },
                        series: [
                            {dataField: 'day_tickets', displayText: 'Days'},
                            {dataField: 'hour_tickets', displayText: 'Hours'}
                        ]
                    }
                ]
            };

            // Create the charts
            $('#unackContainer').jqxChart(unackSettings);
        }
    });

    $.ajax({
        type: 'GET',
        url: "/tickets/unresolved",
        data: {nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var unresSettings = {
                title: '',
                description: '',
                padding: {left: 5, top: 25, right: 5, bottom: 5},
                enableAnimations: true,
                legendLayout: {left: '100%', top: 0},
                source: dataAdapter,
                categoryAxis: {
                    dataField: 'less_time',
                    type: 'basic',
                    description: 'Time',
                    textRotationAngle: -55,
                    showGridLines: false
                },
                colorScheme: 'scheme04',
                seriesGroups: [
                    {
                        type: 'column',
                        columnsGapPercent: 30,
                        seriesGapPercent: 0,
                        valueAxis: {
                            description: 'Tickets'
                        },
                        series: [
                            {dataField: 'day_tickets', displayText: 'Days'},
                            {dataField: 'hour_tickets', displayText: 'Hours'}
                        ]
                    }
                ]
            };

            // Create the charts
            $('#unresContainer').jqxChart(unresSettings);
        }
    });

    $.ajax({
        type: 'GET',
        url: "/tickets/overdue",
        data: {nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var overSettings = {
                title: '',
                description: '',
                padding: {left: 5, top: 25, right: 5, bottom: 5},
                enableAnimations: true,
                legendLayout: {left: '100%', top: 0},
                source: dataAdapter,
                categoryAxis: {
                    dataField: 'less_time',
                    type: 'basic',
                    description: 'Time',
                    textRotationAngle: -55,
                    showGridLines: false
                },
                colorScheme: 'scheme02',
                seriesGroups: [
                    {
                        type: 'column',
                        columnsGapPercent: 30,
                        seriesGapPercent: 0,
                        valueAxis: {
                            description: 'Tickets'
                        },
                        series: [
                            {dataField: 'day_tickets', displayText: 'Days'},
                            {dataField: 'hour_tickets', displayText: 'Hours'}
                        ]
                    }
                ]
            };

            // Create the charts
            $('#overContainer').jqxChart(overSettings);
        }
    });

    $.ajax({
        type: 'GET',
        url: "/tickets/wait",
        data: {nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var waitSettings = {
                title: '',
                description: '',
                padding: {left: 5, top: 25, right: 5, bottom: 5},
                enableAnimations: true,
                legendLayout: {left: '100%', top: 0},
                source: dataAdapter,
                categoryAxis: {
                    dataField: 'less_time',
                    type: 'basic',
                    description: 'Time',
                    textRotationAngle: -55,
                    showGridLines: false
                },
                colorScheme: 'scheme07',
                seriesGroups: [
                    {
                        type: 'column',
                        columnsGapPercent: 30,
                        seriesGapPercent: 0,
                        valueAxis: {
                            description: 'Tickets'
                        },
                        series: [
                            {dataField: 'day_tickets', displayText: 'Days'},
                            {dataField: 'hour_tickets', displayText: 'Hours'}
                        ]
                    }
                ]
            };

            // Create the charts
            $('#waitContainer').jqxChart(waitSettings);
        }
    });

    var initAverage = function () {
        $.ajax({
            type: 'GET',
            url: "/tickets/average",
            data: {nodeId: $nodeId},
            success: function (result) {
                var obj = result;
                var resultData = $.parseJSON(obj);
                var source =
                {
                    datatype: "json",
                    localdata: resultData,
                    datafields: [
                        {name: 'metricName'},
                        {name: 'number'}
                    ]
                };

                var dataAdapter = new $.jqx.dataAdapter(source);

                $("#averageContainer").jqxGrid({
                    source: dataAdapter,
                    showheader: false,
                    height: '100%',
                    width: '99%',
                    columns: [
                        {text: 'Metric', dataField: 'metricName', width: '65%'},
                        {text: 'Number', dataField: 'number', width: '35%'}
                    ]
                });
            }
        });
    };

    var initPriority = function () {
        $.ajax({
            type: 'GET',
            url: "/tickets/priority",
            data: {nodeId: $nodeId},
            success: function (result) {
                var obj = result;
                var resultData = $.parseJSON(obj);
                var source =
                {
                    datatype: "json",
                    localdata: resultData,
                    datafields: [
                        {name: 'metricName'},
                        {name: 'number'}
                    ]
                };

                var dataAdapter = new $.jqx.dataAdapter(source);

                $("#priorityContainer").jqxGrid({
                    source: dataAdapter,
                    showheader: false,
                    height: '100%',
                    width: '99%',
                    columns: [
                        {text: 'Metric', dataField: 'metricName', width: '65%'},
                        {text: 'Number', dataField: 'number', width: '35%'}
                    ]
                });
            }
        });
    };

    var initStatus = function () {
        $.ajax({
            type: 'GET',
            url: "/tickets/status",
            data: {nodeId: $nodeId},
            success: function (result) {
                var obj = result;
                var resultData = $.parseJSON(obj);
                var source =
                {
                    datatype: "json",
                    localdata: resultData,
                    datafields: [
                        {name: 'metricName'},
                        {name: 'number'}
                    ]
                };

                var dataAdapter = new $.jqx.dataAdapter(source);

                $("#statusContainer").jqxGrid({
                    source: dataAdapter,
                    showheader: false,
                    height: '100%',
                    width: '99%',
                    columns: [
                        {text: 'Metric', dataField: 'metricName', width: '65%'},
                        {text: 'Number', dataField: 'number', width: '35%'}
                    ]
                });
            }
        });
    };

    var initUser = function () {
        $.ajax({
            type: 'GET',
            url: "/tickets/user",
            data: {nodeId: $nodeId},
            success: function (result) {
                var obj = result;
                var resultData = $.parseJSON(obj);
                var source =
                {
                    datatype: "json",
                    localdata: resultData,
                    datafields: [
                        {name: 'metricName'},
                        {name: 'number'}
                    ]
                };

                var dataAdapter = new $.jqx.dataAdapter(source);

                $("#userContainer").jqxGrid({
                    source: dataAdapter,
                    showheader: false,
                    height: '100%',
                    width: '99%',
                    columns: [
                        {text: 'Metric', dataField: 'metricName', width: '65%'},
                        {text: 'Number', dataField: 'number', width: '35%'}
                    ]
                });
            }
        });
    };

    var initPolicy = function () {
        $.ajax({
            type: 'GET',
            url: "/tickets/policy",
            data: {nodeId: $nodeId},
            success: function (result) {
                var obj = result;
                var resultData = $.parseJSON(obj);
                var source =
                {
                    datatype: "json",
                    localdata: resultData,
                    datafields: [
                        {name: 'ticket_id'},
                        {name: 'subject'},
                        {name: 'policy_name'},
                        {name: 'total_tte'}
                    ]
                };

                var dataAdapter = new $.jqx.dataAdapter(source);

                $("#policyContainer").jqxGrid({
                    source: dataAdapter,
                    showheader: true,
                    height: '100%',
                    width: '99%',
                    columns: [
                        {text: 'Ticket ID', dataField: 'ticket_id', width: '15%'},
                        {text: 'Subject', dataField: 'subject', width: '40%'},
                        {text: 'Policy', dataField: 'policy_name', width: '15%'},
                        {text: 'Time Until Escalation', dataField: 'total_tte', width: '30%'}

                    ]
                });
            }
        });
    };

    var initContent = function (tab) {
        switch (tab) {
            case 0:
                initStatus();
                break;
            case 1:
                initPriority();
                break;
            case 2:
                initUser();
                break;
            case 3:
                initPolicy();
                break;
            case 4:
                initAverage();
                break;
        }
    };

    // Create the Panels
    $('#window1').jqxExpander({theme: 'custom', height: '50%', toggleMode: 'dblclick'});
    $('#window2').jqxExpander({theme: 'custom', height: '50%', toggleMode: 'dblclick'});
    $('#window3').jqxExpander({theme: 'custom', height: '50%', toggleMode: 'dblclick'});
    $('#window4').jqxExpander({theme: 'custom', height: '50%', toggleMode: 'dblclick'});
    $('#window5').jqxExpander({theme: 'custom', toggleMode: 'dblclick'});

    // Create the buttons
    $("#unackIntervalButton").jqxButton({});
    $("#unresIntervalButton").jqxButton({});
    $("#overIntervalButton").jqxButton({});
    $("#waitIntervalButton").jqxButton({});
    $("#metricsIntervalButton").jqxButton({});
    $("#unackIntervalButton").jqxTooltip({position: 'bottom', content: 'Click to select the interval for the data'});
    $("#unresIntervalButton").jqxTooltip({position: 'bottom', content: 'Click to select the interval for the data'});
    $("#overIntervalButton").jqxTooltip({position: 'bottom', content: 'Click to select the interval for the data'});
    $("#waitIntervalButton").jqxTooltip({position: 'bottom', content: 'Click to select the interval for the data'});
    $("#metricsIntervalButton").jqxTooltip({position: 'bottom', content: 'Click to select the interval for the data'});

    // Create the tabs
    $('#metricsTabs').jqxTabs({theme: 'custom', width: '99.5%', autoHeight: false, initTabContent: initContent});
}
function _addEventListeners() {

    // Adding Event Listeners for Graph Buttons
    $("#unackIntervalButton").bind('click', function () {
        $('#unackIntervalWindow').jqxWindow('open');
    });
    $("#unresIntervalButton").bind('click', function () {
        $('#unresIntervalWindow').jqxWindow('open');
    });
    $("#overIntervalButton").bind('click', function () {
        $('#overIntervalWindow').jqxWindow('open');
    });
    $("#waitIntervalButton").bind('click', function () {
        $('#waitIntervalWindow').jqxWindow('open');
    });
    $("#metricsIntervalButton").bind('click', function () {
        $('#metricsIntervalWindow').jqxWindow('open');
    });
}
// Create the Popup Windows
function _createWindow($nodeId) {

    // Unacknowledged Tickets Intervals Window
    $('#unackIntervalWindow').jqxWindow({
        showCollapseButton: true,
        height: 115,
        width: 215,
        isModal: true,
        modalOpacity: 0.3,
        resizable: true,
        autoOpen: false,
        initContent: function () {
            $("#unackNum").jqxNumberInput({
                width: '100px',
                height: '25px',
                inputMode: 'simple',
                decimalDigits: '0',
                spinButtons: true,
                min: '0',
                max: '99'
            });

            // Adding Event Listeners
            $('#unackIntervalWindow').on('close', function () {
                $int = document.getElementById("unackNum").value;
                UnackClose($nodeId, $int);
            });
        }
    });

    // Unresolved Tickets Intervals Window
    $('#unresIntervalWindow').jqxWindow({
        showCollapseButton: true,
        height: 115,
        width: 215,
        isModal: true,
        modalOpacity: 0.3,
        resizable: true,
        autoOpen: false,
        initContent: function () {
            $("#unresNum").jqxNumberInput({
                width: '100px',
                height: '25px',
                inputMode: 'simple',
                decimalDigits: '0',
                spinButtons: true,
                min: '0',
                max: '99'
            });

            // Adding Event Listeners
            $('#unresIntervalWindow').on('close', function () {
                $int = document.getElementById("unresNum").value;
                UnresClose($nodeId, $int);
            });
        }
    });

    // Overdue Tickets Intervals Window
    $('#overIntervalWindow').jqxWindow({
        showCollapseButton: true,
        height: 115,
        width: 215,
        isModal: true,
        modalOpacity: 0.3,
        resizable: true,
        autoOpen: false,
        initContent: function () {
            $("#overNum").jqxNumberInput({
                width: '100px',
                height: '25px',
                inputMode: 'simple',
                decimalDigits: '0',
                spinButtons: true,
                min: '0',
                max: '99'
            });

            // Adding Event Listeners
            $('#overIntervalWindow').on('close', function () {
                $int = document.getElementById("overNum").value;
                OverClose($nodeId, $int);
            });
        }
    });

    // Waiting Tickets Intervals Window
    $('#waitIntervalWindow').jqxWindow({
        showCollapseButton: true,
        height: 115,
        width: 215,
        isModal: true,
        modalOpacity: 0.3,
        resizable: true,
        autoOpen: false,
        initContent: function () {
            $("#waitNum").jqxNumberInput({
                width: '100px',
                height: '25px',
                inputMode: 'simple',
                decimalDigits: '0',
                spinButtons: true,
                min: '0',
                max: '99'
            });

            // Adding Event Listeners
            $('#waitIntervalWindow').on('close', function () {
                $int = document.getElementById("waitNum").value;
                WaitClose($nodeId, $int);
            });
        }
    });

    // Ticketing Metrics Intervals Window
    $('#metricsIntervalWindow').jqxWindow({
        showCollapseButton: true,
        height: 140,
        width: 250,
        isModal: true,
        modalOpacity: 0.3,
        resizable: true,
        autoOpen: false,
        initContent: function () {
            $("#metricsNum").jqxNumberInput({
                width: '100px',
                height: '25px',
                inputMode: 'simple',
                decimalDigits: '0',
                spinButtons: true,
                min: '0',
                max: '99'
            });

            // Adding Event Listeners
            $('#metricsIntervalWindow').on('close', function () {
                $int = document.getElementById("metricsNum").value;
                MetricsClose($nodeId, $int);
            });
        }
    });
}
// Update the Unacknowledged Graph with new interval data
function UnackClose($nodeId, $int) {

    $.ajax({
        type: 'GET',
        url: "/tickets/unack",
        data: {int: $int, nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            $('#unackContainer').jqxChart({source: dataAdapter});
        }
    });
}
// Update the Unresolved Graph with new interval data
function UnresClose($nodeId, $int) {

    $.ajax({
        type: 'GET',
        url: "/tickets/unresolved",
        data: {int: $int, nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            $('#unresContainer').jqxChart({source: dataAdapter});
        }
    });
}
// Update the Overdue Graph with new interval data
function OverClose($nodeId, $int) {

    $.ajax({
        type: 'GET',
        url: "/tickets/overdue",
        data: {int: $int, nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            $('#overContainer').jqxChart({source: dataAdapter});
        }
    });
}
// Update the Waiting Graph with new interval data
function WaitClose($nodeId, $int) {

    $.ajax({
        type: 'GET',
        url: "/tickets/wait",
        data: {int: $int, nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'less_time', type: 'string'},
                    {name: 'day_tickets', type: 'int'},
                    {name: 'hour_tickets', type: 'int'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            $('#waitContainer').jqxChart({source: dataAdapter});
        }
    });
}
// Update the Ticketing Metrics with new interval data
function MetricsClose($nodeId, $int) {

    $.ajax({
        type: 'GET',
        url: "/tickets/average",
        data: {int: $int, nodeId: $nodeId},
        success: function (result) {
            var obj = result;
            var resultData = $.parseJSON(obj);
            var source =
            {
                datatype: "json",
                localdata: resultData,
                datafields: [
                    {name: 'metricName'},
                    {name: 'number'}
                ]
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#averageContainer").jqxGrid({source: dataAdapter});
        }
    });
}

