<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class EventGenerator
    {

        //these vars keep track of number of event calls and provide a failsafe in the case of recursive events
        private static $callRecord = array();
        private static $eventRun = false;

        private static function eventPermitted($event)
        {

            $threshold = Config::get('events.max_events_per_request');

            if (array_key_exists($event, static::$callRecord)) {
                if (static::$callRecord[$event] > $threshold) {
                    Log::error("Event '$event' has exceeded " . $threshold . " calls. Terminating event process.");

                    return false;
                } else {
                    static::$callRecord[$event] ++;
                }
            } else {
                static::$callRecord[$event] = 1;
            }
            static::$eventRun = true;

            return true;
        }

        public static function getPrintableEventStatus()
        {
            $s = "Event calls during this request:\n";
            foreach (static::$callRecord as $key => $value) {
                $s .= "\tEvent " . str_pad("'$key'", 12) . " called $value times\n";
            }

            return $s;
        }

        public static function eventsOccured()
        {
            return static::$eventRun;
        }

        //actions (incoming)

        public static function AddedUser($user)
        {
            $timer = new EventMetric(C2Event::ADD_USER);
            if (! static::eventPermitted(C2Event::ADD_USER)) {
                return;
            }
            Event::fire(C2Event::ADD_USER, array($user));
        }

        public static function SelectedNode($node)
        {
            $timer = new EventMetric(C2Event::SELECT_NODE);
            if (! static::eventPermitted(C2Event::SELECT_NODE)) {
                return;
            }
            Event::fire(C2Event::SELECT_NODE, array($node));
        }

        public function ModifiedNode($old, $new)
        {
            $timer = new EventMetric(C2Event::MODIFY_NODE);
            if (! static::eventPermitted(C2Event::MODIFY_NODE)) {
                return;
            }
            Event::fire(C2Event::MODIFY_NODE, array($old, $new));
        }

        public function ModifiedDevice($old, $new)
        {
            $timer = new EventMetric(C2Event::MODIFY_DEVICE);
            if (! static::eventPermitted(C2Event::MODIFY_DEVICE)) {
                return;
            }
            Event::fire(C2Event::MODIFY_DEVICE, array($old, $new));
        }

        public function ModifiedGroup($old, $new)
        {
            $timer = new EventMetric(C2Event::MODIFY_GROUP);
            if (! static::eventPermitted(C2Event::MODIFY_GROUP)) {
                return;
            }
            Event::fire(C2Event::MODIFY_GROUP, array($old, $new));
        }


        //manually specify event to fire
        public static function DispatchEvent($event, $data)
        {
            $timer = new EventMetric($event);
            if (! static::eventPermitted($event)) {
                return;
            }
            if (! is_array($data)) {
                Log::warning("'DispatchEvent' with event '$event' called with invalid data. Must be an array.");
                $data = array($data);
            }
            Event::fire($event, $data);
        }
    }

    class EventMetric
    {

        private $start;
        private $end;
        private $name;


        function __construct($name)
        {
            $this->name = $name;
            $this->start = microtime(true);
        }

        function __destruct()
        {
            $this->end = microtime(true);
            $duration = 1000 * ($this->end - $this->start);
            if (Config::get('events.log_runtime')) {
                Log::info("EventHandler for event '{$this->name}' took " . round($duration, 2) . " ms to process");
            }
        }

    }