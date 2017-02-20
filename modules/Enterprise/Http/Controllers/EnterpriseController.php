<?php namespace Modules\Enterprise\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class EnterpriseController extends Controller {

	public function index()
	{
		return view('enterprise::index');
	}

}
