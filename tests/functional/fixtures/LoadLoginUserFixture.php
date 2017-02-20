<?php
use Illuminate\Database\Seeder;
use \DB;

class LoadLoginUserFixture
{
    public function run($db)
    {
    	try {
    		
	    	$id = $db->table('css_authentication_user')->insertGetId(array(
	    		'id' => 5,
	    		'role_id' => 1,
	    		'home_node_id' => 5000,
	    		'first_name' => 'System',
	    		'last_name'=> 'Administrator',
	    		'email_address' => 'software.managers@csquaredsystems.com',
	    		'username' => 'G8Keeper',
	    		'password' => '$2y$10$y53zYhvYqTOn9Kng.AIokeXktx6ixs5FQjKrBo3EXMLqXPjx3A03m',
	    		'active' => 1,
	    		'cell_phone' => '6037322250',
	    		'cell_carrier_id' => 5,
	    		'upgrade_notices' => 0,
	    		'processing' => 0,
	    		'company_id' => 1,
	    		'date_updated' => '2016-01-01 00:00:00',
	    		'department_id' => 0,
	    		'time_zone_id' => 96,
	    		'role' => 'Administrator',
	    		'failed_login' => 1,
	    		'force_pwd_change' => 0,
	    		'pwd_modified_date' => '2016-01-01 00:00:00',
	    		'pwd_never_expire' => 90,
	    		'email_count' => 5,
	    		'sms_count' => 5,
	    		'ticketing_group_id' => 0,
	    		'signature' => 0,
	    		'show_coordinates_tip' => 0,
	    		'use_signature' => 1,
	    		'message_exempt' => 0,
	    		'reset_pwd' => null,
	    		'pwd_validate_count' => 3,
	    		'last_pwd_validate_time' => '2016-01-01 00:00:00',
	    		'account_locked_until' => null,
	    		'idletime' => 0,
	    		'max_idletime' => 0,
	    		'allow_ignore_idletime' => 0,
	    		'ignore_idletime' => 0,
	    		'end_vacation' => '0000-00-00',
	    		'offHours' => 1,
	    		'alarmConsolidation' => null,
	    		'queue_reminder' => 0,
	    		'queue_days' => 0,
	    		'queue_hours' => 0,
	    		'filter_off_hour_email' => 0,
	    		'filter_off_hour_texts' => 0,
	    		'authentication_method' => null,
	    		'ldap_group' => null,
	    		'lock_to_custom_tree' => 0,
	    		'remember_token' => null
	    	));
	    	
    	} catch (\Exception $e) {
    		echo $e->getMessage() . PHP_EOL;
    		exit;
    	}
    	
    }
    
}