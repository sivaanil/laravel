<?php namespace Unified\Http\Helpers;

use Auth;
use DB;
use URL;
use View;
use Module;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Models\NetworkTreeMap;
use Unified\Models\NetworkTree;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Extras include getting the breadcrumb and setting up the navbar
class GeneralHelper
{
    public static function makeWithExtras($destination, $var, $nodeId, $destinationName)
    {
        //This makes it so all blades have access to the breadcrumb, nodeId and
        //to get the nav bar configured correctly based on the users selected node
        $var['breadcrumb'] = GeneralHelper::getNodeBreadcrumb($nodeId);
        $var['navBarSettings'] = GeneralHelper::getNavBarSettings($nodeId, $destinationName);
        $var['nodeId'] = $nodeId;
        $var['activePage'] = $destinationName;

        //$var['navBarSettings2'] = json_encode(GeneralHelper::getNavBarSettings($nodeId, $destinationName));
        return View::make($destination, $var);
    }

    public static function getNodeMap($nodeId, $returnType)
    {
        $node = NetworkTreeMap::where('node_id', '=', $nodeId)
                ->first();

        if ($node) {

            if ($returnType == 'string') {
                return $node->node_map;
            }
            if ($returnType == 'array') {
                $nodeIds = explode('.', trim($node->node_map, '.'));

                return $nodeIds;
            }
        } else {
            $returnType == 'string' ? '' : [];
        }
    }

    public static function getNodeBreadcrumb($nodeId)
    {
        $nodeIds = GeneralHelper::getNodeMap($nodeId, 'array');

        $nodes = NetworkTree::breadcrumbMap($nodeIds);
        //var_dump($nodes);
        $homeNode = Auth::user()->home_node_id;
        $hyperlinkBreadcrumb = GeneralHelper::makeBreadcrumbHyperlinked($nodes, $homeNode, $nodeId);

        return $hyperlinkBreadcrumb;
        $mapNode = NetworkTreeMap::where('node_id', '=', $nodeId)->get();
        //echo count($mapNode);
        //var_dump($mapNode[0]->breadcrumb);
        return $mapNode[0]->breadcrumb;
    }

