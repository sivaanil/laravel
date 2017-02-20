<?php namespace Unified\Http\Controllers;

use Input;
use Log;


class NetworkTreeController extends \BaseController
{

    private $tree = null;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->initTree();

        return $this->tree->showTree();
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

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

    /**
     * initialize tree
     *
     */
    public function initTree()
    {

        if ($this->tree == null) {
            $this->tree = new Tree();
        }
    }

    /**
     * load Parent Nodes using async call.
     *
     * @param  int $nodeId
     *
     * @return json response
     */
    public function loadFirstLevel($nodeId = 0)
    {
        $this->initTree();
        if ($nodeId == 0) {
            $nodeId = $this->tree->homeNodeId;
        } else {
            $nodeId = (string) Input::get('nodeId');
        }
        $parent = $this->tree->getLevelByNodeId($nodeId);
        $children = $this->tree->getLevelChildrenByNodeId($nodeId);

        // UN-783 - allows device type filter to be set
        $deviceTypeFilter = null;
        if (!empty(Input::get('dtf'))) {
            $deviceTypeFilter = Input::get('dtf');
        }

        if ($deviceTypeFilter) {
            $children = $this->tree->filterByDeviceType($deviceTypeFilter, $children);
        }


        return json_encode(array('nodes'        => $this->tree->formatNodes($parent, $children),
                                 'lastsynctime' => $this->tree->lastSyncTime(),
                                 'length'       => count($children) + 1
        ));
    }

    /**
     * load Child Nodes using async call.
     *
     * @return json response
     */
    public function loadNextLevel()
    {
        $this->initTree();
        $nodeId = (string) Input::get('nodeId');
        $parent = $this->tree->getLevelByNodeId($nodeId);
        $children = $this->tree->getLevelChildrenByNodeId($nodeId);

        $deviceTypeFilter = null;
        if (!empty(Input::get('dtf'))) {
            $deviceTypeFilter = Input::get('dtf');
        }
        if ($deviceTypeFilter) {
            $children = $this->tree->filterByDeviceType($deviceTypeFilter, $children);
        }

        return json_encode(array('nodes'        => $this->tree->formatNodes($parent, $children, 2),
                                 'lastsynctime' => $this->tree->lastSyncTime(),
                                 'length'       => count($children) + 1
        ));
    }

    /**
     * load all Nodes level using async call.
     *
     * @return json response
     */
    public function loadAllLevel()
    {
        $this->initTree();
        $nodeId = $this->tree->homeNodeId;

        $output = $this->tree->loadAllLevelsByNodeId($nodeId);

        // Filter by device type, if applicable.
        if (!empty(Input::get('dtf'))) {
            $nodes = $this->tree->filterByDeviceType($deviceType, $output['nodes']);
            $output['nodes'] = $nodes;
        }
        return $output;
    }

    /**
     * load Full Network Tree using async call.
     *
     * @return json response
     */
    public function getTreeControls()
    {

        $tmp = array();
        $tmp1 = array();

        $tmp[0] = array('id'    => 'expandNode',
                        'value' => 'Expand',
                        'class' => 'expandNode',
                        'event' => 'expandItem',
                        'show'  => "true",
                        'src'   => '/img/icons/expand.png'
        );
        $tmp[1] = array('id'    => 'collapseNode',
                        'value' => 'Collapse',
                        'class' => 'collapseNode',
                        'event' => 'collapseItem',
                        'show'  => "true",
                        'src'   => '/img/icons/collapse.png'
        );

        $tmp1[0] = array('id'    => 'colorLegend',
                         'value' => 'Color',
                         'class' => 'colorLegend',
                         'event' => 'colorLegend',
                         'show'  => "true",
                         'src'   => '/img/icons/color_wheel.png'
        );
        $tmp1[1] = array('id'    => 'moveNode',
                         'value' => 'Move',
                         'class' => 'moveNode',
                         'event' => 'moveNode',
                         'show'  => "true",
                         'src'   => '/img/icons/table_gear.png'
        );

        $tmp1[2] = array('id'    => 'addDevice',
                         'value' => '+Device',
                         'class' => 'addDevice',
                         'event' => 'addDevice',
                         'show'  => "selectedItemType == 'group'",
                         'src'   => '/img/icons/switch.png'
        );
        $tmp1[3] = array('id'    => 'editDevice',
                         'value' => 'Edit',
                         'class' => 'editNode',
                         'event' => 'editItem',
                         'show'  => "selectedItemType == 'device'",
                         'src'   => '/img/icons/rename.png'
        );
        $tmp1[4] = array('id'    => 'removeDevice',
                         'value' => '-Remove',
                         'class' => 'removeNode',
                         'event' => 'removeItem',
                         'show'  => "selectedItemType == 'device'",
                         'src'   => '/img/icons/delete.png'
        );
        $tmp1[5] = array('id'    => 'scanDevice',
                         'value' => 'Scan',
                         'class' => 'scanDevice',
                         'event' => 'scanDevice',
                         'show'  => "selectedItemType == 'device'",
                         'src'   => '/img/icons/switch.png'
        );

        $tmp1[6] = array('id'    => 'addGroup',
                         'value' => '+Group',
                         'class' => 'addGroup',
                         'event' => 'addGroup',
                         'show'  => "selectedItemType == 'group'",
                         'src'   => '/img/icons/folder.png'
        );
        $tmp1[7] = array('id'    => 'editGroup',
                         'value' => 'Edit',
                         'class' => 'editNode',
                         'event' => 'editItem',
                         'show'  => "selectedItemType == 'group'",
                         'src'   => '/img/icons/rename.png'
        );
        $tmp1[8] = array('id'    => 'removeGroup',
                         'value' => '-Remove',
                         'class' => 'removeNode',
                         'event' => 'removeItem',
                         'show'  => "selectedItemType == 'group'",
                         'src'   => '/img/icons/delete.png'
        );

        return json_encode(array(0 => $tmp, 1 => $tmp1));

    }

    /**
     * load Full Network Tree usinng async call.
     *
     * @return json response
     */
    public function syncServer()
    {
        $this->initTree();
        $lastsynctime = (string) Input::get('lastsynctime');
        $lastsynctime = str_replace("%20", " ", $lastsynctime);
        dd('dying on purpose');
        $list = $this->tree->getAllLevelNodesSinceTime($lastsynctime);

        return json_encode(array(
            'updated'      => $this->tree->formatNodes(null, $list['updated']),
            'deleted'      => $this->tree->formatNodes(null, $list['deleted']),
            'lastsynctime' => $this->tree->lastSyncTime(),
            'length'       => count($list)
        ));
    }

    /**
     * load additional node Information using async call.
     *
     * @return json response
     */
    public function getNodeInformation()
    {
        $this->initTree();
        $nodeId = (string) Input::get('nodeId');

        return json_encode(array(0 => $this->tree->getNodeInfoByNodeId($nodeId)));
    }


}
