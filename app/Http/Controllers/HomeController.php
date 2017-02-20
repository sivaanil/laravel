<?php namespace Unified\Http\Controllers;

use Unified\Models\Device;
use Unified\Models\NetworkTree;
use Unified\Models\Group;
use Unified\Http\Helpers\devices\DeviceHelper;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Http\Helpers\UserHelper;
use Unified\Http\Helpers\GeneralHelper;
use Auth;
class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('home');
    }

    public function menuTest() {
        return view('menutest');
    }

    public function buttonTest() {
        return view('buttontest');
    }

    public function aclcheck($permission = "") {
       $output = [];
       $output['status'] = Auth::user()->can($permission) ? 1 : 0;
       return json_encode($output);
    }

    public function show($nodeId = 0, $includeSeverity=false)
    {
        $nodeId = UserHelper::getUserHomeNode();
        $usingHome=true;
        //$breadcrumbRes['breadcrumb'] = NetworkTreeMap::where('node_id', '=', $nodeId)->get(array('breadcrumb'));
        $devGrpId = NetworkTree::where('id','=',$nodeId)->get(array('device_id', 'group_id'));

        //$nodeInfo['breadcrumb'] = $breadcrumbRes['breadcrumb'][0]->breadcrumb;

        if($devGrpId[0]->device_id == null || $devGrpId[0]->device_id == 0){
            //it is a group
            $nameRes['selectedNodeName'] = Group::where('id','=',$devGrpId[0]->group_id)->get(array('name'));
        }else{
            //it is a device
            $nameRes['selectedNodeName'] = Device::where('id','=',$devGrpId[0]->device_id)->get(array('name'));
        }
        $nodeInfo['selectedNodeName'] = $nameRes['selectedNodeName'][0]->name;

        $parent = NodeHelper::getNodeParent($nodeId, $includeSeverity);
        $current = NodeHelper::getNodeById($nodeId, $includeSeverity);
        $children = NodeHelper::getNodeChildren($nodeId, $includeSeverity);

        $nodeInfo['nodeOptions'] = NodeHelper::formatNodes($parent, $current, $children, $usingHome, $includeSeverity);
        //dd($nodeInfo['nodeOptions']);
        $view = 'layouts/master';
        //View::make('nodes/NodeSelection', $nodeInfo);
        $name = "selction";
        return GeneralHelper::makeWithExtras($view, $nodeInfo, $nodeId, $name);
    }

}
