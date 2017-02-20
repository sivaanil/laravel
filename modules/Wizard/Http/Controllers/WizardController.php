<?php namespace Modules\Wizard\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class WizardController extends Controller {

	public function index()
	{
		return view('wizard::index');
	}

}