    /*
     * Create the nav bar based on the selected node.
     * dispName is what is displayed
     * navigateTo is the like to the next page
     * active is the page the user is currently on
     */
    public static function getNavBarSettings($nodeId, $destinationName)
    {
        $node = NodeHelper::getNodeNameAndType($nodeId);
        //echo $destinationName;
        $pages = array();
        /*
        if ($node['isGroup'] == 1) {
        */
            $pages[] = array('dispName'   => trans('menuArea.selection'),
                             'navigateTo' => "#/stateChange/nodes",
                             'active'     => $destinationName == 'selction' ? true : false,
                             'enabled'    => true
            );
            $pages[] = array('dispName'   => trans('menuArea.alarm'),
                             'navigateTo' => "#/stateChange/alarms",
                             'active'     => $destinationName == 'alarms' ? true : false,
                             'enabled'    => true
            );
            $pages[] = array('dispName'   => trans('menuArea.deviceInfo'),
                'navigateTo' => "#/stateChange/deviceInfo",
                'active'     => $destinationName == 'deviceInfo' ? true : false,
                'enabled'    => true
            );
/* 			$pages[] = array('dispName'   => 'Camera',
                'navigateTo' => "#/stateChange/cameraInfo",
                'active'     => $destinationName == 'cameraInfo' ? true : false,
                'enabled'    => true,
                'class' => 'cameraInfo'
            );
*/
            if (env('C2_SERVER_TYPE') == 'sitegate') {
                $pages[] = array('dispName' => trans('menuArea.wanSettings'),
                    'navigateTo' => "#/stateChange/wanSettings",
                    'active' => $destinationName == 'wanSettings' ? true : false,
                    'enabled' => true
                );
                $pages[] = array('dispName' => trans('menuArea.lanSettings'),
                    'navigateTo' => "#/stateChange/lanSettings",
                    'active' => $destinationName == 'lanSettings' ? true : false,
                    'enabled' => true
                );
                $pages[] = array('dispName'   => trans('menuArea.systemSettings'),
                    'navigateTo' => "#/stateChange/systemSettings",
                    'active'     => $destinationName == 'systemSettings' ? true : false,
                    'enabled'    => true
                );


/*                $snmpModule = Module::find('snmpforward');
                if ($snmpModule && $snmpModule->active()) {
                    $pages[] = [
                        'dispName'   => trans('menuArea.snmpforward'),
                        'navigateTo' => '#/stateChange/snmpforward',
                        'active'     => $destinationName == 'snmpforward' ? true : false,
                        'enabled'    => true,
                    ];
                }
*/
/*                $pages[] = [
                    'dispName'   => trans('menuArea.sensors'),
                    'navigateTo' => '#/stateChange/sensors',
                    'active'     => $destinationName == 'sensors' ? true : false,
                    'enabled'    => true,
                ];
*/
            $pages[] = array('dispName'   => trans('menuArea.location'),
                             'navigateTo' => "/device/$nodeId/location",
                             'active'     => $destinationName == 'location' ? true : false,
                             'enabled'    => false
            );
            //$pages[] = array('dispName'=>trans('menuArea.dashboard'), 'navigateTo'=>"/tickets/$nodeId/dashboard", 'active' =>$destinationName=='dashboard'?true:false, 'enabled'=>true);
            /*
        } else {
            $actionList = NodeHelper::getAvailableActions($nodeId);
            $pages[] = array('dispName'   => trans('menuArea.selection'),
                             'navigateTo' => "/nodes/$nodeId",
                             'active'     => $destinationName == 'selction' ? true : false,
                             'enabled'    => true
            );
            $pages[] = array(
                'dispName'   => trans('menuArea.actions'),
                'navigateTo' => "/DeviceActions/$nodeId",
                'active'     => $destinationName == 'actions' ? true : false,
                'enabled'    => false,
                'children'   => $actionList['buttonList']
            );
            $pages[] = array('dispName'   => trans('menuArea.alarm'),
                             'navigateTo' => "/alarms/$nodeId",
                             'active'     => $destinationName == 'alarms' ? true : false,
                             'enabled'    => true
            );
            $pages[] = array('dispName'   => trans('menuArea.info'),
                             'navigateTo' => "/device/$nodeId",
                             'active'     => $destinationName == 'devInfo' ? true : false,
                             'enabled'    => false
            );
            $pages[] = array('dispName'   => trans('menuArea.location'),
                             'navigateTo' => "/device/$nodeId/location",
                             'active'     => $destinationName == 'location' ? true : false,
                             'enabled'    => false
            );
            $pages[] = array('dispName'   => trans('menuArea.properties'),
                             'navigateTo' => "/device/$nodeId/state/props",
                             'active'     => $destinationName == 'props' ? true : false,
                             'enabled'    => false
            );
            $pages[] = array('dispName'   => trans('menuArea.scantimes'),
                             'navigateTo' => "/device/$nodeId/scanTimes",
                             'active'     => $destinationName == 'scanTimes' ? true : false,
                             'enabled'    => false
            );
            $pages[] = array('dispName'   => trans('menuArea.status'),
                             'navigateTo' => "/device/$nodeId/state/stats",
                             'active'     => $destinationName == 'stats' ? true : false,
                             'enabled'    => false
            );
            //$pages[] = array('dispName'=>trans('menuArea.dashboard'), 'navigateTo'=>"/tickets/$nodeId/dashboard", 'active' =>$destinationName=='dashboard'?true:false, 'enabled'=>true);
        }
            */
        }
        //var_dump($pages);
        $pages[] = array('dispName'   => trans('menuArea.logout'),
                         'navigateTo' => url() . "/logout",
                         'active'     => false,
                         'enabled'    => true
        );
        // prepend URL prefix
        foreach ($pages as &$page) {
            $page['navigateTo'] = $page['navigateTo']; // prepend the # for UI-Router
        }

        return $pages;
    }

    /**
     * Clear all alarms on the given node and all of its children
     *
     * @param  int $nodeId
     *
     * @return boolean $success
     */
    public static function clearAlarms($nodeId, $notes)
    {
        /*
         * TODO replace below query with this
         * SELECT
    da.*
from
    css_networking_device_alarm da
INNER JOIN
(
SELECT
            nt.device_id
from
            css_networking_network_tree nt
Inner join css_networking_network_tree_map ntm on ntm.node_id = nt.id and ntm.node_map like '%.321.%'
            and ntm.deleted = 0        and ntm.build_in_progress = 0        and ntm.visible = 1
) as tmp ON tmp.device_id = da.device_id
         * where da.cleared is null;
*/
        //echo"<br/>$nodeId<br/>";
        $searchRes = DeviceAlarm::clearAlarms($nodeId);
        for ($i = 0; $i < count($searchRes); $i ++) {
            //var_dump($searchRes[$i]->id) ;
            $alarm = DeviceAlarm::find($searchRes[$i]->id);
            //var_dump($alarm);
            $alarm->cleared = date("Y-m-d H:i:s");
            if (strlen($notes) > 0) {
                $alarm->notes .= " \n ------------------------ \n " . $notes;
                $alarm->has_notes = 1;
            }
            GeneralHelper::deleteTicketWithAlarmId($alarm->id);
            $alarm->save();
        }

        return;
        //var_dump($searchRes);
    }

