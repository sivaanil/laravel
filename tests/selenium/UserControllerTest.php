<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class UserControllerTest extends TestCase
    {


        //utility funcs
        //setUp() is a bootstrapping function that it always called before any tests.
        public function setUp()
        {
            //always remember to call parent's setUp first
            parent::setUp();
            //if there is no UNIT_TEST_USER, create one.
            $user = User::whereUsername('UNIT_TEST_USER')->first();
            if (! $user) {
                $user = new User();
                $user->username = 'UNIT_TEST_USER';
                $user->password = Hash::make('test');
                $user->save();
            }
        }

        //tearDown() is always cladded post-test
        public function tearDown()
        {
            //clean up our DB entries if they exist
            $user = User::whereUsername('UNIT_TEST_USER')->first();
            if ($user) {
                $user->delete();
            }
        }

        //Tests
        //ensure that a http GET to root redirects to to the login page
        public function testShowLogin()
        {

            $result = $this->call('GET', '/');
            $this->assertRedirectedToRoute('login');

        }

        //run through the log process by posting creds and ensuring we're redirected to the home page
        public function testDoLogin()
        {

            $this->client->restart();//ensure the client is logged out
            $this->call('POST', '/login', array('username' => 'UNIT_TEST_USER', 'password' => 'test'));
            $this->assertRedirectedToRoute('home');

        }

        public function testDoLogout()
        {
            //force the test user to be authenticated, then post to the logout route. make sure we're redirected to the root with a flash_message (this is the logout message)
            $user = User::whereUsername('UNIT_TEST_USER')->first();
            if (! $user) {
                throw new Exception("Unit test user does not exist");
            }

            $this->be($user);
            $this->call('GET', 'logout', array('id' => $user->id));
            $this->assertRedirectedToRoute('root');
            $this->assertSessionHas('flash_notice');

        }

    }