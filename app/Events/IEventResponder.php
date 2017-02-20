<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    interface IEventResponder
    {

        //return an array of C2Events your class does *not* want to listen for. implemented functions for these events will never be called
        function GetExcludedEvents();

        function NodeSelection($node);

        function DeviceModified($old, $new);

        function GroupModified($old, $new);

        function NodeModified($old, $new);

        function UserAdded($user);

    }