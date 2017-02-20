<?php namespace Unified\Http\Controllers;

use Input;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Http\Helpers\nodes\NodeHelper;

class MenuController extends \BaseController
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

    public function refreshMenu()
    {
        $nodeId = Input::get('nodeId');
        $name = Input::get('activePage');
        $newMenu = GeneralHelper::getNavBarSettings($nodeId, $name);
        $jsonMenu = json_encode($newMenu);

        return $jsonMenu;
    }

    public function refreshBreadcrumbMenu() {
        $nodeId = Input::get('nodeId');
        return GeneralHelper::makeWithExtras('menuBreadcrumbs', NULL, $nodeId, 'test');
    }

    public function breadcrumbData($nodeId) {
        $breadcrumb = GeneralHelper::getNodeBreadcrumb($nodeId);
        //echo json_encode($breadcrumb);
        return $breadcrumb;
    }

    public function launchScan($id, $type){
        NodeHelper::launchScan($id, $type);
        return;
    }

}
