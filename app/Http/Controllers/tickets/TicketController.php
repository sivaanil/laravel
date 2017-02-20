<?php

    class TicketController extends \BaseController
    {

        public function show($nodeId)
        {
            $nodeInfo = null;
            $name = "dashboard";
            $view = 'tickets/dashboard';

            return GeneralHelper::makeWithExtras($view, $nodeInfo, $nodeId, $name);
        }

        public function getPref($int)
        {
            $userId = Auth::user()->id;
            $varName = "ticketDashboard";
            $pref = DB::table('css_authentication_user_pref')
                ->where('user_id', '=', $userId)
                ->where('variable_name', '=', $varName)
                ->get();
            if (! empty($pref)) {
                $prefArray = implode(",", $pref);
                $value = $prefArray[$int];
            } else {
                $value = 7;
            }

            return $value;
        }

        public function getUnackData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $int = filter_input(INPUT_GET, 'int', FILTER_SANITIZE_NUMBER_INT);
            if (empty($int)) {
                $int = static::getPref(0);
            }
            Schema::dropIfExists('tmp_unack_graph');
            Schema::create('tmp_unack_graph', function ($table) {
                $table->string('less_time', 50);
                $table->string('day_tickets', 11);
                $table->string('hour_tickets', 11);
            });
            DB::statement(DB::raw('CALL fill_unack_graph(' . $int . ',' . $node . ');'));
            $tickets = DB::table('tmp_unack_graph')->get();

            return json_encode($tickets);
        }

        public function getUnresolvedData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $int = filter_input(INPUT_GET, 'int', FILTER_SANITIZE_NUMBER_INT);
            if (empty($int)) {
                $int = static::getPref(1);
            }
            Schema::dropIfExists('tmp_unres_graph');
            Schema::create('tmp_unres_graph', function ($table) {
                $table->string('less_time', 50);
                $table->string('day_tickets', 11);
                $table->string('hour_tickets', 11);
            });
            DB::statement(DB::raw('CALL fill_unres_graph(' . $int . ',' . $node . ');'));
            $tickets = DB::table('tmp_unres_graph')->get();

            return json_encode($tickets);
        }

        public function getWaitData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $int = filter_input(INPUT_GET, 'int', FILTER_SANITIZE_NUMBER_INT);
            if (empty($int)) {
                $int = static::getPref(2);
            }
            Schema::dropIfExists('tmp_wait_graph');
            Schema::create('tmp_wait_graph', function ($table) {
                $table->string('less_time', 50);
                $table->string('day_tickets', 11);
                $table->string('hour_tickets', 11);
            });
            DB::statement(DB::raw('CALL fill_wait_graph(' . $int . ',' . $node . ');'));
            $tickets = DB::table('tmp_wait_graph')->get();

            return json_encode($tickets);
        }

        public function getOverdueData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $int = filter_input(INPUT_GET, 'int', FILTER_SANITIZE_NUMBER_INT);
            if (empty($int)) {
                $int = static::getPref(3);
            }
            Schema::dropIfExists('tmp_over_graph');
            Schema::create('tmp_over_graph', function ($table) {
                $table->string('less_time', 50);
                $table->string('day_tickets', 11);
                $table->string('hour_tickets', 11);
            });
            DB::statement(DB::raw('CALL fill_over_graph(' . $int . ',' . $node . ');'));
            $tickets = DB::table('tmp_over_graph')->get();

            return json_encode($tickets);
        }

        public function getAverageData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $int = filter_input(INPUT_GET, 'int', FILTER_SANITIZE_NUMBER_INT);
            if (empty($int)) {
                $int = static::getPref(4);
            }
            Schema::dropIfExists('tmp_calendar');
            Schema::create('tmp_calendar', function ($table) {
                $table->string('datefield', 20);
            });
            DB::statement(DB::raw('CALL fill_calendar(DATE_SUB(CURDATE(), INTERVAL ' . $int . ' DAY), DATE_ADD(CURDATE(), INTERVAL 1 DAY));'));

            Schema::dropIfExists('tmp_avg_metrics_data');
            Schema::create('tmp_avg_metrics_data', function ($table) {
                $table->string('number', 11);
                $table->string('metricName', 100);
            });
            DB::statement(DB::raw('CALL fill_avg_metrics_data(' . $int . ',' . $node . ');'));
            $tickets = DB::table('tmp_avg_metrics_data')->get();

            return json_encode($tickets);
        }

        public function getPriorityData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $tickets = DB::table('css_ticketing_ticket_header AS tth')
                ->selectRaw("CONCAT(ttp.description, ' tickets:') AS metricName, COUNT(tth.id) AS number")
                ->join('css_ticketing_ticket_priority AS ttp', 'tth.css_ticketing_ticket_priority_id', '=', 'ttp.id')
                ->join('css_networking_network_tree AS nt', 'tth.node_id', '=', 'nt.id')
                ->join('css_networking_network_tree_map AS tm', 'tm.node_id', '=', 'nt.id')
                ->where('tth.css_ticketing_ticket_status_id', '<>', 2)
                ->where('tm.node_map', 'like', DB::raw("CONCAT('%.',$node, '.%')"))
                ->groupBy('tth.css_ticketing_ticket_priority_id')
                ->get();

            return json_encode($tickets);
        }

        public function getStatusData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $tickets = DB::table('css_ticketing_ticket_header AS tth')
                ->selectRaw("CONCAT('Current number of ', tts.description, ' tickets:') AS metricName, COUNT(tth.id) AS number")
                ->join('css_ticketing_ticket_status AS tts', 'tth.css_ticketing_ticket_status_id', '=', 'tts.id')
                ->join('css_networking_network_tree AS nt', 'tth.node_id', '=', 'nt.id')
                ->join('css_networking_network_tree_map AS tm', 'tm.node_id', '=', 'nt.id')
                ->where('tth.css_ticketing_ticket_status_id', '<>', 2)
                ->where('tm.node_map', 'like', DB::raw("CONCAT('%.',$node, '.%')"))
                ->groupBy('tth.css_ticketing_ticket_status_id')
                ->get();

            return json_encode($tickets);
        }

        public function getUserData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $tickets = DB::table('css_ticketing_ticket_header AS tth')
                ->selectRaw("CONCAT('Tickets assigned to user ', CONCAT(au.first_name, ' ', au.last_name)) AS metricName, COUNT(tth.id) AS number")
                ->join('css_authentication_user AS au', 'tth.assigned_to_user_id', '=', 'au.id')
                ->join('css_networking_network_tree AS nt', 'tth.node_id', '=', 'nt.id')
                ->join('css_networking_network_tree_map AS tm', 'tm.node_id', '=', 'nt.id')
                ->where('tth.css_ticketing_ticket_status_id', '<>', 2)
                ->where('tm.node_map', 'like', DB::raw("CONCAT('%.',$node, '.%')"))
                ->groupBy('tth.assigned_to_user_id')
                ->get();

            return json_encode($tickets);
        }

        public function getPolicyData()
        {
            $node = filter_input(INPUT_GET, 'nodeId', FILTER_SANITIZE_NUMBER_INT);
            $tickets = DB::table('log_ticketing_notification_queue AS nq')
                ->selectRaw("nq.ticket_id, tth.subject, tep.policy_name, FLOOR(HOUR(TIMEDIFF(NOW(), nq.send_date)) / 24) AS tte_days,
                        MOD(HOUR(TIMEDIFF(NOW(), nq.send_date)), 24) AS tte_hours, MINUTE(TIMEDIFF(NOW(), nq.send_date)) AS tte_minutes")
                ->join('css_ticketing_ticket_header AS tth', 'nq.ticket_id', '=', 'tth.id')
                ->join('css_ticketing_escalation_info AS tei', 'nq.info_id', '=', 'tei.id')
                ->join('css_ticketing_escalation_policies AS tep', 'tei.policy_id', '=', 'tep.id')
                ->join('css_networking_network_tree AS nt', 'tth.node_id', '=', 'nt.id')
                ->join('css_networking_network_tree_map AS tm', 'tm.node_id', '=', 'nt.id')
                ->where('nq.sent', 'IS', 'NULL')
                ->where('nq.cleared', '=', '0')
                ->where('nq.send_date', '>', 'NOW()')
                ->where('tep.isEscalation', '=', '1')
                ->where('tm.node_map', 'like', DB::raw("CONCAT('%.',$node, '.%')"))
                ->orderBy('nq.send_date', 'ASC')
                ->get();

            // Format the time to escalation
            for ($i = 0; $i < 24; $i ++) {
                $d = null;
                $h = null;
                $m = null;
                if (! empty($tickets[$i]->tte_days)) {
                    $d = $tickets[$i]->tte_days . ' Days ';
                }
                if (! empty($tickets[$i]->tte_hours)) {
                    $h = $tickets[$i]->tte_hours . ' Hours ';
                }
                if (! empty($tickets[$i]->tte_minutes)) {
                    $m = $tickets[$i]->tte_minutes . ' Minutes';
                }
                if (! empty($tickets[$i]->ticket_id)) {
                    $tickets[$i]->total_tte = $d . $h . $m;
                }
            }

            return json_encode($tickets);
        }


    }