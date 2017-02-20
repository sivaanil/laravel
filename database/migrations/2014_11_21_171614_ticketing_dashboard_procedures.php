<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class TicketingDashboardProcedures extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            // drop existing
            $this->down();
            // Drop the procedures if they already exist (idempotencty)
            $procedures = "DROP PROCEDURE IF EXISTS fill_unack_graph;
                            DROP PROCEDURE IF EXISTS fill_unres_graph;
                            DROP PROCEDURE IF EXISTS fill_wait_graph;
                            DROP PROCEDURE IF EXISTS fill_over_graph;
                            DROP PROCEDURE IF EXISTS fill_calendar;
                            DROP PROCEDURE IF EXISTS fill_avg_metrics_data;";

           //
            $procedures .= "CREATE PROCEDURE fill_unack_graph(mod_interval INT, nodeId INT)
                            BEGIN
                            DECLARE iter INT;
                            SET iter = 1;
                            WHILE iter < 8 DO
                                    INSERT INTO tmp_unack_graph (less_time, day_tickets, hour_tickets)
                                    SELECT 	iter*mod_interval AS less_time,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_created) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval DAY)
                                                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) DAY)
                                                                            AND tth.css_ticketing_ticket_status_id = 1 AND tth.acknowledged = 0
                                            )  AS day_tickets,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_created) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval HOUR)
                                                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) HOUR)
                                                                            AND tth.css_ticketing_ticket_status_id = 1 AND tth.acknowledged = 0
                                            )  AS hour_tickets;
                                    SET iter = iter+1;
                            END WHILE;
                            END ;

                            CREATE PROCEDURE fill_unres_graph(mod_interval INT, nodeId INT)
                            BEGIN
                            DECLARE iter INT;
                            SET iter = 1;
                            WHILE iter < 8 DO
                                    INSERT INTO tmp_unres_graph (less_time, day_tickets, hour_tickets)
                                    SELECT 	iter*mod_interval AS less_time,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_created) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval DAY)
                                                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) DAY)
                                                                            AND tth.css_ticketing_ticket_status_id <> 2 AND tth.acknowledged = 1
                                            )  AS day_tickets,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_created) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval HOUR)
                                                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) HOUR)
                                                                            AND tth.css_ticketing_ticket_status_id <> 2 AND tth.acknowledged = 1
                                            )  AS hour_tickets;
                                    SET iter = iter+1;
                            END WHILE;
                            END ;

                            CREATE PROCEDURE fill_wait_graph(mod_interval INT, nodeId INT)
                            BEGIN
                            DECLARE iter INT;
                            SET iter = 1;
                            WHILE iter < 8 DO
                                    INSERT INTO tmp_wait_graph (less_time, day_tickets, hour_tickets)
                                    SELECT 	iter*mod_interval AS less_time,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_updated) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval DAY)
                                                                            AND DATE(tth.date_updated) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) DAY)
                                                                            AND tth.css_ticketing_ticket_status_id = 4 AND tth.acknowledged = 1
                                            )  AS day_tickets,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_updated) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval HOUR)
                                                                            AND DATE(tth.date_updated) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) HOUR)
                                                                            AND tth.css_ticketing_ticket_status_id = 4 AND tth.acknowledged = 1
                                            )  AS hour_tickets;
                                    SET iter = iter+1;
                            END WHILE;
                            END ;

                            CREATE PROCEDURE fill_over_graph(mod_interval INT, nodeId INT)
                            BEGIN
                            DECLARE iter INT;
                            SET iter = 1;
                            WHILE iter < 8 DO
                                    INSERT INTO tmp_over_graph (less_time, day_tickets, hour_tickets)
                                    SELECT 	iter*mod_interval AS less_time,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_updated) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval DAY)
                                                                            AND DATE(tth.date_updated) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) DAY)
                                                                            AND tth.css_ticketing_ticket_status_id <> 2 AND tth.acknowledged = 1
                                            )  AS day_tickets,
                                            (
                                                    SELECT COUNT(*)
                                                    FROM css_ticketing_ticket_header tth
                                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                                    WHERE DATE(tth.date_updated) <= DATE_SUB(CURDATE(), INTERVAL iter*mod_interval HOUR)
                                                                            AND DATE(tth.date_updated) > DATE_SUB(CURDATE(), INTERVAL mod_interval*(iter+1) HOUR)
                                                                            AND tth.css_ticketing_ticket_status_id <> 2 AND tth.acknowledged = 1
                                            )  AS hour_tickets;
                                    SET iter = iter+1;
                            END WHILE;
                            END ;

                            CREATE PROCEDURE fill_calendar(start_date DATE, end_date DATE)
                            BEGIN
                              DECLARE crt_date DATE;
                              SET crt_date=start_date;
                              WHILE crt_date < end_date DO
                                INSERT INTO tmp_calendar VALUES(crt_date);
                                SET crt_date = ADDDATE(crt_date, INTERVAL 1 DAY);
                              END WHILE;
                            END ;

                            CREATE PROCEDURE fill_avg_metrics_data(metrics_interval INT, nodeId INT)
                            BEGIN

                            INSERT INTO tmp_avg_metrics_data (metricName, number)
                                    SELECT 'Average time to acknowledge a ticket:' AS metricName,
                                            CASE WHEN SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, tth.date_created, tth.acknowledged_date))) IS NULL
                                                    THEN '0'
                                                    ELSE SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, tth.date_created, tth.acknowledged_date)))
                                            END number
                                    FROM css_ticketing_ticket_header tth
                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                    WHERE tth.acknowledged = 1
                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL metrics_interval DAY);

                            INSERT INTO tmp_avg_metrics_data (metricName, number)
                                    SELECT 'Average time to close a ticket:' AS metricName,
                                            CASE WHEN SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, tth.date_created, tth.date_resolved))) IS NULL
                                                    THEN '0'
                                                    ELSE SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, tth.date_created, tth.date_resolved)))
                                            END number
                                    FROM css_ticketing_ticket_header tth
                                    INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                    INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                    WHERE tth.css_ticketing_ticket_status_id = 2
                                            AND DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL metrics_interval DAY);

                            INSERT INTO tmp_avg_metrics_data (metricName, number)
                                    SELECT 'Average steps to resolve a ticket:' AS metricName,
                                            CASE WHEN ROUND(AVG(a.Stages), 1) IS NULL
                                                    THEN '0'
                                                    ELSE ROUND(AVG(a.Stages), 1)
                                            END number
                                    FROM (
                                            SELECT MAX(tts.stage_id) AS Stages
                                            FROM css_ticketing_ticket_header tth
                                            INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                            INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                            LEFT JOIN css_ticketing_ticket_stages tts ON (tth.id = tts.ticket_id)
                                            WHERE DATE(tth.date_created) > DATE_SUB(CURDATE(), INTERVAL metrics_interval DAY)
                                                    AND tth.date_resolved IS NOT NULL
                                            GROUP BY tts.ticket_id
                                            ) a;

                            INSERT INTO tmp_avg_metrics_data (metricName, number)
                                    SELECT 'Average number of tickets opened a day:' AS metricName,
                                            CASE WHEN ROUND(AVG(a.Opened), 1) IS NULL
                                                    THEN '0'
                                                    ELSE ROUND(AVG(a.Opened), 1)
                                            END average
                                    FROM (
                                            SELECT COUNT(tth.date_created) AS Opened, c.datefield AS DATE
                                            FROM css_ticketing_ticket_header tth
                                            INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                            INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                            RIGHT JOIN tmp_calendar c ON (DATE(tth.date_created) = c.datefield)
                                            GROUP BY DATE
                                            ) a;

                            INSERT INTO tmp_avg_metrics_data (metricName, number)
                                    SELECT 'Average number of tickets closed a day:' AS metricName,
                                            CASE WHEN ROUND(AVG(a.Closed), 1) IS NULL
                                                    THEN '0'
                                                    ELSE ROUND(AVG(a.Closed), 1)
                                            END average
                                    FROM (
                                            SELECT COUNT(tth.date_resolved) as Closed, c.datefield AS DATE
                                            FROM css_ticketing_ticket_header tth
                                            INNER JOIN css_networking_network_tree nt ON (tth.node_id = nt.id)
                                            INNER JOIN css_networking_network_tree_map tm ON (tm.node_id = nt.id AND tm.node_map LIKE CONCAT('%.',nodeId,'.%'))
                                            RIGHT JOIN tmp_calendar c ON (DATE(tth.date_resolved) = c.datefield)
                                            GROUP BY DATE
                                            ) a;
                            END ;";

            DB::connection()->getPdo()->exec($procedures);
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            //
            $procedures = "DROP PROCEDURE IF EXISTS fill_unack_graph;
                            DROP PROCEDURE IF EXISTS fill_unres_graph;
                            DROP PROCEDURE IF EXISTS fill_wait_graph;
                            DROP PROCEDURE IF EXISTS fill_over_graph;
                            DROP PROCEDURE IF EXISTS fill_calendar;
                            DROP PROCEDURE IF EXISTS fill_avg_metrics_data;";

            DB::connection()->getPdo()->exec($procedures);
        }

    }
