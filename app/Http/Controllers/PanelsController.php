<?php namespace Unified\Http\Controllers;

use \Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class PanelsController extends Controller
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

    /**
     * routes
     */
    public function panel()
    {
        return View::make("panels::panel");
    }

    public function panelView()
    {
        return View::make("panels::panelView");
    }

    public function panelMenu()
    {
        return View::make("panels::panelMenu");
    }

    /**
     * get all panels
     */
    public function panelsList()
    {

        $panels = PanelsHelper::getAllPanels();

        return json_encode($panels);
    }

    /**
     * menu
     */
    public function refreshPanelMenu()
    {

        $nodeId = Input::get('nodeId');
        $name = Input::get('activePage');

        //get the node
        $node = NodeHelper::getNodeNameAndType($nodeId);
        if ($node['isGroup'] == 1) {
            $NodeType = "group";
        } else {
            $NodeType = "device";
        }
        $panels = PanelsHelper::getPanelsByDeviceType($NodeType);

        $jsonMenu = json_encode($panels);

        return $jsonMenu;
    }

    //menu buttons 
    public function getMenuButtonControls()
    {

        $tmp = array();
        $type = 'group';
        $tmp = PanelsHelper::getPanelsByDeviceType($type);

        return json_encode($tmp);
    }

}
