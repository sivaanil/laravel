<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class EventRegistrar
    {

        private $events;


        //Registering events

        public static function RegisterListener($eventName, $handler, $priority = EventPriority::LOW)
        {

            Event::listen($eventName, $handler, $priority);

        }

        public static function SubscribeToAddUser($handler, $priority = EventPriority::LOW)
        {
            EventRegistrar::RegisterListener(C2Event::ADD_USER, $handler, $priority);
        }

        public static function SubscribeToSelectNode($handler, $priority = EventPriority::LOW)
        {
            EventRegistrar::RegisterListener(C2Event::SELECT_NODE, $handler, $priority);
        }


        public static function RegisterResponder(IEventResponder $responder)
        {

            $responderClass = get_class($responder);

            $exclusions = $responder->GetExcludedEvents();
            if (! is_array($exclusions)) {
                $exclusions = array();
            }

            if (array_search(C2Event::ADD_USER, $exclusions) === false) {
                EventRegistrar::RegisterListener(C2Event::ADD_USER, $responderClass . "@UserAdded");
            }
            if (array_search(C2Event::SELECT_NODE, $exclusions) === false) {
                EventRegistrar::RegisterListener(C2Event::SELECT_NODE, $responderClass . "@NodeSelection");
            }
            if (array_search(C2Event::MODIFY_NODE, $exclusions) === false) {
                EventRegistrar::RegisterListener(C2Event::MODIFY_NODE, $responderClass . "@NodeModified");
            }
            if (array_search(C2Event::MODIFY_DEVICE, $exclusions) === false) {
                EventRegistrar::RegisterListener(C2Event::MODIFY_DEVICE, $responderClass . "@DeviceModified");
            }
            if (array_search(C2Event::MODIFY_GROUP, $exclusions) === false) {
                EventRegistrar::RegisterListener(C2Event::MODIFY_GROUP, $responderClass . "@GroupModified");
            }

        }

    }