    // TODO - almost certain we don't do ticketing in the application currently, and this is
    // cruft that needs to be removed. Excluding this from UN-80 because it shouldbe deleted.
    public static function deleteTicketWithAlarmId($alarmId)
    {
        //echo "Delete Called";
        $ticketHeader = DB::table('css_ticketing_ticket_header as th')
            ->where('th.ticket_alarm_id', '=', "$alarmId")
            ->get();
        $cnt = count($ticketHeader);
        //echo $cnt;
        if ($cnt > 0) {
            for ($i = 0; $i < $cnt; $i ++) {
                if ($ticketHeader[$i]->date_created == $ticketHeader[$i]->date_updated) {
                    $ticketId = $ticketHeader[$i]->id;
                    DB::delete("DELETE FROM css_ticketing_ticket_header WHERE ticket_alarm_id = '$alarmId");
                    DB::delete("DELETE FROM css_ticketing_ticket_details WHERE css_ticketing_ticket_header_id = '$ticketId'");
                    DB::delete("DELETE FROM css_ticketing_ticket_stages WHERE ticket_id = '$ticketId'");
                }
            }
        }
    }

    // Execute a command as a background process
    public static function ExecBackground($path, $program, $file, $args = "")
    {
        //CssUtil::DebugPrint("Preparing command: path-> $path program-> $program file-> $file args-> $args");
        // Make sure the program exists at the given path
        if (file_exists($path . $program)) {

            // Change the working directory to the path
            chdir($path);

            // Build the command string
            $commandString = $program . " \"$file\" " . escapeshellarg($args);

            // Launch the process
            $pid = GeneralHelper::PsExecute($commandString);

            return $pid;
        } else {
            echo "$path$program Doesn't exist!?!";
        }
    }

    // Execute a command line process
    public static function PsExecute($command, $timeout = 60, $sleep = 2)
    {

        // First, execute the process, get the process ID
        $pid = GeneralHelper::PsExec($command);

        // If we got no PID back, it failed to launch. Return false
        if ($pid === false) {
            return false;
        }

        return $pid;
    }

    // Execute a command line process, returning the process ID
    private static function PsExec($command)
    {

        // Setup the paths
        if (PHP_OS == 'Linux') {
            //  CssUtil::DebugPrint("Running command: $command");
            $commandJob = $command . ' > /dev/null 2>&1 & echo $!';
            //echo $commandJob;
            exec($commandJob, $op);
            $pid = (int) $op[0];
            if ($pid != "") {
                return $pid;
            }
        } else {
            $tmp = substr(__FILE__, strpos(__FILE__, "/cswapi") + 1);
            $rootDir = substr($tmp, 0, strpos($tmp, "/"));

            $binPath = CssUtil::GetFullPath($rootDir . "\\bin\pstools\\");
            $execPath = "\"" . $binPath . "psexec.exe\" -s -d -accepteula $command  2>&1";


            echo "Executing: " . $execPath;

            // Execute the command using psxec.exe
            exec("\"" . $execPath . "\"", $output);

            // For each line of output...
            foreach ($output as $row) {
                //CssUtil::DebugPrint("\t>" . $row);
                // See if the process ID has been output
                $found = stripos($row, 'with process ID ');
                if ($found) {
                    $pid = preg_replace('/[^0-9]+/', '', $row);
                    echo "Started PID: " . $pid;

                    return $pid;
                }
            }
        }

        return false;
    }

    /*Get the html color for the passed alarm severity
     *
     * $severity = int alarm severity
     */
    public static function getColorFromSeverity($severity, $isheartbeat = 0)
    {
        //This uses the same color scheme as reports.
        $color = array();
        /*if($isheartbeat==1){
            $color['bg'] = "F5F5DC";
            return $color;
        }*/
        switch ($severity) {
            case 1:
                $color['bg'] = "FF4c4c";
                break;
            case 2:
                $color['bg'] = "FFCC77";
                break;
            case 3:
                $color['bg'] = "FFFF00";
                break;
            case 4:
                $color['bg'] = "A8BEFF";
                break;
            case 5:
                $color['bg'] = "666666";
                break;
            case 6:
                $color['bg'] = "B7FFFF";
                break;
            default:
                $color['bg'] = "FFFFFF";
                break;
        }

        return $color;
    }

