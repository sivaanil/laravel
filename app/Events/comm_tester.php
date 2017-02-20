<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class CommTester
    {


        public function NodeSelect($node)
        {
            Log::info("'SelectNode' event has reached the test listener: NID: {$node->id}");
        }

    }


    class TestResponder implements IEventResponder
    {

        //listen to all events
        public function GetExcludedEvents()
        {
            return array();
        }

        public function DeviceModified($old, $new)
        {
            Log::info("TestResponder's 'DeviceModified' event handle is being run!");
        }

        public function GroupModified($old, $new)
        {

            Log::info("TestResponder's 'GroupModified' event handle is being run!");
        }

        public function NodeModified($old, $new)
        {

            Log::info("TestResponder's 'NodeModified' event handle is being run!");
        }

        public function NodeSelection($node)
        {
            Log::info("TestResponder's 'NodeSelection' event handle is being run!");
        }

        public function UserAdded($user)
        {

            Log::info("TestResponder's 'UserAdded' event handle is being run!");
        }


    }