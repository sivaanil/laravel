<?php namespace Unified\Http\Controllers\system;


use Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Unified\Models\SystemStats;
use Unified\Models\Timezone;
use Unified\System\CommandHelper;
use Unified\System\Network\WANStatus;
use Log;
use Artisan;

class SettingsController extends \BaseController
{

    public function __construct(WANStatus $wanStatus)
    {
        $this->wanStatus = $wanStatus;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = SystemStats::orderBy('timestamp', 'desc')->first();
        // if the server type is a SiteGate, take sha1 hash of MAC address and return as SiteGate identifier
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            $settings['sitegateId'] = sha1($this->wanStatus->getMacAddress());
        }
        $settings['uiVersion'] = $this->getUiVersion(base_path());
        $settings['cswapiVersion'] = $this->getCswapiVersion(env('CSWAPI_ROOT'));

        $settings['timezone'] = getenv("TIMEZONE");
        $settings['timezoneOptions'] = $this->timezoneOptions();

        Log::info("Settings:\n".print_r($settings, true));


        return [
            'data' => $settings
        ];
    }

    protected function timezoneOptions() {

        $output = [];
        $timezones = Timezone::all();
        return $timezones;
    }

    public function setTimeZone() {
        $tz = Input::get('timezone');

        $tz = preg_replace('/string:/', '', $tz);

        // TODO - call artisan command to set timezone
        Log::info("New Timezone: $tz");

        Artisan::call('timezone:set', ['timezone' => $tz]);
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

    public function reboot() {
        if(Input::get('rebootConfirm') == 'REBOOT') {
            CommandHelper::CallWrapper('reboot');
        }
    }

    public function resetGuacamole() {
        if (Request::isMethod('post')) {
            CommandHelper::CallWrapper('resetguest');
        }
    }

    // TODO This function should be part of the SystemInfo model.
    public function getUIVersion($path) {
        if (file_exists(base_path()."/version")) {
            return trim(file_get_contents(base_path()."/version"));
        } else {
            return "Unknown";
        }

/*        $version = exec('svn info ' . $path . ' | grep Relative');
        preg_match("/[^\/]+$/", $version, $matches);
        $version = explode('-', $matches[0]);
        $version = ucfirst($version[0]) . ' ' . ucfirst($version[1]);
        $revision = exec('svn info ' . $path . ' | grep Revision | cut -d\' \' -f 2');
        return $version . ' (R' . $revision . ')';
*/
    }

    private function getCswapiVersion($path) {
        $version = "unknown";
        if (file_exists(env('CSWAPI_ROOT') . "/version")) {
            $version = trim(file_get_contents(env('CSWAPI_ROOT') . "/version"));
        }

        return $version;
    }



}
