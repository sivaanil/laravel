/*function showFilters() {
    if (!$('#filterToggle').prop('checked')) {
        $('#filters').hide();
    } else {
        $('#filters').show();
    }
}
*/
function sort(label) {
    //get the footable sort object
    //var footableSort = $('.footable').data('footable-sort');
    //console.log(label);
    //document.getElementById("alarmTable").rows[0].cells[e.column.index].id
    updateAlarmsWithSort(label, "ASC");
}
/*
function updateMet(id) {
    updateCK(id, 'allMet', ['polled', 'traps']);
    updateAlarms();
}
*/

/*
function updateSev(id) {

    updateCK(id, 'allSev', ['crit', 'maj', 'min', 'warn', 'info']);
    updateAlarms();
}
*/

/**
 * Updates checkboxes where allElm is the select/clear all element.
 * @param String id The ID of the elm selected/cleared.
 * @param String allElm The ID of the "all" checkbox
 * @param Array<String> elmsToCheck ID's of all subordinate elements.
 */
function updateCK(id, keyElm, elmsToCheck) {

    if (id == keyElm) {
        var checked = $('#' + keyElm).prop('checked');
        for (var i = 0; i < elmsToCheck.length; ++i) {
            $('#' + elmsToCheck[i]).prop('checked', checked);
        }
    } else {
        var allSubsChecked = true;
        for (var i = 0; i < elmsToCheck.length; ++i) {
            if (!$('#' + elmsToCheck[i]).prop('checked')) {
                allSubsChecked = false;
            }
        }
        $('#' + keyElm).prop('checked', allSubsChecked);
    }
}

/*
function updateSub(id) {

    updateCK(id, 'allState', ['acti', 'clea', 'ign', 'del']);
    updateAlarms();
}
*/

/*
function updateTime(id) {

    updateCK(id, 'allRals', ['1day', '17day', '2wk', '34wk', '1mon']);
    updateAlarms();
}
*/

/*
var waitForFinalEvent = (function () { //This is also in menuArea.blade.php move it to a common js lib
    var timers = {};
    return function (callback, ms, uniqueId) {
        if (!uniqueId) {
            uniqueId = "Don't call this twice without a uniqueId";
        }
        if (timers[uniqueId]) {
            clearTimeout(timers[uniqueId]);
        }
        timers[uniqueId] = setTimeout(callback, ms);
    };
})();
*/

/**
 * Replaces newline characters with <br /> tags.
 *
 * @param String str String to modify
 * @param Boolean is_xhtml flag for whether the string is HTML or XHTML
 *
 * @return String
 */
function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

