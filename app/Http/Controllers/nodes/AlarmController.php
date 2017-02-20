<?php

namespace Unified\Http\Controllers\nodes;

use DateTime;
use DB;
use Input;
use Excel;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Http\Controllers\BaseController;
use Unified\Http\Controllers\devices\DeviceController;
use Unified\Http\Controllers\general\FilterController;
use Unified\Models\DefGrid;
use Unified\Models\DefGridMenu;
use Unified\Models\GridColumns;
use Unified\Models\FilterOptions;
use Unified\Models\FilterQuery;
use Unified\Models\DeviceAlarm;

class AlarmController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /* $alaCount = DB::table('css_networking_device_alarm as da')
          ->select(DB::raw("COUNT(*) as count"))
          ->join(DB::raw("(SELECT
          nt.device_id, nt.id as nid, ntm.breadcrumb as breadcrumb
          from
          css_networking_network_tree nt
          Inner join css_networking_network_tree_map ntm on ntm.node_id = nt.id and ntm.node_map like '%.$id.%'
          and ntm.deleted = 0        and ntm.build_in_progress = 0        and ntm.visible = 1) as tmp"), 'tmp.device_id','=','da.device_id')
          ->whereRaw('da.cleared is null')
          ->get();
          $alarmList['count'] = $alaCount[0]->count; */
        $filCont = new FilterController();
        $alarmList['filters'] = json_encode($filCont->getFilters('alarm', null, false));
        $name = "alarms";
        //var_dump($alarmList['alarms']);
        //Log::debug("Device Object:\n" . print_r($device->toArray(), true));
        return GeneralHelper::makeWithExtras('nodes/Alarms', $alarmList, $id, $name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function tmpGetAlarmHeaders($json_encode = true, $queryType = null)
    {

        $node = (string) Input::get('nodeId');
        $first = Input::get('isFirst');
        $startIndex = Input::get('recordstartindex');
        $endIndex = Input::get('recordendindex');
        $sortData = Input::get('sortData');
        $filterData = Input::get('filterData');
        $filterGroups = Input::get('filterGroups');
        $queryType = $queryType === null ? Input::get('queryType') : $queryType;
        //var_dump($filterGroups);
        //var_dump($filterData);
        $start = $startIndex === null ? 0 : $startIndex;
        $end = $endIndex === null ? 120 : $endIndex;
        $numRowsToFetch = $end - $start;
        $gridName = 'alarm';
        $alarmColumnList = $this->getColumnHeaderData($gridName);
        $alarmGrid = $this->getGridDefData($gridName);
        if ($alarmGrid->has_button_column === "1") {
            $alarmMenuList = $this->getMenuColumnData($gridName);
        }
        $sortList = array();
        if ($sortData != null && count($sortData) > 0) {
            $sortList = $this->createSortList($sortData, $alarmColumnList);
        }

        $filterList = array();
        if ($filterData != null && count($filterData) > 0) {
            $filterList = $this->createFilterList($filterData, $alarmColumnList);
        }
        $filterGroupList = array();
        if ($filterGroups != null) {
            $filterGroupList = $this->createFilterGroupList($filterGroups, $gridName);
        } else {
            $filterGroupList = $this->getDefaultFilterGroupSet($gridName);
        }
        //var_dump($filterGroupList);

        $columnCount = count($alarmColumnList);

        //Adding an extra true column because there is an extra comma added because SQL_CALC_FOUND_ROWS is treated as a column by itself
        $selectList = array();
        for ($i = 0; $i < $columnCount; $i ++) {
            if ($alarmColumnList[$i]->column_id == "path") {
                //TODO add handler on front end to render this column as a link instead of passing the link from here
                //$str = "CONCAT(\"<a href=\\\"" . url() . "/home#/alarms/\",css_networking_network_tree.id,\"\\\">\",".$this->getAlarmSqlColumnString($alarmColumnList[$i], false).") as ".$alarmColumnList[$i]->column_id;
                //$selectList[] = DB::raw($str);
            } else if ($alarmColumnList[$i]->column_id == "notes") {
                //Make the notes column. It is a concat of 4 different columns
                //This likely isn't going to be common for grids in SP so this isn't setup with tables
                //If this becomes more common make a maps table to link 1 def_grid_column link to many db columns
                $str = "`css_networking_device`.`stop_alarm_until`,
                    CONCAT(IFNULL(Concat(css_networking_device_alarm.notes, '\\n'), ''),
                            IFNULL(Concat('Cause:', css_alarms_dictionary.cause, '\\n'), ''),
                            IFNULL(Concat('Impact:', css_alarms_dictionary.impact, '\\n'), ''),
                            IFNULL(Concat('Remedy:', css_alarms_dictionary.remedy, '\\n'), '')) as " . $alarmColumnList[$i]->column_id;
                $selectList[] = DB::raw($str);
            } else if ($alarmColumnList[$i]->column_data_type === 'date') {
                $str = "DATE_FORMAT(DATE_ADD(" . $this->getAlarmSqlColumnString($alarmColumnList[$i], false) . ", INTERVAL 0 minute), '%b %e %Y (%l:%i %p)') as " . $alarmColumnList[$i]->column_id;
                //echo $str;
                $selectList[] = DB::raw($str);
            } else {
                $selectList[] = $this->getAlarmSqlColumnString($alarmColumnList[$i]);
            }
        }
        //Replace this with a button list table
        /* $selectList[] = "css_networking_device_type.has_web_interface";
          $selectList[] = "css_networking_device_type.prop_scan_file"; */
        $selectList[] = "css_networking_network_tree.id as node_id";
        $selectList[] = "css_networking_device_alarm.can_acknowledge as can_ack";
        $selectList[] = "css_networking_device_alarm.ignored as ignored";
        $selectList[] = "css_networking_device.stop_scan_until";
        $selectList[] = "css_networking_device.stop_property_until";
        $selectList[] = "css_networking_device.stop_alarm_until";

        if ($queryType == 'data') {
            //var_dump($selectList);
            $alarmRes = $this->gridDataQuery($start, $numRowsToFetch, $node, $selectList, $sortList, $filterList, $filterGroupList);
            $alaCount = count($alarmRes);

            //Get Info for Buttons
            //$menuStuff = $this->buttonsFromAlarmQuery($alarmRes);
            //alarmRes is pass by reference
            //$this->mergeDataWithButtons($alarmRes, $menuStuff, $alarmMenuList);

            for ($i = 0; $i < $alaCount; $i ++) {
                //These columns are used to setup the menu, after the menu number is set the can all be removed so they don't appear in the row details
                unset($alarmRes[$i]->TRUE);
                unset($alarmRes[$i]->stop_scan_until);
                unset($alarmRes[$i]->stop_property_until);
                unset($alarmRes[$i]->stop_alarm_until);
                unset($alarmRes[$i]->can_ack);
                unset($alarmRes[$i]->ignored);
                $sev = GeneralHelper::getNameFromSeverity($alarmRes[$i]->severity);
                $alarmRes[$i]->severity = ucfirst($sev['severity']);
            }
            $returnData['data'] = $alarmRes;
        }

        //dd(DB::getQueryLog());
        //var_dump($alarmColumnList);
        if ($first != "false") {
            $returnData['columns'] = $alarmColumnList;
            $returnData['class'] = $alarmGrid->class_column_id;
            $returnData['hasButtons'] = $alarmGrid->has_button_column === "1";
            if ($returnData['hasButtons']) {
                $returnData['menuList'] = $alarmMenuList;
            }
        }

        if ($queryType == 'count') {
            $selectList = array(DB::raw('COUNT(*) as alarmCount'));
            $alarmCount = $this->gridCountQuery(0, 1, $node, $selectList, $sortList, $filterList, $filterGroupList);
            $returnData['count'] = $alarmCount[0]->alarmCount;
        }

        if ($json_encode) {
            return json_encode($returnData);
        } else {
            return $returnData;
        }
    }

    private function gridDataQuery(
    $start, $numRowsToFetch, $node, $selectList, $sortList, $filterList, $filterGroupList = array()
    )
    {
        $selectStmt = "";
        $sortBy = "";
        $filterBy = "";
        $filterGroupBy = "";
        $term = "";
        $clearedTerm = "css_networking_device_alarm.cleared_bit = (0)";

        $nodeMap = GeneralHelper::getNodeMap($node, 'string');
        $nodeCount = NodeHelper::countNodeSubtree($nodeMap);

        // ************************** Configure the Select Statement.
        if (count($selectList) > 0) {
            $selectStmt = "Select ";
            foreach ($selectList as $rowKey => $selectRow) {
                if ($rowKey > 0) {
                    $selectStmt .= ", ";
                } else {
                    $selectStmt .= " ";
                }
                $selectStmt .= $selectRow;
            }
        }
        $selectStmt = str_replace("'", "\"", $selectStmt);

        // ************************** Configure the Order By string.
        if (count($sortList) > 0) {
            $sortBy = " Order by ";
            foreach ($sortList as $rowKey => $sortRow) {
                if ($rowKey > 0) {
                    $sortBy .= ", ";
                }
                foreach ($sortRow as $sortItem) {
                    $sortBy .= $sortItem . " ";
                }
            }
        } else {
            $sortBy = "Order by css_networking_device_alarm.raised desc ";
        }

        // ************************** Configure the Qualifiers.
        if (count($filterList) > 0) {
            for ($fieldIndex = 0; $fieldIndex < count($filterList); $fieldIndex++) {
                if ($fieldIndex > 0) {
                    $filterBy .= "AND ";
                }
                $filterBy .= "(" . $filterList[$fieldIndex]["col"] . " ";
                for ($qualifierIndex = 0; $qualifierIndex < count($filterList[$fieldIndex]) - 1; $qualifierIndex++) {
                    if ($qualifierIndex > 0) {
                        $filterBy .= $filterList[$fieldIndex][$qualifierIndex]["cond"] . " " . $filterList[$fieldIndex]["col"] . " " .
                                $filterList[$fieldIndex][$qualifierIndex]["oper"] . " \"" . $filterList[$fieldIndex][$qualifierIndex]["val"] . "\" ";
                    } else {
                        $filterBy .=$filterList[$fieldIndex][$qualifierIndex]["oper"] . " \"" . $filterList[$fieldIndex][$qualifierIndex]["val"] . "\" ";
                    }
                }
                $filterBy .= ") ";
            }
        } else {
            $filterBy = "true ";
        }

        // ************************** Configure the Group Qualifiers.
        if (count($filterGroupList) > 0) {
            for ($fieldIndex = 0; $fieldIndex < count($filterGroupList); $fieldIndex++) {
                $term = "AND (" . $filterGroupList[$fieldIndex]["col"] . " ";
                for ($qualifierIndex = 0; $qualifierIndex < count($filterGroupList[$fieldIndex]) - 1; $qualifierIndex++) {
                    if ($qualifierIndex > 0) {
                        $term .= $filterGroupList[$fieldIndex][$qualifierIndex]["cond"] . " " . $filterGroupList[$fieldIndex]["col"] . " " .
                                $filterGroupList[$fieldIndex][$qualifierIndex]["oper"] . " (" . $filterGroupList[$fieldIndex][$qualifierIndex]["val"] . ") ";
                    } else {
                        $term .= $filterGroupList[$fieldIndex][$qualifierIndex]["oper"] . " (" . $filterGroupList[$fieldIndex][$qualifierIndex]["val"] . ") ";
                    }
                }
                $term .= ") ";
                if ($filterGroupList[$fieldIndex]["col"] == "css_networking_device_alarm.cleared_bit") {
                    if (strpos($term, "css_networking_device_alarm.cleared_bit = (0) or css_networking_device_alarm.cleared_bit = (1)")) {
                        $term = "";
                        $clearedTerm = " TRUE ";
                    } else {
                        $clearedTerm = $term;
                    }
                    $clearedTerm = str_replace("AND", " ", $clearedTerm);
                } else if (strpos($term, "(0,1)") == false) { // && strpos($term, "(1,2,3,4,6)") == false ) {
                    $filterGroupBy .= $term;
                }
            }
        } else {
            $filterGroupBy = "true ";
        }
        $filterBy .= $filterGroupBy . " ";

        // ********************************* Laravel Processing ************************************************
        $alarmRes = array();

        $alarmRes = DB::select("call sp_gridDataQuery($numRowsToFetch, $start, '$nodeMap', '$selectStmt', '$sortBy', '$filterBy', '$clearedTerm')");

        foreach ($alarmRes as $row) {
            $breadcrumbData = GeneralHelper::getNodeBreadcrumb($row->node_id);
            $breadcrumb = [];
            //$row->path = '<a href="' . url() . '/home#/alarms/' . $row->node_id . '">';

            foreach ($breadcrumbData as $node) {
                $breadcrumb[] = $node['name'];
            }
            $row->path = implode(' >> ', $breadcrumb);
        }
        //dd($alarmRes);

        return $alarmRes;
    }

// gridDataQuery

    private function gridCountQuery(
    $start, $numRowsToFetch, $node, $selectList, $sortList, $filterList, $filterGroupList = array()
    )
    {
        // left join css_alarms_dictionary ad on (ad.device_type_id=css_networking_device.type_id and ad.alarm_description=css_networking_device_alarm.description)
        $nodeMap = GeneralHelper::getNodeMap($node, 'string');

        $numSortColumns = count($sortList);

        //this query is fetching a small number of rows so an inner select isn't ideal here.
        $alarmQue = DB::table('css_networking_device_alarm')
                ->select($selectList)
                ->join("css_networking_device", "css_networking_device.id", "=", "css_networking_device_alarm.device_id")
                //->join("css_networking_device_type", "css_networking_device_type.id", "=", "css_networking_device.type_id")
                ->join("css_networking_network_tree", "css_networking_network_tree.device_id", "=", "css_networking_device_alarm.device_id")
                ->join("css_networking_network_tree_map", function ($join) use ($nodeMap) {
            $join->on('css_networking_network_tree_map.node_id', '=', 'css_networking_network_tree.id')
            ->where('css_networking_network_tree_map.deleted', '=', "0")
            ->where('css_networking_network_tree_map.build_in_progress', '=', "0")
            ->where('css_networking_network_tree_map.visible', '=', "1")
            ->where('css_networking_network_tree_map.node_map', 'like', "$nodeMap%");
        });
        $alarmQueWiFilter = $this->addJqxFiltersToQuery($alarmQue, $filterList);
        if (count($filterGroupList) > 0) {
            $alarmQueWiFilter = $this->addJqxFiltersToQuery($alarmQueWiFilter, $filterGroupList);
        }
        $alarmQueFin = $alarmQueWiFilter->take($numRowsToFetch)->skip($start);
        //echo $alarmQueFin->toSql();
        //die;
        $alarmRes = $alarmQueFin->get();

        return $alarmRes;
    }

    private function buttonsFromAlarmQuery($data)
    {
        $alaCount = count($data);
        $nodeIdList = array();
        for ($i = 0; $i < $alaCount; $i ++) {
            $nodeIdList[] = $data[$i]->node_id;
        }
        //var_dump(count($nodeIdList));
        if (count($nodeIdList) > 0) {
            /* SELECT dt.has_web_interface, dt.prop_scan_file, nt.id from css_networking_network_tree nt
              INNER JOIN css_networking_device d on main_device_id(nt.id) = d.id
              INNER JOIN css_networking_device_type dt on dt.id = d.type_id
              where nt.id in ([List of Nodes]) */
            $res = NetworkTree::fromNodeList($nodeIdList);
        } else {
            return;
        }
        $typeCount = count($res);
        $processedData = array();
        for ($j = 0; $j < $typeCount; $j ++) {
            $processedData[$res[$j]->node_id] = Array();
            $processedData[$res[$j]->node_id]['node_id'] = $res[$j]->node_id;
            $processedData[$res[$j]->node_id]['hasWeb'] = $res[$j]->wi == 1;
            $processedData[$res[$j]->node_id]['hasProp'] = ($res[$j]->psf !== null && strlen($res[$j]->psf) > 1);
        }

        //var_dump($processedData);
        return $processedData;
    }

    private function mergeDataWithButtons(&$data, $buttons, $menuList)
    {
        $dataCount = count($data);
        for ($i = 0; $i < $dataCount; $i ++) {
            $node = $data[$i]->node_id;
            $data[$i]->menuItems = 0;
            //doing bit wise addition to prevent overflow flipping the wrong flag
            //Web interface
            if ($buttons[$node]['hasWeb'] == 1) {
                $data[$i]->menuItems = $data[$i]->menuItems | pow(2, $menuList["wedInterface"]["flag"]);
            }
            $cur_time = strtotime("now");

            /*
              if($buttons[$node]['hasProp'] == 1) {
              //If the device has a property scanner add alarm and prop scanners
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["alarmScan"]["flag"]);
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["propScan"]["flag"]);

              if($cur_time < strtotime($data[$i]->stop_alarm_until)){
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["startAlarmScan"]["flag"]);
              }else{
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["stopAlarmScan"]["flag"]);
              }
              if($cur_time < strtotime($data[$i]->stop_property_until)){
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["startPropScan"]["flag"]);
              }else{
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["stopPropScan"]["flag"]);
              }
              //
              //$data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["stopPropScan"]["flag"]);
              } else {
              //Just a single scanner
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["scanDevice"]["flag"]);
              if($cur_time < strtotime($data[$i]->stop_scan_until)){
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["startScan"]["flag"]);
              }else{
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["stopScan"]["flag"]);
              }
              }
             */

            //Can be ack
            /* if($data[$i]->can_ack == 1 && $data[$i]->clear != null) {
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["ack"]["flag"]);
              }

              //Can be ignored
              if($data[$i]->ignored == 0 ) {
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["ignore"]["flag"]);
              } else {
              $data[$i]->menuItems =$data[$i]->menuItems | pow(2,$menuList["unignore"]["flag"]);
              } */
        }
    }

    private function addJqxFiltersToQuery($alarmQue, $filterList)
    {
        //var_dump($filterList);
        $numFilters = count($filterList);
        for ($i = 0; $i < $numFilters; $i ++) {
            $filteri = $filterList[$i];
            //var_dump($filteri);
            // subquery for severity_id for performance optimization
            if ($filteri['col'] == 'css_networking_device_alarm.severity_id') {
                $alarmQue = $alarmQue->where(function ($query) use ($filteri) {
                    $query->whereIn('css_networking_device_alarm.id', function ($subquery) use ($filteri) {
                        for ($j = 0; $j < (count($filteri) - 1); $j ++) {
                            $subquery->select('css_networking_device_alarm.id')
                                    ->from('css_networking_device_alarm')
                                    ->whereIn($filteri['col'], explode(',', $filteri[$j]['val']), $filteri[$j]['cond']);
                        }
                    });
                });
            } else {
                $alarmQue = $alarmQue->where(function ($query) use ($filteri) {
                    for ($j = 0; $j < (count($filteri) - 1); $j ++) {
                        $oper = strtolower($filteri[$j]['oper']);
                        if ($oper == "in") {
                            $query->whereIn($filteri['col'], explode(',', $filteri[$j]['val']), $filteri[$j]['cond']);
                        } else if ($oper == "not in") {
                            $query->whereNotIn($filteri['col'], explode(',', $filteri[$j]['val']), $filteri[$j]['cond']);
                        } else if ($oper === "null" || $oper === "is null") {
                            $query->whereNull($filteri['col'], $filteri[$j]['cond']);
                        } else if ($oper === "is not null") {
                            $query->whereNotNull($filteri['col'], $filteri[$j]['cond']);
                        } else {//if($filteri[$j]['val'] !== null){
                            $query->where($filteri['col'], $filteri[$j]['oper'], $filteri[$j]['val'], $filteri[$j]['cond']);
                        }
                    }
                });
            }
        }

        return $alarmQue;
    }

    private function addFiltersToQuery($alarmQue, $filterList)
    {
        $numFilters = count($filterList);
        for ($i = 0; $i < $numFilters; $i ++) {
            if ($filteri[$j]['val'] !== null) {
                $alarmQue->where($filteri['col'], $filteri[$j]['oper'], $filteri[$j]['val'], $filteri[$j]['cond']);
            } else {
                $alarmQue->whereNull($filteri['col'], $filteri[$j]['cond']);
            }
        }
    }

    private function getGridDefData($gridName)
    {
        $gridDef = DefGrid::where('grid_id', '=', $gridName)->get();

        //var_dump($gridDef[0]);
        return $gridDef[0];
    }

    private function getColumnHeaderData($gridName)
    {
        //Short version of the same query but returns data in a diffrent format
        //$alarmColumnList = DefGridColumns::where('grid_id', '=', $gridName)->orderBy('column_order_priority')->get();
        return GridColumns::alarmColumnList($gridName);
    }

    /*
     * Pass in module name and fetch all of the potential menu options
     */

    private function getMenuColumnData($moduleName)
    {
        $defGridMenu = DefGridMenu::where('module_id', '=', $moduleName)->orderBy("order")->get();
        $menuItemCount = count($defGridMenu);
        $menuList = array();
        for ($i = 0; $i < $menuItemCount; $i ++) {
            $menuList[$defGridMenu[$i]->item_id]["module"] = $defGridMenu[$i]->module_id;
            $menuList[$defGridMenu[$i]->item_id]["item"] = $defGridMenu[$i]->item_id;
            $menuList[$defGridMenu[$i]->item_id]["action"] = $defGridMenu[$i]->action;
            $menuList[$defGridMenu[$i]->item_id]["flag"] = $defGridMenu[$i]->flag_two_exponent;
            $menuList[$defGridMenu[$i]->item_id]["order"] = $defGridMenu[$i]->order;
            $menuList[$defGridMenu[$i]->item_id]["display"] = trans($defGridMenu[$i]->module_id . "." . $defGridMenu[$i]->item_id);
        }

        return $menuList;
    }

    private function createSortList($sortData, $alarmColumnList)
    {
        $sortKeys = array_keys($sortData);
        $sortCount = count($sortData);
        $columnCount = count($alarmColumnList);

        $sortArray = array();
        for ($i = 0; $i < $sortCount; $i ++) {
            for ($j = 0; $j < $columnCount; $j ++) {
                if ($sortKeys[$i] == $alarmColumnList[$j]->column_id) {
                    $sortArray[$i]['column'] = $this->getAlarmSqlColumnString($alarmColumnList[$j], false);
                    $sortArray[$i]['dir'] = $this->convertDirToString($sortData[$sortKeys[$i]]);
                    break;
                }
            }
        }

        return $sortArray;
    }

    private function getAlarmSqlColumnString($alarmColumn, $includeAlias = true)
    {

        $sqlColumnString = $alarmColumn->db_table_name . "." . $alarmColumn->db_column_name;
        if ($includeAlias) {
            $sqlColumnString .= " as " . $alarmColumn->column_id;
        }

        return $sqlColumnString;
    }

    private function convertDirToString($intDir)
    {
        $retStr = "";
        if ($intDir == 1) {
            $retStr = "asc";
        } else if ($intDir == 2) {
            $retStr = "desc";
        }

        return $retStr;
    }

    private function createFilterList($filterData, $alarmColumnList)
    {
        $filterCount = count($filterData);
        $columnCount = count($alarmColumnList);

        $filterArray = array();
        for ($i = 0; $i < $filterCount; $i ++) {
            for ($j = 0; $j < $columnCount; $j ++) {
                if ($filterData[$i]['column'] == $alarmColumnList[$j]->column_id) {
                    $filterArray[$i]['col'] = $this->getAlarmSqlColumnString($alarmColumnList[$j], false);
                    break;
                }
            }
            if (isset($filterArray[$i]['col'])) {
                $dataCount = count($filterData[$i]['data']);

                for ($k = 0; $k < $dataCount; $k ++) {
                    $filtData = $filterData[$i]['data'][$k];
                    //$filterArray[$i] = $this->getFilterFormat($filterData[$i]['data'], $col);
                    $filterArray[$i][$k]['oper'] = $this->getCond($filtData["condition"]);
                    $filterArray[$i][$k]['cond'] = $this->getOper($filtData["operator"]);
                    if ($filtData['type'] === "datefilter" && isset($filtData["value"]) && $filtData["value"] != "") {
                        //date is passed back as M/d/YYYY conver to db format of YYYY/M/dd
                        $filtData["value"] = DateTime::createFromFormat('n/j/Y', $filtData["value"])->format('Y/m/d');
                    }
                    if ($filterArray[$i][$k]['oper'] == "LIKE" || $filterArray[$i][$k]['oper'] == "NOT LIKE") {
                        $filterArray[$i][$k]['val'] = "%" . $filtData["value"] . "%";
                    } else if ($filterArray[$i][$k]['oper'] == "IS NULL") {
                        $filterArray[$i][$k]['val'] = null;
                    } else {
                        $filterArray[$i][$k]['val'] = $filtData["value"];
                    }
                }
            }
        }

        return $filterArray;
    }

    //This function should go into the filter abstract
    /*
     * convert the filterGroup fetched from the ajax call into an array of query strings to add to display in the alarm grid
     * Move to the grid abstract
     */
    public function createFilterGroupList($filterGroup, $module)
    {
        $idList = [];
        foreach ($filterGroup as $currentGroup) {
            $noneTrue[$currentGroup['id']] = (int) $currentGroup['id'];
            if ($currentGroup['value'] === 'true') {
                $idList[] = (int) $currentGroup['id'];
                unset($noneTrue[$currentGroup['id']]);
            } else {
                foreach ($currentGroup['list'] as $childGroup) {
                    if ($childGroup['value'] === 'true') {
                        $idList[] = (int) $childGroup['id'];
                        unset($noneTrue[$currentGroup['id']]);
                    }
                }
            }
        }
        return $this->buildQueryListFromFilterIdList($idList, $noneTrue, $module);
    }

    private function getDefaultFilterGroupSet($module)
    {
        $filterGroup = FilterOptions::fromModule($module);
        $queryResCount = count($filterGroup);
        $idList = array();
        $noneTrue = array();
        $curParent = null;
        $curGroupList = array();
        $curGroupHasTrue = false;
        $curGroupHasFalse = false;
        for ($i = 0; $i < $queryResCount; $i ++) {
            $cur = $filterGroup[$i];
            if ($cur->filter_parent == 1) {
                //clean up last parent
                if ($i > 0) {
                    $this->doMerge($idList, $noneTrue, $curParent, $curGroupList, $curGroupHasTrue, $curGroupHasFalse);
                }

                $curGroupList = array();
                $curGroupHasTrue = false;
                $curGroupHasFalse = false;
                $curParent = $cur;
            } else {
                if ($cur->default_state == 1) {
                    $curGroupHasTrue = true;
                    $curGroupList[] = $cur->id;
                } else {
                    $curGroupHasFalse = true;
                }
            }
        }
        if ($queryResCount > 0) {
            $this->doMerge($idList, $noneTrue, $curParent, $curGroupList, $curGroupHasTrue, $curGroupHasFalse);
        }

        return $this->buildQueryListFromFilterIdList($idList, $noneTrue, $module);
    }

    /*
     * If none in the group are checked then merge into noneTrue
     * If all are true in the group then merge parent into $idList
     * If some are and some aren't then merge the true list into $isList
     * idList and noneTrue are passed by reference so both can be updated
     */

    private function doMerge(&$idList, &$noneTrue, $curParent, $curGroupList, $curGroupHasTrue, $curGroupHasFalse)
    {
        if ($curGroupHasFalse && !$curGroupHasTrue) {
            $noneTrue[] = $curParent->id;
        } else if ($curGroupHasTrue && !$curGroupHasFalse) {
            $idList[] = $curParent->id;
        } else {
            $idList = array_merge($idList, $curGroupList);
        }
    }

    /*
     * Move to the grid abstract
     */
    public function buildQueryListFromFilterIdList($idList, $noneTrue, $module)
    {
        $allIDs = array_merge($idList, $noneTrue);
        //var_dump($allIDs);

        $gridFilterRes = FilterQuery::getForIds($allIDs, $module);
        $filterCount = count($gridFilterRes);
        $colIndex = - 1;
        $queryList = array();
        $queryList[0]['col'] = "";
        for ($j = 0; $j < $filterCount; $j ++) {
            if (!isset($queryList[$colIndex]['col']) || $queryList[$colIndex]['col'] != $gridFilterRes[$j]->db_table_name . "." . $gridFilterRes[$j]->db_column_name) {
                if (isset($k) && isset($queryList[$colIndex])) {
                    // since it is the last parameter for a column switch it from or to and before going to next column
                    //$queryList[$colIndex][$k-1]['cond'] = 'and';
                }
                $k = 0;
                ++$colIndex;
                $queryList[$colIndex]['col'] = $gridFilterRes[$j]->db_table_name . "." . $gridFilterRes[$j]->db_column_name;
            }
            if (isset($noneTrue[$gridFilterRes[$j]->id]) && $gridFilterRes[$j]->id == $noneTrue[$gridFilterRes[$j]->id]) {
                //echo "INVERTING ".$gridFilterRes[$j]->id;
                $queryList[$colIndex][$k]['oper'] = $this->invertCond($gridFilterRes[$j]->filter_selected_condition);
                //need to convert to and in order to really invert (De Morgan's laws)
                // not (A nd B) == (not A) and (not B)
                $queryList[$colIndex][$k]['cond'] = 'and';
            } else {
                $queryList[$colIndex][$k]['oper'] = $gridFilterRes[$j]->filter_selected_condition;
                $queryList[$colIndex][$k]['cond'] = 'or';
            }
            $queryList[$colIndex][$k]['val'] = $gridFilterRes[$j]->filter_value === "" ? null : $gridFilterRes[$j]->filter_value;
            $k ++;
        }
        //var_dump($queryList);
        //change the last to an and
        //$queryList[$colIndex][$k]['cond'] = 'and';
        return $queryList;
    }

    /* private function getFilterFormat($dataToFormat, $column){
      $numColFilters = count($dataToFormat);
      //$theWhere = new Illuminate\Database\Query\Builder();
      for($k=0; $k< $numColFilters; $k++){
      $cond = $this->getCond($dataToFormat[$k]["condition"]);
      $oper = $this->getOper($dataToFormat[$k]["operator"]);
      $val = $dataToFormat[$k]["value"];
      if($oper == "LIKE" || $oper == "NOT LIKE"){
      $val = "%".$val."%";
      }
      $theWhere->where($column, $cond, $val, $oper);
      }
      return $theWhere;
      } */

    /*
     * Move to the grid abstract
     */

    private function getCond($cond)
    {

        switch ($cond) {
            case 'EQUAL':
                $condSymb = '=';
                break;
            case 'LESS_THAN':
                $condSymb = '<';
                break;
            case 'LESS_THAN_OR_EQUAL_TO':
                $condSymb = '=<';
                break;
            case 'GREATER_THAN':
                $condSymb = '>';
                break;
            case 'GREATER_THAN_OR_EQUAL_TO':
                $condSymb = '>=';
                break;
            case 'CONTAINS':
                $condSymb = 'LIKE';
                break;
            case 'DOES_NOT_CONTAIN':
                $condSymb = 'NOT LIKE';
                break;
            case 'NULL':
                $condSymb = 'IS NULL';
                break;
            default:
        }

        return $condSymb;
    }

    /*
     * Move to the grid abstract
     */

    private function getOper($oper)
    {
        if ($oper == 0) {
            return 'and';
        } else {
            return 'or';
        }
    }

    /*
     * Move to the grid abstract
     */

    private function invertCond($cond)
    {
        $condSymb = $cond;
        switch (strtoupper($cond)) {
            case '=':
                $condSymb = '<>';
                break;
            case '<>':
                $condSymb = '=';
                break;
            case 'IN':
                $condSymb = 'NOT IN';
                break;
            case 'NOT IN':
                $condSymb = 'IN';
                break;
            case 'LIKE':
                $condSymb = 'NOT LIKE';
                break;
            case 'NOT LIKE':
                $condSymb = 'LIKE';
                break;
            case 'NULL':
            case "IS NULL":
                $condSymb = 'IS NOT NULL';
                break;
            case 'IS NOT NULL':
                $condSymb = 'NULL';
                break;
            default:
        }

        return $condSymb;
    }

    /*
     * Move to the grid abstract
     */

    public function dataExport()
    {
        $headers = $this->tmpGetAlarmHeaders(false, 'data');
        //dd($headers['data']);
        Excel::create('Alarms', function ($excel) use ($headers) {

            // Set the title
            $excel->setTitle('Alarms');

            // Chain the setters
            $excel->setCreator("C Squared Systems, LLC.")
                    ->setCompany("C Squared Systems, LLC.");

            // Call them separately
            $excel->sheet('Alarms', function ($sheet) use ($headers) {
                foreach ($headers['data'] as &$row) {
                    $row = (array) $row;
                    foreach ($row as &$rowItem) {
                        $rowItem = strip_tags($rowItem);
                    }
                }
                $sheet->fromArray($headers['data']);
                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 40);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 40);
                $sheet->setWidth('E', 7);
                $sheet->setWidth('F', 12);
                $sheet->setWidth('G', 18);
                $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
                $sheet->setAutoFilter();
                $sheet->row(1, function($row) {
                    $row->setBackground('#4F81BD');
                });
            });
        })->export('xls');
    }

    public function handleGridAction()
    { 
        $action = (string) Input::get('action');
        $alarmId = (string) Input::get('alarmId');
        $nodeId = AlarmController::getNodeFromAlarmId($alarmId);
        if ($action === "wedInterface") {
            if ($nodeId !== false) {
                $deviceController = new DeviceController();
                $link = $deviceController->getWebLink($nodeId->id);
                $retObj = array('link' => $link, 'action' => $action);
            } else {
                $retObj = array('action' => $action);
            }
        }
        if ($action === "scanDevice") {
            NodeHelper::launchScan($nodeId, "scan");

            return;
        }
        if ($action === "alarmScan") {
            NodeHelper::launchScan($nodeId, "alarm");

            return;
        }
        if ($action === "propScan") {
            NodeHelper::launchScan($nodeId, "prop");

            return;
        }
        if ($action === "stopScan" || $action === "stopAlarmScan" || $action === "stopPropScan") {
            $maxTime = Duration::getMaxStopScanDuration();
            $retObj = array('nodeId' => $nodeId->id, 'action' => $action, 'maxTime' => $maxTime);
        }
        if ($action === "startScan") {
            $enableScanRes = AlarmController::startScanPost($nodeId, "scan");
            $retObj = array('nodeId' => $nodeId->id, 'action' => $action, 'enableScanRes' => $enableScanRes);
        }
        if ($action === "startAlarmScan") {
            $enableScanRes = AlarmController::startScanPost($nodeId, "alarm");
            $retObj = array('nodeId' => $nodeId->id, 'action' => $action, 'enableScanRes' => $enableScanRes);
        }
        if ($action === "startPropScan") {
            $enableScanRes = AlarmController::startScanPost($nodeId, "prop");
            $retObj = array('nodeId' => $nodeId->id, 'action' => $action, 'enableScanRes' => $enableScanRes);
        }
        if ($action === "ignore") {
            
        }
        if ($action === "unignore") {
            
        }
        if ($action === "ack") {
            
        }

        return json_encode($retObj);
    }

    public static function getNodeFromAlarmId($alarmId)
    {
        $res = DeviceAlarm::getNode($alarmId);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    public static function startScanPost($id, $type)
    {
        $mainDevNode = NodeHelper::getMainDevNodeFromNodeId($id);
        $node = NetworkTree::find($mainDevNode->id);
        $device = Device::find($node->device_id);

        $date = new DateTime("2000-01-01 00:00:00");

        if ($type == "scan") {
            $device->stop_scan_until = $date->format('Y-m-d H:i:s');
        } else if ($type == "alarm") {
            $device->stop_alarm_until = $date->format('Y-m-d H:i:s');
        } else if ($type == "prop") {
            $device->stop_property_until = $date->format('Y-m-d H:i:s');
        }
        $device->save();

        return "true";
    }

}
