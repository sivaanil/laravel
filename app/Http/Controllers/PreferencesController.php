<?php namespace Unified\Http\Controllers;

use Auth;
use Input;
use Unified\Models\UserPref;

class PreferencesController extends \BaseController {

    public function getPreferences()
    {
        $user = Auth::user()->id;
        $component = Input::get('component');
        $module = Input::get('module');
        $prefix = $component . '_' . $module;
        $prefs = UserPref::where('user_id', '=', $user);
        $prefs->where('variable_name', 'LIKE', $prefix . '%');
        //$prefs->keyBy('variable_name');
        $prefs->get();

        //return $prefs;
    }

    public function getPreference()
    {
        $user = Auth::user()->id;
        $name = Input::get('name');
        $pref = UserPref::where('user_id', '=', $user);
        $pref->where('variable_name', '=', $name);
        $data = $pref->get();
        if (isset($data[0])) {
            echo $data[0]->value;
        }
    }

    public function deletePreference($user, $name)
    {
        $pref = UserPref::where('user_id', '=', $user);
        $pref->where('variable_name', '=', $name);
        if ($pref->get()->count() > 0) {
            $pref->delete();
        }
    }

    public function setPreference()
    {
        $user = Auth::user()->id;
        $name = Input::get('name');
        $value = Input::get('value');

        // delete existing
        $this->deletePreference($user, $name);

        // save new
        $pref = new UserPref;
        $pref->user_id = $user;
        $pref->variable_name = $name;
        $pref->value = $value;
        $pref->save();

    }

}
