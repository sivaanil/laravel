<?php namespace Unified\Http\Controllers\general;

use DB;
use Input;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Models\DefFilterOptions;
use Unified\Models\FilterQuery;
use Unified\Models\DeviceAlarm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FilterController extends \BaseController
{
    public function getFilters($passedModule = null, $nodeId = null, $includeCounts = null)
    {

        $module = $passedModule != null ? $passedModule : (string) Input::get('module');
        $nodeId = $nodeId != null ? $nodeId : (string) Input::get('nodeId');
        $includeCounts = $includeCounts != null ? $includeCounts : (string) Input::get('includeCounts');
        $filters = $this->getFilterListing($module);
        //dd($filters);
        if ($includeCounts) {
            $filterCounts = $this->getFilterCounts($module, $filters, $nodeId);
            $this->appendFilterCounts($filters, $filterCounts);
        }
        //dd($filterCounts);
        $displayData = $this->formatFiltersToDisplay($filters);
        //var_dump($displayData);
        //echo json_encode($displayData)."\n";
        //return substr(json_encode($displayData),1,-1);
        return $displayData;
    }

    private function getFilterListing($module)
    {
        $filters = DefFilterOptions::where('filter_module', '=', $module)->orderBy('filter_group',
            'asc')->orderBy('filter_order', 'asc')->get();

        return $filters;
    }

    private function getFilterCounts($module, $filters, $nodeId)
    {
        $nodeMap = GeneralHelper::getNodeMap($nodeId, 'string');

        $gridFilterRes = FilterQuery::fromModule($module);
        //dd($gridFilterRes);

        $selectArray = array();
        $currentId = - 1;
        $currentSelect = '';
        // loop through the filter queries for each distict column name
        foreach ($gridFilterRes as $gridFilter) {
            $columnName = $gridFilter->db_table_name . '.' . $gridFilter->db_column_name;
            if (! in_array($columnName, $selectArray)) {
                $selectArray[] = $columnName;
            }
        }
        $groupByArray = $selectArray;
        $selectArray[] = 'count(*) as count';

        $selectString = implode(',', $selectArray);
        $groupByString = implode(',', $groupByArray);

        // get the counts
        $alarmRes = DeviceAlarm::filtered($selectString, $groupByString, $nodeMap);

        $currentId = - 1;
        $currentSelect = '';
        $filterCounts = Array();
        // loop through each filter
        foreach ($gridFilterRes as $gridFilter) {
            if ($currentId != $gridFilter->id) {
                $currentId = $gridFilter->id;
                $filterCounts[$gridFilter->id] = 0;
            }
            // loop through each count and add to sum based on matching conditions
            if (!empty($alarmRes)) {
                foreach ($alarmRes as $res) {
                    $res = (array) $res;
                    if ($gridFilter->filter_selected_condition == 'in') {
                        // not currently used, if needed in future just explode filter_selected_condition and match
                    } else if ($gridFilter->filter_selected_condition == '=') {
                        if ($res[$gridFilter->db_column_name] == $gridFilter->filter_value) {
                            $filterCounts[$gridFilter->id] += $res['count'];
                        }
                    }
                }
            }
        }

        return $filterCounts;
    }

    private function appendFilterCounts(&$filters, $filterCounts)
    {
        foreach ($filters as $filter) {
            if (isset($filterCounts[$filter->id])) {
                $filter->filter_count = $filterCounts[$filter->id];
            }
        }
    }

    private function formatFiltersToDisplay($rawFilters)
    {
        $filterCount = count($rawFilters);
        $formattedData = array();
        //var_dump($rawFilters);
        for ($i = 0; $i < $filterCount; $i ++) {
            //parent, display text,type, type List
            $formattedData[$i] = array(
                'disp'     => trans($rawFilters[$i]->filter_module . "." . $rawFilters[$i]->filter_id),
                'isParent' => $rawFilters[$i]->filter_parent == 1 ? true : false,
                'type'     => $rawFilters[$i]->filter_type,
                'group'    => $rawFilters[$i]->filter_group,
                'id'       => $rawFilters[$i]->filter_id,
                'numId'    => $rawFilters[$i]->id,
                'count'    => $rawFilters[$i]->filter_count,
            );
            if ($rawFilters[$i]->filter_type === '1') {
                $formattedData[$i]['state'] = $rawFilters[$i]->default_state === '1' ? 'true' : 'false';
            } else if ($formattedData[$i]['type'] === '2') { // its a dropdown
                //TODO handle drop down
            }
            //var_dump($formattedData);
        }

        //var_dump($formattedData);
        return $formattedData;
    }

}