    /*Get the html color for the passed alarm severity
 *
 * $severity = int alarm severity
 */
    public static function getNameFromSeverity($severity, $isheartbeat = 0)
    {
        //This is currently used to distinguish foldercolor on node selection
        $sev = array();

        switch ($severity) {
            case -2:
                $sev['severity'] = "loading";
                break;
            case 0:
                $sev['severity'] = "critical";
                break;
            case 1:
                $sev['severity'] = "critical";
                break;
            case 2:
                $sev['severity'] = "major";
                break;
            case 3:
                $sev['severity'] = "minor";
                break;
            case 4:
                $sev['severity'] = "warning";
                break;
            case 5:
                $sev['severity'] = "ignored";
                break;
            case 6:
                $sev['severity'] = "information";
                break;
            case 100:
                $sev['severity'] = "clear";
                break;
            default:
                $sev['severity'] = "clear";
                break;
        }

        return $sev;
    }

    /*based on the number of rows, current page and count pet page
    * config which pages are acceptable to put in pagination list.
     * Always display 1,2,3 current -1, current, current +1 and last
     * add ... if there is a gap
     * $rows = int number of rows that are in the list
     * $page = int the current page
     * $count = int number of rows per page
     */

    public static function acceptablePages($rows, $page, $count)
    {
        $okPages = array();
        $totalPages = ceil($rows / $count);
        if ($totalPages < 2) {
            return false;
        }  //there is only 1 page
        $okPages[] = 1;
        $okPages[] = 2;
        if ($totalPages < 3) {
            return $okPages;
        }  //there is only 1 page
        $okPages[] = 3;
        if ($totalPages < 4) {
            return $okPages;
        }  //there is only 1 page
        if ($totalPages == 4) {
            $okPages[] = 4;

            return $okPages;
        } else if ($totalPages == 5) {
            $okPages[] = 4;
            $okPages[] = 5;

            return $okPages;
        }
        if ($page == 3) {
            $okPages[] = 4;
        }
        if ($page == 4) {
            $okPages[] = 4;
            $okPages[] = 5;
        }
        $okPages[] = "...";
        //there are atleast 6 pages.
        if ($page > 4 && $page + 2 < $totalPages) {
            $okPages[] = $page - 1; //previous page
            $okPages[] = $page; //current page
            $okPages[] = $page + 1; //next page
            if ($totalPages == $page || $totalPages == $page + 1) {
                return $okPages;
            }// per are on the last or 2nd to last page
        } elseif ($page + 2 == $totalPages) {
            $okPages[] = $page - 1; //previous page
            $okPages[] = $page; //current page
        } elseif ($page + 1 == $totalPages) {
            $okPages[] = $page - 1; //previous page
        }

        if ($totalPages != $page + 1 && $totalPages != $page + 2 && $totalPages != $page && $page > 4) {
            $okPages[] = "...";
            $okPages[] = $totalPages - 1; //the last row is ok
            $okPages[] = $totalPages; //the last row is ok
        } else {
            $okPages[] = $totalPages - 1; //the last row is ok
            $okPages[] = $totalPages; //the last row is ok
        }

        return $okPages;
    }

    public static function makeBreadcrumbHyperlinked($nodes, $homeNode, $currentNode)
    {
        //For device specific Views clicking on a group will give an error. Validation will be needed to redirect users to /Nodeselection/$nodeId
        //The same validation will be needed to handle users trying to navigate to invalide nodes or nodes outside of their tree
        $shortbreadCrumb = "";
        $validPart = false;
        $breadcrumbList = array();
        $i = 0;
        foreach ($nodes as $node) {
            if ($homeNode == $node->node_id) {
                $validPart = true;
            }
            if ($validPart) {
                $link = URL::current();
                $bcLink = $node->node_id;
                $breadcrumbList[$i]['name'] = $node->name;
                $breadcrumbList[$i]['hyperlink'] = $bcLink;
                $i ++;
            }
        }

        return $breadcrumbList;
    }

    public static function getSystemTimeZone() {
        $result = shell_exec('timedatectl');

        $tz = null;
        $matches = [];

        if (preg_match('/timezone\:(.*?)\n/i', $result, $matches) === 1) {
            $tz = trim($matches[1]);
        }

        return $tz;
    }
}
