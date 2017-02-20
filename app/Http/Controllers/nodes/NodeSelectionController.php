<?php namespace Unified\Http\Controllers\nodes;

use Unified\Http\Helpers\GeneralHelper;
use Unified\Http\Helpers\UserHelper;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Models\Device;
use Unified\Models\Group;
use Unified\Models\NetworkTree;

use DB;
use Input;

class NodeSelectionController extends \BaseController
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
     * @param  int $nodeId
     *
     * @return Response
     */
    public function show($nodeId = 0, $includeSeverity = false)
    {

        $usingHome = false;
        //echo $nodeId;
        if ($nodeId == 0) {
            //initial login
            $nodeId = UserHelper::getUserHomeNode();
            $usingHome = true;
        }
        //$breadcrumbRes['breadcrumb'] = NetworkTreeMap::where('node_id', '=', $nodeId)->get(array('breadcrumb'));
        $devGrpId = NetworkTree::where('id', '=', $nodeId)->get(array('device_id', 'group_id'));

        //$nodeInfo['breadcrumb'] = $breadcrumbRes['breadcrumb'][0]->breadcrumb;

        if ($devGrpId[0]->device_id == null || $devGrpId[0]->device_id == 0) {
            //it is a group
            $nameRes['selectedNodeName'] = Group::where('id', '=', $devGrpId[0]->group_id)->get(array('name'));
        } else {
            //it is a device
            $nameRes['selectedNodeName'] = Device::where('id', '=', $devGrpId[0]->device_id)->get(array('name'));
        }
        $nodeInfo['selectedNodeName'] = $nameRes['selectedNodeName'][0]->name;

        $parent = NodeHelper::getNodeParent($nodeId, $includeSeverity);
        $current = NodeHelper::getNodeById($nodeId, $includeSeverity);
        $children = NodeHelper::getNodeChildren($nodeId, $includeSeverity);

        $nodeInfo['nodeOptions'] = NodeHelper::formatNodes($parent, $current, $children, $usingHome, $includeSeverity);
        //var_dump($nodeInfo['nodeOptions']);
        $view = 'nodes/NodeSelection';
        //View::make('nodes/NodeSelection', $nodeInfo);
        $name = "selction";

        return GeneralHelper::makeWithExtras($view, $nodeInfo, $nodeId, $name);
    }

    public function showWithSeverities($nodeId = 0)
    {
        return NodeSelectionController::show($nodeId, true);
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

    public function autoComplete()
    {
        $str = (string) trim(Input::get('name'));
        $subDev = (string) Input::get('subDev');
        $fullTreeSearch = (string) Input::get('fullTreeSearch');
        $selectedNodeSearch = (string) Input::get('selectedNodeSearch');
        $selectedNode = (string) Input::get('selectedNode');
        $pageLimit = Input::get('page_limit'); //is_int(Input::get('page_limit'))&&Input::get('page_limit') > 0?Input::get('page_limit'):10;
        $page = Input::get('page') - 1;//is_int(Input::get('page'))&&Input::get('page') > 0 ? Input::get('page'): 0;
        //echo "$pageLimit -- $page ";
        $getMains = $subDev == "true" ? '0,1' : '1';
        $homeNode = UserHelper::getUserHomeNode();
        //echo "$selectedNodeSearch -- $homeNode";
        $nodeId = $selectedNodeSearch == "true" ? $selectedNode : $homeNode;
        //echo "$subDev -- $fullTreeSearch -- $selectedNodeSearch -- $selectedNode -- $getMains -- $nodeId";
        /*
        SELECT nt.id, COALESCE(dev.name,g.name)as name, dev.id, dev.ip_address, dev.ip_address_2, concat("%",concat(COALESCE(dev.name,g.name),",", dev.ip_address,",", dev.ip_address_2),"%")
        from css_networking_network_tree nt
        INNER join css_networking_network_tree_map ntm on nt.id = ntm.node_id and ntm.node_map like '%.240689.%'and ntm.visible = 1 and ntm.deleted = 0 and ntm.build_in_progress=0
        left join css_networking_group g on g.id = nt.group_id
        left join css_networking_device dev on dev.id = nt.device_id
        left join css_networking_device_type dt on dt.id = dev.type_id
        where  (dt.main_device =1 or dt.id is null) and (
        dev.name like '%net%' or
        g.name like '%net%' or
        dev.ip_address like '%net%' or
        dev.ip_address_2 like '%net%')*/

        $res = NetworkTree::search($getMains, $str, $nodeId, $pageLimit, $page, $selectedNodeSearch);

        $searchRes = $res->result;
        $count     = $res->num;

        $arr = array();
        $dataResult['count'] = $count[0]->count;
        for ($i = 0; $i < count($searchRes); $i ++) {
            /*$arr[] = array ('name'=> $searchRes[$i]->name,
            'id'=>$searchRes[$i]->node_id);*/
            if ($searchRes[$i]->devId == null) {
                $arr[$i]['name'] = $searchRes[$i]->name;
            } else {
                $arr[$i]['name'] = $searchRes[$i]->name . " (" . $searchRes[$i]->display . ")";
            }
            $arr[$i]['id'] = $searchRes[$i]->node_id;
        }
        if (count($arr) == 0) {
            $arr = array(array('name' => trans('menuArea.noresultsfound'), 'id' => - 1));
        }
        $dataResult['data'] = $arr;

        return json_encode($dataResult);
    }
}
