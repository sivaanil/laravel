<?php namespace Unified\Http\Helpers;

use Auth;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Extras include getting the breadcrumb and setting up the navbar
class UserHelper
{

    public static function getUserHomeNode()
    {
        return Auth::user()->home_node_id;
    }

}
