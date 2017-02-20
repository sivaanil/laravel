angular.module('grid').controller("gridCtrl", ['$scope', '$http', '$timeout', 'mainService', function ($scope, $http, $timeout, mainService) {
    $scope.gridInit = false;
    $scope.actualCount = false;
    $scope.fetchDataRequest = null;
    $scope.jqxWindowSettings = "{" +
        "width: '100%', resizable: true, isModal: false, autoOpen: false, modalOpacity: 0.3" +
        "}";

    $scope.initGridComponent = function () {
        //TODO replace the ajax call with angulars $http request
        nodeId = this.nodeId;
        dataUrl = this.dataUrl;
        $.ajax({
            type: 'POST',
            url: baseUrl + this.dataUrl,
            data: {nodeId: this.nodeId, isFirst: true, queryType: 'data'},

            success: function (result) {
                var obj = jQuery.parseJSON(result);
                $scope.urlData.isFirst = false; //Headers have been fetched so flip the flag so
                $scope.columnData = obj.columns;
                $scope.contentData = obj.data;
                $scope.fetchCount(nodeId, dataUrl);
                if (obj.class !== undefined) {
                    $scope.classString = obj.class;
                }
                if (obj.hasButtons !== undefined) {
                    $scope.hasButtons = obj.hasButtons;
                    $scope.menuList = $scope.processMenuList(obj.menuList);
                    $scope.pageSizeSelectedIndex = 1;//set this to the user pref defaulting it to the 25 option in the grid
                }
                $scope.initGrid(obj);
                $scope.showHideGridColumns();
                //Adjust the width of dynamic columns to fill space
                $scope.makeColumnsFillSpace();
                //resize and cb event were triggers put the cb back to checked because it was automatic
                $scope.initResizeCB();
                // load user preferences
                $scope.loadColumnPreferences();
                $scope.notesPopUpOpenButtonInit();
                $scope.initColumnWindow();
                $scope.initExcelExport();
                $scope.gridInit = true;
                // change to generic id?
                $('#alarmMenu').jqxMenu({mode: 'horizontal', autoSizeMainItems: true});
                $("#alarmMenu").css('visibility', 'visible');
                $('#alarmTabMenu').jqxMenu({mode: 'horizontal', autoSizeMainItems: true});
                $("#alarmTabMenu").css('visibility', 'visible');
                $('#tab-active-alarms').click();
            }
        });
    };

    $scope.initGrid = function (dataObject) {

        var columnData = dataObject.columns;

        var dataFieldDefs = this.defineDataFields(columnData);

        $scope.pageSizeOptions = ['10', '25', '50'];

        $scope.gridSource =
        {
            datatype: "array",
            datafields: dataFieldDefs,
            totalrecords: 10000000 // placeholder count until real count loads via ajax
        };

        var myDataAdapter = new $.jqx.dataAdapter($scope.gridSource);

        var columns = this.processColumnData(columnData);

        // load virtual data.
        var renderGridRows = function (params) {
            var data = $scope.fetchData(params.startindex, params.endindex, false);
            return data;
        };

        var initrowdetails = function (index, parentElement, gridElement, datarecord) {
            var tabsdiv = null;
            var information = null;
            tabsdiv = $($(parentElement).children()[0]);
            if (tabsdiv != null) {
                information = tabsdiv.find('.informationSection');
                var container = $('<div id="' + $scope.divId + 'rowDetails' + index + '"class="row" style=" max-width:100%; margin: 10px;">');
                container.appendTo($(information));
                $scope.populateRowDetails(container, datarecord, index);
            }
        };

        if (this.pageable) {
            $("#" + this.divId).on('pagechanged', function (evt) {
                $("#" + $scope.divId).jqxGrid({autorowheight: false, rowsheight: 62});
            });
        }

        $("#" + this.divId).jqxGrid(
            {
                width: '99%', //This is 99% because at 100% it makes a horizontal scroll bar appear which then makes a vertical scroll bar appear
                height: '100%',
                source: myDataAdapter,
                filterable: true,
                sortable: true,
                altrows: true,
                theme: 'custom',
                scrollmode: 'logical',
                enabletooltips: true,
                virtualmode: true,
                columnsreorder: this.disableDragAndDrop,
                rowdetails: true,
                columnsresize: true,
                rowdetailstemplate: {rowdetails: "<div style='margin: 10px; height: 180px;'><div class='informationSection'></div></div>"},
                initrowdetails: initrowdetails,
                selectionmode: 'multiplerowsextended',
                rendergridrows: renderGridRows,
                columns: columns,
                localization: $scope.gridLocalization,
                pagerrenderer: $scope.pagerrenderer,
                pagesizeoptions: $scope.pageSizeOptions,
                pagesize: 25,
                autoshowcolumnsmenubutton: false,
                updatefilterconditions: $scope.updatefilterconditions,
                ready: function () {
                },
                touchmode: false,
            });
        if (this.pageable) {
            var rendered = function (evt) {
                $("#" + $scope.divId).jqxGrid({autorowheight: false, rowsheight: 62});
                $("#" + $scope.divId).jqxGrid('hideloadelement');
                // a fix for Windows tablet not being able to bring up filter dialog on active filter
                $('.jqx-grid-column-filterbutton').on('pointerdown', function (event) {
                    $(event.target).parent().click();
                });
                // custom checkbox filters for severity - todo: move to generic filter component
                $('.jqx-grid-column-menubutton').on('touchstart click', function (event) {
                    if ($(event.target).parent().parent().children(':first-child').text() == 'Severity') {
                        setTimeout(function () {
                            // hide jQX filter
                            $('li.filter:visible').children().hide();
                            // add cloned filter list
                            $('#alarmsFilter_allpriorities_CB_list').clone().addClass('cloned-filter').css('padding', '10px 10px 10px 10px').appendTo('li.filter:visible');
                            // handle clicks
                            $('.cloned-filter input').click(function (event) {
                                var targetIndex = $(event.target).parent().parent().index() + 1;
                                $('#alarmsFilter_filters #alarmsFilter_allpriorities_CB_list li:nth-of-type(' + targetIndex + ') input').click();
                            });

                        }, 50);
                    } else {
                        $('.cloned-filter').remove();
                        $('li.filter').children().show();
                    }
                });
                // remove filter icon for Actions column
                $('.jqx-grid-column-header').each(function () {
                    if ($(this).find('span').text() == 'Actions') {
                        $(this).find('span').parent().parent().find('.jqx-icon-arrow-down').parent().remove();
                        $(this).find('span').parent().parent().find('.iconscontainer').remove();
                    } else {
                        // adjust label margin so that centering accounts for filter
                        $(this).find('span').css('margin-right', '20px');
                    }
                });
                $scope.initColumnWindowData();
                setHeight();
            };
            $("#" + this.divId).jqxGrid({
                //trigger row height it doesn't work for the initial page so just set it to 110 so it is very likely the scroll bar will appear unless users have a very tall screen
                /* autorowheight: true, */
                pageable: true,
                rowsheight: 110,
                rendered: rendered
            });

        }

        $("#" + this.divId).on("sort", function (event) {
            $scope.customSortFunction(event);
        });
        /*
         * This on Row expand is needed because after sorting the expansion areas arean't refreshed
         * The grid methods clear and refreshdata reset scroll position so as an alternative this function
         * clears out the div then redraws the area
         */
        $("#" + this.divId).on('rowexpand', function (event) {
            //$scope.customExpandFunction(event);

        });
        $("#" + this.divId).on("filter", function (event) {
            $scope.customFilterFunction(event);
        });

        $("#" + this.divId).on("columnreordered", function (event) {
            if (event.target.id == $scope.divId) {
                $("#alarmsGrid").jqxGrid('refresh');
                $scope.initColumnWindowData();
                $scope.saveColumnPreferences();
            }
        });

        $("#" + this.divId).on("columnresized", function (event) {
            if (event.target.id == $scope.divId) {
                $scope.uncheckResizeCB();
                $scope.saveColumnPreferences();
            }
        });

        if ($scope.hasButtons) {
            $("#" + this.divId).bind('cellclick', function (event) {
                if (event.args.datafield === "Actions") {
                    if (document.getElementById($scope.gridMenuContainer) !== null) {
                        $("#" + $scope.gridMenuContainer).jqxMenu('destroy');
                    }
                    $("#" + $scope.gridmenu).append("<div id = \"" + $scope.gridMenuContainer + "\"> </div>");
                    var source = [];

                    //var menuFlags = event.args.row.bounddata.menuItems;
                    // for now, just Launch Web Interface
                    // ... when we add more again, we'll need to switch this to AJAX load on click
                    var menuFlags = 1;
                    var iterations = 0;

                    //Populate the menu source with the items based on the bit flags.
                    while (menuFlags > 0) {
                        if (menuFlags % 2 === 1) {
                            //id isn't ideal since it isn't dynamic. Add a primary column to the def_grid table to indicate which is the primary
                            //Add a Buttons
                            if (event.args.row.bounddata.id !== undefined) {

                                source[source.length] = {
                                    label: $scope.menuList[iterations].display,
                                    id: $scope.menuList[iterations].item,
                                    value: event.args.row.bounddata.id
                                };
                            } else {
                                source[source.length] = {
                                    label: $scope.menuList[iterations].display,
                                    id: $scope.menuList[iterations].item
                                };
                            }
                        }
                        //bit shift to the right
                        menuFlags = menuFlags >> 1;
                        iterations++;
                    }

                    var menuWidth = 125;
                    $("#" + $scope.gridMenuContainer).jqxMenu({
                        source: source,
                        width: menuWidth,
                        autoCloseOnClick: true,
                        mode: "popup",
                        autoOpenPopup: false
                    });

                    $("#" + $scope.gridMenuContainer).on('itemclick', function (event) {
                        // Pass the action to the module's action handler
                        $scope.gridRowActionHandler.gridActionHandler(event.args.id, event.args.attributes["item-value"].value);
                    });
                    // get coordinates - default events
                    if (typeof event.args.originalEvent.pageX != 'undefined'
                        && event.args.originalEvent.pageX != 0) {
                        var top = event.args.originalEvent.pageY;
                        var left = event.args.originalEvent.pageX;
                    }
                    // get coordinates - alternate/Android events
                    else if (typeof event.args.originalEvent.originalEvent != 'undefined'
                        && typeof event.args.originalEvent.originalEvent.changedTouches != 'undefined') {
                        var top = event.args.originalEvent.originalEvent.changedTouches[0].clientY;
                        var left = event.args.originalEvent.originalEvent.changedTouches[0].clientX;
                    }
                    if (left > $(window).width() - menuWidth - 15) {
                        left = $(window).width() - menuWidth - 15;
                    }
                    // small wait so that any duplicate click/touch events don't trigger immediate close
                    setTimeout(function () {
                        $("#" + $scope.gridMenuContainer).jqxMenu('open', left + 5, top + 5);
                    }, 50);
                }
            });
        }
    };

    $scope.updatefilterconditions = function (type, defaultconditions) {
        var stringcomparisonoperators = ['EQUAL', 'DOES_NOT_CONTAIN', 'CONTAINS'];
        var numericcomparisonoperators = ['LESS_THAN', 'LESS_THAN_OR_EQUAL_TO', 'GREATER_THAN', 'GREATER_THAN_OR_EQUAL_TO', 'CONTAINS', 'EQUAL'];
        var datecomparisonoperators = ["NULL", 'LESS_THAN', 'GREATER_THAN'];
        var booleancomparisonoperators = ['EQUAL', 'NOT_EQUAL'];
        switch (type) {
            case 'stringfilter':
                return stringcomparisonoperators;
            case 'numericfilter':
                return numericcomparisonoperators;
            case 'datefilter':
                return datecomparisonoperators;
            case 'booleanfilter':
                return booleancomparisonoperators;
        }
    };

    $scope.updatecolumnselection = function (position, columns, column, $event) {
        // toggle selection of clicked
        if (typeof column.selectedClass === 'undefined' || !column.selectedClass) {
            column.selectedClass = 'selected';
        } else {
            column.selectedClass = null;
        }
        // deselect all others (allow only one to be selected at a time)
        angular.forEach(columns, function (value, key) {
            if (value != column) {
                value.selectedClass = null;
            }
        });
    };

    $scope.saveCustomFilterPreferences = function () {
        name = 'grid' + '_' + $scope.divId + '_custom_filters';
        mainService.setPreference(name, JSON.stringify(scope.$parent.filterList));
    };


    $scope.saveColumnPreferences = function () {
        var columnData = $("#alarmsGrid").jqxGrid('columns');
        var columnPreferences = {};
        for (index = 0; index < columnData.records.length; ++index) {
            if (columnData.records[index].displayfield) {
                columnPreferences[columnData.records[index].displayfield] = {};
                columnPreferences[columnData.records[index].displayfield].width = columnData.records[index].width;
                columnPreferences[columnData.records[index].displayfield].hidden = columnData.records[index].hidden;
                columnPreferences[columnData.records[index].displayfield].index = index;
            }
        }
        name = 'grid' + '_' + $scope.divId + '_columns';
        mainService.setPreference(name, JSON.stringify(columnPreferences));

    };

    $scope.loadColumnPreferences = function () {
        name = 'grid' + '_' + 'alarmsGrid' + '_columns';
        preference = mainService.getPreference(name, function (result) {
            if (result) {
                var columns = jQuery.parseJSON(result);
                for (var column in columns) {
                    $("#" + $scope.divId).jqxGrid('setcolumnproperty', column, 'width', columns[column].width);
                    $("#" + $scope.divId).jqxGrid('setcolumnproperty', column, 'hidden', columns[column].hidden);
                    $("#" + $scope.divId).jqxGrid('setcolumnindex', column, columns[column].index);
                }
            }
        });
    };

    $scope.resetColumnPreferences = function() {
        var columns = $scope.processColumnData($scope.columnData);

        $("#alarmsGrid").jqxGrid(
            {
                columns: columns
            });
        $scope.initColumnWindowData();
        $scope.saveColumnPreferences();
    }

    $scope.decreaseColumnSize = function (position, columns, column, $event) {
        var currentWidth = $("#alarmsGrid").jqxGrid('getcolumnproperty', column.datafield, 'width');
        var newWidth = currentWidth - 15;
        if (newWidth >= 20) {
            $("#alarmsGrid").jqxGrid('setcolumnproperty', column.datafield, 'width', newWidth);
        }
        $("#" + $scope.divid + '_resizeCB').attr('checked', false);
        $event.stopPropagation();
    };

    $scope.increaseColumnSize = function (position, columns, column, $event) {
        var currentWidth = $("#alarmsGrid").jqxGrid('getcolumnproperty', column.datafield, 'width');
        var newWidth = currentWidth + 15;
        $("#alarmsGrid").jqxGrid('setcolumnproperty', column.datafield, 'width', newWidth);
        $("#" + $scope.divid + '_resizeCB').attr('checked', false);
        $event.stopPropagation();
    };

    $scope.reorderColumnUp = function () {
        var prevField = null;
        angular.forEach($scope.selectedColumns, function (value, key) {
            if (typeof value.selectedClass !== 'undefined' && value.selectedClass == 'selected') {
                if (prevField) {
                    var prevIndex = $("#alarmsGrid").jqxGrid('getcolumnindex', prevField);
                    $("#alarmsGrid").jqxGrid('setcolumnindex', value.datafield, prevIndex);
                }
            }
            if (!value.hidden) {
                prevField = value.datafield;
            }
        }, $scope.divid);
        $scope.initColumnWindowData();
    };

    $scope.reorderColumnDown = function () {
        var currentField = null;
        var nextIndex = null;
        angular.forEach($scope.selectedColumns, function (value, key) {
            if (currentField && !nextIndex) {
                if (!value.hidden) {
                    nextIndex = $("#alarmsGrid").jqxGrid('getcolumnindex', value.datafield);
                }
            }
            if (typeof value.selectedClass !== 'undefined' && value.selectedClass == 'selected') {
                currentField = value.datafield;
            }
        }, $scope);
        if (nextIndex) {
            $("#alarmsGrid").jqxGrid('setcolumnindex', currentField, nextIndex);
        }
        $scope.initColumnWindowData();
    };

    $scope.addColumn = function () {
        // deselect all other displayed columns (only newly displayed will be selected)
        angular.forEach($scope.selectedColumns, function (value, key) {
            value.selectedClass = null;
        });
        // find the selected column and hide it
        angular.forEach($scope.availableColumns, function (value, key) {
            if (typeof value.selectedClass !== 'undefined' && value.selectedClass == 'selected') {
                $("#alarmsGrid").jqxGrid('showcolumn', value.datafield);
                $("#" + $scope.divid + '_resizeCB').attr('checked', false);
            }
        });
        $scope.initColumnWindowData();
        $scope.saveColumnPreferences();
    };

    $scope.removeColumn = function () {
        // deselect all other hidden columns (only newly hidden will be selected)
        angular.forEach($scope.availableColumns, function (value, key) {
            value.selectedClass = null;
        });
        // find the selected column and hide it
        angular.forEach($scope.selectedColumns, function (value, key) {
            if (typeof value.selectedClass !== 'undefined' && value.selectedClass == 'selected') {
                $("#alarmsGrid").jqxGrid('hidecolumn', value.datafield);
                $("#" + $scope.divid + '_resizeCB').attr('checked', false);
            }
        });
        $scope.initColumnWindowData();
        $scope.saveColumnPreferences();
    };

    $scope.pagerrenderer = function () {
        var element = $("<div style='margin-left: 10px; margin-top: 5px; width: 100%; height: 100%;'></div>");
        var datainfo = $("#" + $scope.divId).jqxGrid('getdatainformation');
        var paginginfo = datainfo.paginginformation;
        var theme = "custom";

        //init the elements
        var pageSizeLabel = $("<label style='padding: 0px; float: left; margin: 2px 1px; position: relative; top: 1px;'>" + $scope.gridLocalization.pagershowrowsstring + "</label>");
        var pageSizeDropDown = $("<div style='padding: 0px; float: left; margin: 1px 10px;'><div style='margin-left: 9px;'></div></div>");
        //TODO  replace selected Index with a user preference
        pageSizeDropDown.jqxComboBox({
            source: $scope.pageSizeOptions,
            selectedIndex: $scope.pageSizeSelectedIndex,
            enableBrowserBoundsDetection: true,
            autoDropDownHeight: true,
            width: 45,
            height: 16,
            theme: theme,
            autoComplete: false,
            searchMode: 'none',
            minLength: 1
        });

        var leftButton = $("<div class='left-button'><div class='jqx-icon-arrow-left'></div></div>");
        var rightButton = $("<div class='right-button'><div class='jqx-icon-arrow-right'></div></div>");

        var leftfirstButton = $("<div class='left-first-button'><div class='jqx-icon-arrow-first'></div></div>");
        var rightlastButton = $("<div class='right-last-button'><div class='jqx-icon-arrow-last'></div></div>");

        var curPage = paginginfo.pagenum + 1;

        var currentPageInput = $("<div class='current-page-input'><input type='text' id='input' value='" + curPage + "'/></div>");
        currentPageInput.jqxInput();

        var startlabel = $("<div class='start-label'></div>");
        startlabel.text(' ' + $scope.gridLocalization.pagergotopagestring);

        if ($scope.actualCount === false) {
            var endLabel = $("<div class='end-label page-count-loading'>");
            endLabel.text('');
            $(rightlastButton).addClass('disabled');
        } else {
            var endLabel = $("<div class='end-label'>");
            var pagesCount = Math.ceil($scope.actualCount / $("#" + $scope.divId).jqxGrid('pagesize'));
            endLabel.text(' ' + $scope.gridLocalization.pagerrangestring + ' ' + pagesCount);
            $(rightlastButton).removeClass('disabled');
        }
        var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        // handle changes in rows per page selection combo box
        $('body').on('keyup', '.jqx-combobox-input', function (event) {
            // enter key
            if (event.which == 13) {
                updatePageSize();
            } else { // 2 second delay in case user is still typing
                delay(function () {
                    updatePageSize();
                }, 2000);
            }
        });

        //Add the events
        pageSizeDropDown.on('change', function (event) {
            if (typeof event.isTrigger != "undefined") {
                updatePageSize();
            }

        });

        function updatePageSize() {
            //TODO save this as a user pref
            var newVal = $('.jqx-combobox-input').val();
            if (typeof newVal != 'undefined' && newVal != '' && newVal > 0) {
                // maximum rows per page
                if (newVal > 100) {
                    newVal = 100;
                }
                var foundIndex = pageSizeDropDown.jqxComboBox('getItemByValue', newVal);
                if (typeof foundIndex != 'undefined') { // in dropdown list
                    $scope.pageSizeSelectedIndex = parseInt(foundIndex.index);
                } else { // not in dropdown list - rebuild dropdown list
                    $scope.pageSizeOptions = ['10', '25', '50', newVal];
                    $scope.pageSizeSelectedIndex = 3;
                    pageSizeDropDown.jqxComboBox({
                        source: $scope.pageSizeOptions,
                        selectedIndex: $scope.pageSizeSelectedIndex
                    });
                    pageSizeDropDown.jqxComboBox('close');
                }
                $("#" + $scope.divId).jqxGrid('pagesize', parseInt(newVal));
            }
        }

        currentPageInput.on('change', function (event) {
            $scope.refreshDataIfCountChanged();
            $("#" + $scope.divId).jqxGrid('gotopage', (parseInt(event.target.value) - 1));
        });

        rightButton.click(function () {
            $scope.refreshDataIfCountChanged();
            $("#" + $scope.divId).jqxGrid('gotonextpage');
        });
        leftButton.click(function () {
            $scope.refreshDataIfCountChanged();
            $("#" + $scope.divId).jqxGrid('gotoprevpage');
        });
        rightlastButton.click(function () {
            if ($scope.actualCount === false) {

            } else {
                $scope.refreshDataIfCountChanged();
                var datainfo = $("#" + $scope.divId).jqxGrid('getdatainformation');
                var paginginfo = datainfo.paginginformation;
                $("#" + $scope.divId).jqxGrid('gotopage', paginginfo.pagescount);
            }
        });
        leftfirstButton.click(function () {
            $("#" + $scope.divId).jqxGrid('gotopage', 0);
        });
        //Create the bar
        pageSizeLabel.appendTo(element);
        pageSizeDropDown.appendTo(element);
        leftfirstButton.appendTo(element);
        leftButton.appendTo(element);
        startlabel.appendTo(element);
        currentPageInput.appendTo(element);
        endLabel.appendTo(element);
        rightButton.appendTo(element);
        rightlastButton.appendTo(element);

        return element;
    };

    $scope.customSortFunction = function (event) {
        //clear the current contents of the grid, if grid isn't dumpped the row details aren't updated after sorting
        var sortInfo = event.args.sortinformation;
        var direction = sortInfo.sortdirection.ascending ? "1" : "2";
        if (sortInfo.sortcolumn in $scope.gridSortList && $scope.gridSortList[sortInfo.sortcolumn] == direction) {
            //stop sorting loop if the sort object is the same.
            //console.log("Attempted Sort");
        } else {

            $scope.gridSortList = {}; //clearing out sort object if multicolumn sorting is desired remove this.
            // sort direction 1 asc 2 desc
            $scope.gridSortList[sortInfo.sortcolumn] = sortInfo.sortdirection.ascending ? "1" : "2";
            //dump local data set
            $scope.contentData = [];
            //Collapse all rows
            //this is resetting scroll position.
            $scope.hideAllExpandedRows();
            //fetch new data
            $scope.fetchData($scope.lastStart, $scope.lastEnd, true);
        }

        /*This block might be useful for multi column sorting
         * if( typeof $scope.gridSortList[column] != "undefined" ){
         $scope.gridSortList[column] = ++$scope.gridSortList[column]%3;
         }else{
         $scope.gridSortList = {}; //clearing out sort object if multicolumn sorting is desired remove this.
         $scope.gridSortList[column] = 1;
         }

         if($scope.gridSortList[column] == 0){
         //Remove sort
         delete $scope.gridSortList[column]
         }*/
    };

    /*
     $scope.customExpandFunction = function(event){
     var div = this.divId+"rowDetails"+event.args.rowindex;

     if(document.getElementById(div)!=null) {

     var data = $("#"+this.divId).jqxGrid('getrowdata', event.args.rowindex);
     var container = document.getElementById(div);
     //console.log(data);
     container.innerHTML = "";
     $scope.populateRowDetails(container, data, event.args.rowindex);
     //console.log("2nd opening");
     } else {
     //console.log("1nd opening");
     }
     this.expandedRows.push(event.args.rowindex);
     };
     */

    /*
     * Closes all expanded rows in the grid.
     * Not needed if refreshdata or clear are called on the grid.
     */
    $scope.hideAllExpandedRows = function () {
        var len = this.expandedRows.length;
        for (var i = 0; i < len; i++) {
            $("#" + this.divId).jqxGrid('hiderowdetails', this.expandedRows[i]);
        }
        this.expandedRows = [];
    };

    $scope.customFilterFunction = function (event) {
        $scope.gridFilterList = [];
        var filter = {};
        for (var i = 0; i < event.args.filters.length; i++) {
            filter = {};
            filter.column = event.args.filters[i].datafield;
            filter.data = event.args.filters[i].filter.getfilters();
            $scope.gridFilterList.push(filter);
        }
        $("#" + $scope.divId).jqxGrid('refreshData');
        $scope.contentData = [];
        //fetch new data
        //console.log("filter fetch");
        $scope.fetchData(0, 20, true);
        //console.log($scope.gridFilterList);
    };

    $scope.initExcelExport = function () {
        if ($scope.theMenuParent !== false) {
            $scope.theMenuParent.moveElementToBar($scope.divId + 'ExcelExportButton');
        }

        $('#alarmMenu').on('itemclick', function (event) {
            if (event.target.id == $scope.divId + 'ExcelExportButton') {
                var obj = {
                    nodeId: $scope.nodeId,
                    isFirst: false,
                    recordstartindex: 0,
                    recordendindex: 500,
                    pagesize: 9999999,
                    sortData: $scope.gridSortList,
                    filterData: $scope.gridFilterList,
                    filterGroups: $scope.filterGroups,
                    queryType: 'data'
                };
                var url = baseUrl + '/dataExport/alarm?' + $.param(obj);
                window.open(url, '_blank');
                //$('#'+$scope.divId+'ExcelExportPopup').jqxWindow('open');
            }
        });
    };

    $scope.initColumnWindow = function () {
        if ($scope.theMenuParent !== false) {
            $scope.theMenuParent.moveElementToBar($scope.divId + 'ColumnButton');
            $scope.theMenuParent.moveElementToBar($scope.divId + 'ResetColumnsButton');
        }

        $('#alarmMenu').on('itemclick', function (event) {
            if (event.target.id == $scope.divId + 'ColumnButton') {
                $('#' + $scope.divId + 'ColumnPopup').jqxWindow('open');
                $scope.resizeColumnWindow();
                $scope.positionColumnWindow();
            }
            if (event.target.id == $scope.divId + 'ResetColumnsButton') {
                $scope.resetColumnPreferences();
            }
        });
    };

    $scope.initColumnWindowData = function () {
        $scope.availableColumns = [];
        $scope.selectedColumns = [];
        var columnData = $("#alarmsGrid").jqxGrid('columns').records;
        var totalColumns = columnData.length;
        for (var i = 0; i < totalColumns; i++) {
            if (columnData[i].hidden) {
                $scope.availableColumns.push(columnData[i]);
            } else {
                $scope.selectedColumns.push(columnData[i]);
            }
        }
        if ($scope.$root.$$phase != '$apply' && $scope.$root.$$phase != '$digest') {
            $scope.$digest();
        }
    };

    $scope.resizeColumnWindow = function () {
        if ($(window).width() > 475) {
            $('#' + $scope.divId + 'ColumnPopup').jqxWindow({width: 478, height: 250});
        } else {
            $('#' + $scope.divId + 'ColumnPopup').jqxWindow({width: 280, height: 430});
        }
    };

    $scope.positionColumnWindow = function () {
        var winHeight = $(window).height();
        var winWidth = $(window).width();
        // horizontal centering
        var posX = (winWidth / 2) - ($('#' + $scope.divId + 'ColumnPopup').width() / 2) + $(window).scrollLeft();
        var posY = (winHeight / 2) - ($('#' + $scope.divId + 'ColumnPopup').height() / 2) + $(window).scrollTop();
        $('#' + $scope.divId + 'ColumnPopup').jqxWindow({position: {x: posX, y: posY}});
    };

    $scope.initResizeCB = function () {
        // move the resize CB to menu bar
        if ($scope.theMenuParent !== false) {
            $scope.theMenuParent.moveElementToBar(this.resizeCB + '_container');
        }
        // set initial state as checked
        $scope.checkEventResizeCb();
        $('#alarmMenu').liveFirst('itemclick', function (event) {
            if (event.target.id == 'alarmsGrid_resizeCB') {
                if ($scope.getResizeCbState()) {
                    $scope.checkEventResizeCb();
                }
            }
            else if (typeof event.target.firstChild != 'undefined'
                && event.target.firstChild != null
                && typeof event.target.firstChild.className != 'undefined'
                && event.target.firstChild.id == 'alarmsGrid_resizeCB') {
                event.target.firstChild.click();
            }
        });
    };

    $scope.checkEventResizeCb = function () {
        //The auto size functions trigger the resize events so prevent the cb from getting into an infinite loop
        if ($scope.resizeCBBeingChecked === false) {
            $scope.resizeCBBeingChecked = true;
            $scope.showHideGridColumns();
            $scope.makeColumnsFillSpace();
            //resize and cb event were triggers put the cb back to checked because it was automatic
            $scope.checkResizeCB();
            $scope.resizeCBBeingChecked = false;
        }
    };

    $scope.checkResizeCB = function () {
        $("#" + this.resizeCB).attr('checked', true);
    };

    $scope.uncheckResizeCB = function () {
        $("#" + this.resizeCB).attr('checked', false);
    };

    $scope.getResizeCbState = function () {
        return $("#" + this.resizeCB).prop('checked');
    };
    $scope.processMenuList = function (menuList) {
        var data = [];
        var keys = Object.keys(menuList);
        for (var i = 0; i < keys.length; i++) {
            data[menuList[keys[i]].flag] = {};
            data[menuList[keys[i]].flag].action = menuList[keys[i]].action;
            data[menuList[keys[i]].flag].display = menuList[keys[i]].display;
            data[menuList[keys[i]].flag].flag = menuList[keys[i]].flag;
            data[menuList[keys[i]].flag].item = menuList[keys[i]].item;
            data[menuList[keys[i]].flag].module = menuList[keys[i]].module;
            data[menuList[keys[i]].flag].order = menuList[keys[i]].order;
        }
        return data;
    };

    $scope.populateRowDetails = function (container, datarecord, index) {
        //console.log($scope.rowDetailsFormat);
        if ($scope.rowDetailsFormat == "foundationFormat") {
            this.foundationFormatRowDetails(container, datarecord);
        } else if ($scope.rowDetailsFormat == "tabsGrid") {
            this.tabsGridRowDetails(container, datarecord, index);
        } else if ($scope.rowDetailsFormat == "grid") {
            this.gridRowDetails(container, datarecord, index);
        }
    };

    $scope.processData = function (gridData, columnData) {
        var data = [];
        var totalRow = gridData.length;
        var totalColumns = columnData.length;
        for (var i = 0; i < totalRow; i++) {
            data[i] = Array();
            for (var j = 0; j < totalColumns; j++) {
                data[i][columnData[j].column_id] = gridData[i][columnData[j].column_id];
            }
        }
        return data;
    };

    $scope.defineDataFields = function (columnData) {

        var totalColumns = columnData.length;
        var datafieldsDef = Array();

        for (var i = 0; i < totalColumns; i++) {
            datafieldsDef[i] = {
                name: columnData[i].column_id,
                type: columnData[i].column_data_type
            };
        }

        return datafieldsDef;
    };

    $scope.fetchData = function (startIndex, endIndex, isChange) {
        // show the "Loading..." message in grid
        $("#" + $scope.divId).jqxGrid('showloadelement');

        var pageInfo;
        var range;
        var trimmedRange;

        // if something changed, clear the count and go to first page
        if (isChange) {
            $scope.actualCount = false;
            $("#" + $scope.divId).jqxGrid('gotopage', 0);
        }

        if (this.pageable) {
            pageInfo = $("#" + $scope.divId).jqxGrid('getdatainformation');
        }

        range = $scope.analyzeWindow(startIndex, endIndex, pageInfo);
        trimmedRange = $scope.trimRange(range, $scope.contentData);
        if (trimmedRange.end !== undefined) {
            range.start = trimmedRange.start;
            range.end = trimmedRange.end;
        }
        // don't let range end exceed count
        if ($scope.actualCount !== false && $scope.actualCount < range.end) {
            range.end = $scope.actualCount;
        }
        var pageSize = range.end - range.start;
        var returnArray = Array();

        this.lastStart = startIndex;
        this.lastEnd = endIndex;

        var fetchDataBool = $scope.fetchRequiredServer(range, $scope.contentData, startIndex, endIndex, $scope.gridSource.totalrecords);

        if (fetchDataBool && pageSize > 0) {
            var dataUrl = this.dataUrl;
            var nodeId = this.nodeId;

            // if there's an outstanding request that hasn't returned yet, abort it
            if ($scope.fetchDataRequest) {
                $scope.fetchDataRequest.abort();
            }
            $scope.fetchDataRequest = $.ajax({
                type: 'POST',
                url: baseUrl + dataUrl,
                data: {
                    nodeId: nodeId,
                    isFirst: false,
                    recordstartindex: range.start,
                    recordendindex: range.end,
                    pagesize: pageSize,
                    sortData: $scope.gridSortList,
                    filterData: $scope.gridFilterList,
                    filterGroups: $scope.filterGroups,
                    queryType: 'data'
                },
                success: function (result) {
                    if ($("#"+$scope.divId).length > 0) {
                        var data = jQuery.parseJSON(result);
                        for(var i =0; i<data.data.length; i++){
                            $scope.contentData[range.start+i] = data.data[i];
                        }

                        if(isChange) {
                            $scope.gridSource.totalrecords = 10000000; // placeholder count until real count loads via ajax
                            $scope.fetchCount(nodeId, dataUrl);
                        }

                        // if request returned fewer rows than requested, we've reached the end, set actual count
                        if (data.data.length < range.end-range.start) {
                            var actualCount = range.start + data.data.length;
                            $scope.updateCount(actualCount);
                            $scope.gridSource.totalrecords = actualCount;
                        }

                        $scope.gridSource.localdata = $scope.contentData.slice(startIndex, endIndex);

                        // passing "cells" to the 'updatebounddata' method will refresh only the cells values when the new rows count is equal to the previous rows count.
                        //$("#"+$scope.divId).jqxGrid('updatebounddata', 'cells');
                        $("#"+$scope.divId).jqxGrid('updatebounddata');

                        if($scope.pageable){
                            $("#"+$scope.divId).jqxGrid({ rowsheight: 62 });
                        }
                        $("#"+$scope.divId).jqxGrid('hideloadelement');
                        //$("#"+$scope.divId).jqxGrid('ensurerowvisible', startIndex);
                        return $scope.contentData.slice(startIndex, endIndex);
                        //updateMenu(searchNode);
                    }
                }
            });
        }
        //if(startIndex > 0){
        returnArray[startIndex] = $scope.contentData[startIndex];
        /*}else{
         returnArray[0] = null;
         }*/
        returnArray = returnArray.concat($scope.contentData.slice(startIndex + 1, endIndex));
        return returnArray;
    };

    $scope.fetchCount = function (nodeId, dataUrl) {
        if ($scope.fetchCountRequest) {
            $scope.fetchCountRequest.abort();
        }
        // counts
        $scope.fetchCountRequest = $.ajax({
            type: 'POST',
            url: baseUrl + dataUrl,
            data: {
                nodeId: nodeId,
                isFirst: false,
                sortData: $scope.gridSortList,
                filterData: $scope.gridFilterList,
                filterGroups: $scope.filterGroups,
                queryType: 'count'
            },
            success: function (result) {
                var data = jQuery.parseJSON(result);
                $scope.updateCount(data.count);

            }
        });
    };

    $scope.updateCount = function (count) {
        if ($("#"+$scope.divId).length > 0) {
            $scope.actualCount = count;
            $scope.gridSource.totalrecords = count;
            var pagesCount = Math.ceil(count / $("#"+$scope.divId).jqxGrid('pagesize'));
            $('.end-label').removeClass('page-count-loading');
            $('.end-label').text(' '+$scope.gridLocalization.pagerrangestring+' ' + pagesCount);
            $('.right-last-button').removeClass('disabled');
        }
    };

    // immediate update of bound data can cause undesired refresh / scroll position reset
    // so we only call this if we need it updated and only update if counts don't match
    $scope.refreshDataIfCountChanged = function () {
        if ($scope.actualCount !== false) {
            var datainfo = $("#" + $scope.divId).jqxGrid('getdatainformation');
            var rowscount = datainfo.rowscount;
            if ($scope.actualCount != rowscount) {
                // update bound data
                $("#" + $scope.divId).jqxGrid('updatebounddata');
            }
        }
    };

    $scope.analyzeWindow = function (start, end, pageInfo) {
        var begin;
        var finish;
        var startIndex;
        var endIndex;
        if (pageInfo !== undefined) {
            //Go back 1 page and make that be the starting index
            startIndex = (pageInfo.paginginformation.pagenum * pageInfo.paginginformation.pagesize) - pageInfo.paginginformation.pagesize;
            //get the current page + 3 more
            endIndex = startIndex + pageInfo.paginginformation.pagesize * 4;
        } else {
            //Go back 1 page and make that be the starting index
            startIndex = start - 20;
            //get the current page + 3 more
            endIndex = start + (end - start) * 4;
            if ((endIndex - startIndex) > 200 && end < 125) {
                endIndex = 126;
            }
        }
        //Get the lower number either the requested window or -1 page, making sure that it doesn't go below 0
        begin = Math.max(Math.min(start, startIndex), 0);
        finish = Math.max(end, endIndex);

        return {'start': begin, 'end': finish};
    };

    $scope.trimRange = function (range, data) {

        var len = range.end - range.start;
        var rs = range.start;
        var newStart;
        var newFinish;

        var i;
        for (i = 0; i < len; i++) {
            if (data[i + rs] === undefined) {
                if (newStart === undefined) {
                    newStart = i + rs;
                }
                newFinish = i + rs + 1;
            }
        }
        return {'start': newStart, 'end': newFinish};
    };

    $scope.fetchRequiredServer = function (range, data, viewStart, viewEnd, totalRecords) {
        //I don't care what your window is if there is no data to fetch beyond your starting point you can't have any
        if (range.start >= totalRecords && totalRecords != 0) {
            //return false;
        }
        //is the starting index defined
        if (data[range.start] === undefined) {
            return true;
        }
        var count = range.end - range.start;
        var rs = range.start;
        var undefinedCount = 0;
        for (var i = 0; i < count && undefinedCount / count < .25; i++) {
            if (data[rs + i] === undefined) {
                undefinedCount++;
            }
            //there is a row in the current view that is not set fetch data NOW!!
            if (rs + i > viewStart && rs + i < viewEnd && data[rs + i] === undefined) {
                return true;
            }
        }
        if (undefinedCount / count) {
            return true;
        } else {
            return false;
        }
    };

    $scope.capitalizeFirstLetter = function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    $scope.processColumnData = function (columnData) {

        var totalColumns = columnData.length;
        var columns = new Array(totalColumns);
        var minWidthSum = 0;
        var cellclass = function (row, columnfield, value, data) {
            //This takes the content of 1 column and adds that cells content to class of all cells in the row.
            //This allows for css to be consistant across the entire grid row
            var classString = '';
            if (columnfield === "Actions") {
                classString += " menu-cell";
            }
            if (columnfield === "severity") {
                classString += " severity-cell";
            }
            if (columnfield === "notes") {
                classString += " notes-cell";
            }
            if (columnfield === "description") {
                classString += " description-cell";
            }
            if (columnfield === "path") {
                if (typeof data.path !== 'undefined' && data.path !== null) {
                    var fontSize = 11;
                    if (data.path.length > 260) {
                        fontSize = 10;
                    } else if (data.path.length > 200) {
                        fontSize = 11;
                    } else if (data.path.length > 160) {
                        fontSize = 11;
                    }
                }
                classString += " path-cell cell-font-" + fontSize;
            }
            if (value === '') {
                classString += " empty-cell";
            }

            if (data[$scope.classString] !== undefined) {
                return data[$scope.classString].toLowerCase() + classString;
            } else {
                return classString;
            }
        };

        for (var i = 0; i < totalColumns; i++) {
            //This column should only be displayed in the details so don't add it to the grid
            if (columnData[i].column_detail_area_only == 1) {
                $scope.hiddenColumns.push(columnData[i]);
                continue;
            }
            if (columnData[i].column_visiable == 0) {
                hideCol = true;
            } else {
                hideCol = false
            }
            columns[i] = {
                text: columnData[i].column_display_text,
                align: columnData[i].column_header_alignment,
                cellsalign: columnData[i].column_cell_alignment,
                width: parseInt(columnData[i].column_min_width),
                minwidth: parseInt(columnData[i].column_min_width),
                datafield: columnData[i].column_id,
                filtertype: columnData[i].column_data_type,
                cellclassname: cellclass,
                hidden: hideCol
            };
            //console.log(hideCol);
            if (columnData[i].column_data_type === 'date') {
                columnData[i].cellsformat = columnData[i].column_date_format;
            }
            if (columnData[i].column_id === 'path') {
                columns[i].cellsrenderer = $scope.renderPathColumn;
            }
            minWidthSum += columns[i].minwidth;
            if (parseInt(columnData[i].column_static_width) === 1) {
                //rows that are static have their max width set.
                columns[i].maxwidth = parseInt(columnData[i].column_min_width);
            } else {
                //get non-static columns
                this.dynamicWidthColumns.push(columnData[i].column_id);
            }
        }
        if ($scope.hasButtons) {
            columns[columns.length] = {
                text: 'Actions',
                datafield: 'Actions',
                cellsrenderer: $scope.renderMenuColumn,
                cellclassname: cellclass,
                minwidth: 90,
                maxwidth: 90,
                align: "center",
                cellsalign: "center",
            };
        }
        return columns;
    };

    $scope.renderMenuColumn = function () {
        //return "<div style=\"display: table;\"> <img style=\"margin-left: 30%;  display: table-cell;  vertical-align:middle;\" src=\"../../../css/styles/images/icon-menu-minimized.png\"/> </div>";
        return "";
    };

    $scope.renderPathColumn = function (row, columnfield, value, defaulthtml, columnproperties) {
        var rowData = $("#" + $scope.divId).jqxGrid('getrowdata', row);
        var pathLink = '<a href="#/alarms/' + rowData.node_id + '">' + rowData.path + '</a>';
        return "<div style='overflow: hidden; text-overflow: ellipsis; padding-bottom: 2px; text-align: left; margin-right: 2px; margin-left: 4px; margin-top: 23px;' title='" + rowData.path + "'>" + pathLink + "</div>";
    };

    $scope.makeColumnsFillSpace = function () {

        var gridAnalysis = this.getGridAnalysis();
        if (gridAnalysis === false) {
            return;
        }
        var potentialSpace = gridAnalysis['potentialSpace'];
        var columnsToExpand = gridAnalysis['visibleDynamicColumnCount'];
        var dynamicVisibleColumns = gridAnalysis['visibleDynamicColumns'];

        //ie9(?) doesn't like fractional widths so this will truncate the fractional part.
        var spaceToAdd = Math.floor(potentialSpace / columnsToExpand) - 1; //subtracting 1 to attempt to prevent horizontal scrollbar at medium widths
        var newWidth;
        //Loop over the visiable dynamic columns to update the widths
        for (i = 0; i < columnsToExpand; i++) {
            newWidth = dynamicVisibleColumns[i].minWidth + spaceToAdd;
            if (newWidth >= dynamicVisibleColumns[i].minWidth) {
                $("#" + this.divId).jqxGrid('setcolumnproperty', dynamicVisibleColumns[i].id, 'width', newWidth);
            } else {
                $("#" + this.divId).jqxGrid('setcolumnproperty', dynamicVisibleColumns[i].id, 'width', dynamicVisibleColumns[i].minWidth);
            }
        }

    };

    $scope.windowResize = function () {

        if (this.getResizeCbState()) {
            this.showHideGridColumns();
            this.makeColumnsFillSpace();
            //resize and cb event were triggers put the cb back to checked because it was automatic
            this.checkResizeCB();
        }
    };

    $scope.showHideGridColumns = function () {
        //Has the user changed the columns?
        var grDet = this.getGridAnalysis();
        var minColumns = 3;
        if (grDet['potentialSpace'] < 0) {
            var deli = 1;
            var colToHide = grDet['visibleColumns'][grDet['visibleColumnsCount'] - deli];
            while (grDet['potentialSpace'] < 0 && grDet['visibleColumnsCount'] - deli > minColumns) {
                //Change Dropdown to reflect screen size
                if (colToHide.id !== 'Actions') {
                    $('.cdropdown-cb[value="' + colToHide.id + '"]').attr('checked', false);
                    //Remove Column
                    $("#" + $scope.divId).jqxGrid('hidecolumn', colToHide.id);
                    //Adjust the potentialSpace because there is more of it
                    grDet['potentialSpace'] += colToHide.minWidth;
                }
                deli++;
                colToHide = grDet['visibleColumns'][grDet['visibleColumnsCount'] - deli];
            }
        } else if (grDet['hiddenColumns'].length > 0 && grDet['potentialSpace'] >= grDet['hiddenColumns'][0].minWidth) { //is there space to add a column
            var addi = 0;
            var colToShow = grDet['hiddenColumns'][addi];
            while (addi < grDet['hiddenColumnsCount'] && grDet['potentialSpace'] >= colToShow.minWidth) {
                if (colToShow.id !== 'clear' || $('#alarmsFilter_cleared_CB').prop('checked') === true) {
                    //Change Dropdown to reflect screen size
                    $('.cdropdown-cb[value="' + colToShow.id + '"]').attr('checked', true);
                    //Add Column
                    $("#" + $scope.divId).jqxGrid('showcolumn', colToShow.id);
                    //Adjust the potentialSpace because there is less of it
                    grDet['potentialSpace'] -= colToShow.minWidth;
                }
                ++addi;
                colToShow = grDet['hiddenColumns'][addi];
            }
        }
    };

    /*
     * Loop over the grid object and get grid width data, column states (visiable, hidden, dynamic)
     */
    $scope.getGridAnalysis = function () {

        var analysisData = Array();
        if ($("#" + this.divId).jqxGrid('columns') === undefined) {
            return false;
        }
        var columnData = $("#" + this.divId).jqxGrid('columns').records;
        var totalColumns = columnData.length;
        var curWidthSum = 0;
        analysisData['gridWidth'] = $("#content" + this.divId).outerWidth();
        analysisData['columnCount'] = columnData.length;
        analysisData['visibleDynamicColumns'] = [];
        analysisData['visibleDynamicColumnCount'] = 0;
        analysisData['hiddenColumns'] = [];
        analysisData['hiddenColumnsCount'] = 0;
        analysisData['visibleColumns'] = [];
        analysisData['visibleColumnsCount'] = 0;

        for (var i = 0; i < totalColumns; i++) {

            if (!columnData[i].hidden) {
                analysisData['visibleColumns'].push({id: columnData[i].datafield, minWidth: columnData[i].minwidth});
                ++analysisData['visibleColumnsCount'];
                if (columnData[i].maxwidth === "auto" && columnData[i].resizable) {
                    curWidthSum += parseInt(columnData[i].minwidth);
                    analysisData['visibleDynamicColumns'].push({
                        id: columnData[i].datafield,
                        minWidth: columnData[i].minwidth
                    });
                    ++analysisData['visibleDynamicColumnCount'];
                } else {
                    curWidthSum += parseInt(columnData[i].width);
                }
            } else {
                analysisData['hiddenColumns'].push({id: columnData[i].datafield, minWidth: columnData[i].minwidth});
                ++analysisData['hiddenColumnsCount'];
            }
        }

        var spaceToAdd = (analysisData['gridWidth'] - curWidthSum);
        analysisData['columnMinWidthSum'] = curWidthSum;
        analysisData['potentialSpace'] = spaceToAdd;
        return analysisData;
    };

    $scope.foundationFormatRowDetails = function (container, datarecord) {

        var cellData;
        var i = 0;
        for (var prop in datarecord) {
            if (!datarecord.hasOwnProperty(prop)) {
                //The current property is not a direct property of datarecord it could be a prototype prop
                continue;
            }
            cellData = "<div class=\"col-xs-12 col-sm-6 col-md-4\" >" +
                "<div style=\"float:left; margin-right:5px; display: table;\">" +
                "<div style=\"vertical-align:middle; width:100%; display: table-cell; word-break: break-all;\"><label style\"font-weight:bold\">" + $scope.capitalizeFirstLetter(prop) + ":</label></div>" +
                "</div>" +
                "<div>" +
                "<div  style=\"display: table; \">" +
                "<div style=\"vertical-align:middle; width:100%; display: table-cell; word-break: normal; white-space: normal;\"><label style=\"display: table-cell; font-weight:normal; word-break: normal; white-space: normal\" >" + datarecord[prop] + "</label></div>" +
                "</div>" +
                "</div>" +
                "</div>";
            i++;
            var clearClass = "clearfix visible-xs";
            if (i % 2 === 0) clearClass += " visible-sm";
            if (i % 3 === 0) clearClass += " visible-md";
            var clearDiv = "<div class=\"" + clearClass + "\" ></div>";

            $(container).append(cellData);
            $(container).append(clearDiv);
        }
        $(container).append('</div>');
    };

    $scope.tabsGridRowDetails = function (container, datarecord, rowIndex) {

        var cellData = [];
        var detailDiv;
        for (var prop in datarecord) {
            if (!datarecord.hasOwnProperty(prop)) {
                //The current property is not a direct property of datarecord it could be a prototype prop
                continue;
            }
            var row = {};
            row["col"] = $scope.capitalizeFirstLetter(prop);
            row["data"] = datarecord[prop];
            cellData.push(row);

        }
        var rowdetailsheight = $("#" + this.divId).jqxGrid('rowdetailstemplate').rowdetailsheight - 20;
        //console.log(rowdetailsheight);

        //detailDiv = "<div id=\""+this.divId+"RowGridScrollBar"+rowIndex+"\"style=\"overflow:auto; height:"+rowdetailsheight+"px; width:100%; \"><div id=\""+this.divId+"rowGrid"+rowIndex+"\" ></div> </div>";
        detailDiv = "<div id=\"" + this.divId + "RowGridScrollBar" + rowIndex + "\"style=\"width:100%; \"><div id=\"" + this.divId + "rowGrid" + rowIndex + "\" ></div> </div>";
        $(container).append(detailDiv);
        $(container).append('</div>');

        var gridSource =
        {
            datatype: "array",
            localdata: cellData
        };

        var myDataAdapter = new $.jqx.dataAdapter(gridSource);

        $("#" + this.divId + "rowGrid" + rowIndex).jqxGrid({
            theme: 'custom',
            altrows: true,
            width: '99%',
            height: rowdetailsheight,
            source: myDataAdapter,
            pageable: true,
            //autoheight: true,
            /* autorowheight: true, */
            columns: [{
                text: 'Field',
                datafield: 'col'
            }, {
                text: 'Data',
                datafield: 'data'
            }]
        });
        //resize the first column so that it takes up the correct amount of space all the extra space will be give to the 2nd data column
        $("#" + this.divId + "rowGrid" + rowIndex).jqxGrid('autoresizecolumn', 'col');
        //$("#"+this.divId+"RowGridScrollBar"+rowIndex).jqxScrollBar({ height: rowdetailsheight, vertical: true, theme:"custom"});
    };

    $scope.gridRowDetails = function (container, datarecord, rowIndex) {

        var cellData = [];
        var detailDiv;

        var columnCount = $("#" + this.divId).jqxGrid('columns').records.length;
        var viewableData = $("#" + this.divId).jqxGrid('columns').records;
        for (var i = 1; i < columnCount; i++) {
            if (viewableData[i].displayfield == "Actions") {
                //Skip the empty actions column
                continue
            }
            var row = {};
            row["col"] = $scope.capitalizeFirstLetter(viewableData[i].displayfield);
            row["data"] = datarecord[viewableData[i].datafield];
            cellData.push(row);
        }

        var hiddenColumnCount = $scope.hiddenColumns.length;
        for (var j = 0; j < hiddenColumnCount; j++) {
            var row = {};
            row["col"] = $scope.capitalizeFirstLetter($scope.hiddenColumns[j].column_display_text);
            row["data"] = datarecord[$scope.hiddenColumns[j].column_id];
            cellData.push(row);
        }
        var rowdetailsheight = $("#" + this.divId).jqxGrid('rowdetailstemplate').rowdetailsheight - 20;
        //console.log(rowdetailsheight);

        detailDiv = "<div id=\"" + this.divId + "RowGridScrollBar" + rowIndex + "\"style=\"overflow:auto; width:100%; \"><div id=\"" + this.divId + "rowGrid" + rowIndex + "\" ></div> </div>";
        //detailDiv = "<div id=\""+this.divId+"RowGridScrollBar"+rowIndex+"\"style=\"width:100%; \"><div id=\""+this.divId+"rowGrid"+rowIndex+"\" ></div> </div>";
        $(container).append(detailDiv);
        $(container).append('</div>');

        var gridSource =
        {
            datatype: "array",
            localdata: cellData
        };

        var myDataAdapter = new $.jqx.dataAdapter(gridSource);

        $("#" + this.divId + "rowGrid" + rowIndex).jqxGrid({
            theme: 'custom',
            //altrows: true,
            width: '99%',
            height: rowdetailsheight,
            source: myDataAdapter,
            //pageable: true,
            autoheight: true,
            autorowheight: true,
            columns: [{
                text: 'Field',
                datafield: 'col'
            }, {
                text: 'Data',
                datafield: 'data'
            }]
        });
        //resize the first column so that it takes up the correct amount of space all the extra space will be give to the 2nd data column
        $("#" + this.divId + "rowGrid" + rowIndex).jqxGrid('autoresizecolumn', 'col');
        //$("#"+this.divId+"RowGridScrollBar"+rowIndex).jqxScrollBar({ height: rowdetailsheight, vertical: true, theme:"custom"});
    };

    $scope.filterChange = function () {
        if ($("#"+$scope.divId).length > 0) {
            $scope.contentData = [];
            $scope.fetchData(0,20, true);
        }
    };

    $scope.notesPopUpOpenButtonInit = function () {

        $('#alarmsGrid').on('cellclick', function (event) {
            //alert('here');
            if (event.args.datafield === "notes" && event.args.value !== '') {
                event.preventDefault();
                event.stopPropagation();
                if ($('#notesWindow').length !== 0) {
                    $('#notesWindow').jqxWindow('destroy');
                }
                $('#mainAlarmDiv').append('<div id="notesWindow"><div id="notesWindowHeader">Notes</div><div id="notesWindowContent">' + nl2br(event.args.value) + '</div></div>');
                $('#notesWindow').jqxWindow({minWidth: '300px'});
            }
        });
    };

}]);

