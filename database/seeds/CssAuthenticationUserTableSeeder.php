<?php

    use Illuminate\Database\Seeder;

    class CssAuthenticationUserTableSeeder extends Seeder
    {

        public function run()
        {
            DB::table('css_authentication_user')->truncate();

            $users = [
                [
                    'id'                     => '1',
                    'role_id'                => '2',
                    'home_node_id'           => '321',
                    'first_name'             => 'System',
                    'last_name'              => 'Machine',
                    'email_address'          => 'james.fulton@csquaredsystems.com',
                    'username'               => 'system',
                    'password'               => '$2a$15$mTWkfKLHNmn3XRwxnQ7zNOXG1DLLPLL35HaA02StzG6hr0biWjWtm',
                    'active'                 => '1',
                    'cell_phone'             => '6037322290',
                    'cell_carrier_id'        => '3',
                    'upgrade_notices'        => '0',
                    'processing'             => '0',
                    'company_id'             => '1',
                    'date_updated'           => '3/3/2015 13:35',
                    'department_id'          => '0',
                    'time_zone_id'           => '96',
                    'role'                   => 'Administrator',
                    'failed_login'           => '0',
                    'force_pwd_change'       => '0',
                    'pwd_modified_date'      => '9/29/2014 12:44',
                    'pwd_never_expire'       => '82',
                    'email_count'            => '5',
                    'sms_count'              => '5',
                    'ticketing_group_id'     => '0',
                    'signature'              => '',
                    'show_coordinates_tip'   => '1',
                    'use_signature'          => '0',
                    'message_exempt'         => '0',
                    'reset_pwd'              => '',
                    'pwd_validate_count'     => '2',
                    'last_pwd_validate_time' => '9/29/2014 12:44',
                    'account_locked_until'   => '',
                    'idletime'               => '',
                    'max_idletime'           => '',
                    'allow_ignore_idletime'  => '',
                    'ignore_idletime'        => '',
                    'end_vacation'           => '',
                    'offHours'               => '1',
                    'alarmConsolidation'     => '',
                    'queue_reminder'         => '0',
                    'queue_days'             => '0',
                    'queue_hours'            => '0',
                    'filter_off_hour_email'  => '0',
                    'filter_off_hour_texts'  => '0',
                    'authentication_method'  => '',
                    'ldap_group'             => '',
                    'lock_to_custom_tree'    => '0',
                    'remember_token'         => ''
                ],
                [
                    'id'                     => '5',
                    'role_id'                => '1',
                    'home_node_id'           => '321',
                    'first_name'             => 'System',
                    'last_name'              => 'Administrator',
                    'email_address'          => 'software.managers@csquaredsystems.com',
                    'username'               => 'admin',
                    'password'               => '$2a$15$L1FStSbf4ZAGvj.PTeyVvOqCuttKxxFQB3kq9umBQlTUfxyNhhbGa',
                    'active'                 => '1',
                    'cell_phone'             => '6037322290',
                    'cell_carrier_id'        => '5',
                    'upgrade_notices'        => '0',
                    'processing'             => '0',
                    'company_id'             => '1',
                    'date_updated'           => '5/20/2015 16:09',
                    'department_id'          => '0',
                    'time_zone_id'           => '96',
                    'role'                   => 'System Administrator',
                    'failed_login'           => '27',
                    'force_pwd_change'       => '1',
                    'pwd_modified_date'      => '2/2/2015 14:42',
                    'pwd_never_expire'       => '82',
                    'email_count'            => '5',
                    'sms_count'              => '5',
                    'ticketing_group_id'     => '0',
                    'signature'              => '',
                    'show_coordinates_tip'   => '0',
                    'use_signature'          => '1',
                    'message_exempt'         => '0',
                    'reset_pwd'              => '',
                    'pwd_validate_count'     => '3',
                    'last_pwd_validate_time' => '9/29/2014 18:03',
                    'account_locked_until'   => '',
                    'idletime'               => '0',
                    'max_idletime'           => '0',
                    'allow_ignore_idletime'  => '0',
                    'ignore_idletime'        => '0',
                    'end_vacation'           => '0000-00-00',
                    'offHours'               => '1',
                    'alarmConsolidation'     => '',
                    'queue_reminder'         => '0',
                    'queue_days'             => '0',
                    'queue_hours'            => '0',
                    'filter_off_hour_email'  => '0',
                    'filter_off_hour_texts'  => '0',
                    'authentication_method'  => '',
                    'ldap_group'             => '',
                    'lock_to_custom_tree'    => '0',
                    'remember_token'         => '',
                ],
            ];
            DB::table('css_authentication_user')->insert($users);
        }

    }
