<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class ChangeNodeTest extends TestCase
    {


        public function setUp()
        {
            parent::setUp();
            $user = User::whereUsername('testuser')->first();
            $this->be($user);

        }

        public function testIndex()
        {

            $result = $this->call('GET', '/NodeChange/321', array('id' => '321'));
            $this->assertResponseOk($result);

        }


    }