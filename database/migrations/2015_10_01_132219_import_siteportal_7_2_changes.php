<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal72Changes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SiteGate only!
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            DB::unprepared("
                -- --------------------------------------------------------------
        --  Comparing the database structure (sitegate_template)
        --  against the template database (sitegate_template_updated)
        -- --------------------------------------------------------------
        -- --------------------------------------------------------------
        --  Checking for missing tables
        -- --------------------------------------------------------------
        DROP TABLE IF EXISTS css_snmp_alarm_map;
        DROP TABLE IF EXISTS css_snmp_incoming_trap;
        CREATE TABLE css_snmp_alarm_map (empty_field_to_delete varchar(1));
        CREATE TABLE css_snmp_incoming_trap (empty_field_to_delete varchar(1));
        -- --------------------------------------------------------------
        --   Checking for missing fields
        -- --------------------------------------------------------------
        ALTER TABLE css_networking_device_type ADD COLUMN heartbeat_threshold_enabled int(11) NOT NULL  DEFAULT 0   ;
        ALTER TABLE css_snmp_alarm_map ADD COLUMN id int(11) NOT NULL   auto_increment  ,ADD PRIMARY KEY (id);
        ALTER TABLE css_snmp_alarm_map ADD COLUMN alarm_id int(10) NULL     ;
        ALTER TABLE css_snmp_alarm_map ADD COLUMN snmp_trap_id int(11) NULL     ;
        ALTER TABLE css_snmp_alarm_map ADD COLUMN clear_bit bit(1) NULL     ;
        ALTER TABLE css_snmp_alarm_map ADD COLUMN inc_trap_payload text NULL     ;
        ALTER TABLE css_snmp_alarm_map ADD COLUMN out_trap_payload text NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN id int(11) NOT NULL   auto_increment  ,ADD PRIMARY KEY (id);
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN program_name varchar(60) NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN from_host varchar(60) NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN device_id int(10) NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN device_reported_time datetime NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN received_at datetime NULL     ;
        ALTER TABLE css_snmp_incoming_trap ADD COLUMN message text NULL     ;
        ALTER TABLE data_power_rtu ADD COLUMN rtu_timestamp timestamp NULL     ;
        ALTER TABLE data_power_rtu ADD COLUMN power_plant_timestamp timestamp NULL     ;
        ALTER TABLE data_power_rtu MODIFY COLUMN date_changed timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'   ;
        ALTER TABLE data_power_rtu ADD COLUMN date_created timestamp NOT NULL  DEFAULT CURRENT_TIMESTAMP   ;
        -- --------------------------------------------------------------
        --   Removing temp fields
        -- --------------------------------------------------------------
        ALTER TABLE  css_snmp_alarm_map DROP  empty_field_to_delete;
        ALTER TABLE  css_snmp_incoming_trap DROP  empty_field_to_delete;
        -- --------------------------------------------------------------
        --   Checking for missing indexes
        -- --------------------------------------------------------------
        ALTER TABLE css_networking_device_prop_opts ADD INDEX idx_css_networking_device_prop_opts_prop_def_id_value (prop_def_id,value) USING BTREE;
        -- --------------------------------------------------------------
        --   Checking for modified indexes
        -- --------------------------------------------------------------
        -- --------------------------------------------------------------
        --  Comparing table values in css_networking_device_class
        -- --------------------------------------------------------------
        INSERT INTO css_networking_device_class(id,description,is_license) VALUES (22,'Remote Unit Element',0);
        -- --------------------------------------------------------------
        --  Comparing table values in css_networking_device_type
        -- --------------------------------------------------------------
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 3  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 4  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 19  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 20  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 21  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 22  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 23  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 24  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 25  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 26  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 27  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 29  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 30  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 31  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 32  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 33  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 34  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 35  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 36  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 37  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 38  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 39  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 40  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 41  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 42  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 43  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 44  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 45  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 46  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 47  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 48  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 49  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 53  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 54  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 55  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 56  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 57  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 58  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 59  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 60  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 61  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 62  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 63  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 64  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 70  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 100  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 101  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 102  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 103  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 104  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 105  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 106  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 107  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 108  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 109  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 110  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 111  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 112  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 113  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 118  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 119  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 120  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 121  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 122  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 123  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 124  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 125  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 126  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 128  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 129  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 131  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 132  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 133  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 134  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 135  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 136  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 140  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 141  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 142  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 200  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 201  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 202  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 203  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 204  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 205  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 206  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 207  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 208  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 209  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 210  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 211  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 212  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 213  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 214  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 215  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 216  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 217  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 218  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 219  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 220  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 221  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 222  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 223  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 226  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 229  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 230  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 231  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 232  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 233  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 234  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 235  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 236  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 237  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 238  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 239  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 240  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 241  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 242  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 251  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 252  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 253  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 255  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 256  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 257  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 258  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 259  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 260  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 261  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 262  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 263  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 264  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 265  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 266  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 267  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 268  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 269  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 299  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 300  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 301  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 306  ; /* Changed from null*/
        UPDATE css_networking_device_type SET auto_build_enabled = 1 where   id = 309  ; /* Changed from 0*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 309  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 312  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 315  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 316  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 317  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 318  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 717  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 719  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 720  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 721  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 722  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 723  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 724  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 725  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 726  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 727  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 728  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 729  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 730  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 731  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 732  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 733  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 734  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 735  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 736  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 737  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 738  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 739  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 740  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 741  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 742  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 743  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 744  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 745  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 750  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 751  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 752  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 753  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 754  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 755  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 756  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 757  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 758  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 759  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 760  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 761  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 762  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 763  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 764  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 765  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 766  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 810  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 811  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 812  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 813  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 814  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 815  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 816  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 820  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 821  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 822  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 823  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 826  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 827  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 850  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 851  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 852  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 853  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 854  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 855  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 856  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 857  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 858  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 859  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 860  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 888  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 889  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 890  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 891  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 892  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 893  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 894  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 895  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 896  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 897  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 898  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 899  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 900  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 901  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 902  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1080  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1081  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1082  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1083  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1084  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1085  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 1 where   id = 1086  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1087  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1088  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1089  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1090  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1091  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1092  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1093  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1094  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 1 where   id = 1095  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1099  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1100  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1101  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1103  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1106  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1107  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1108  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1112  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1114  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1115  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1116  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1117  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1118  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1119  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1120  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1121  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1122  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1123  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1124  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1126  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1127  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1128  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1129  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1130  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1131  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1132  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1133  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1134  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1135  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1136  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1137  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1138  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1139  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1140  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1141  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1142  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1143  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1144  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1145  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1146  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1147  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1148  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1149  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1150  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1151  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1152  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1153  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1156  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1157  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1158  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1159  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1160  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1161  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1162  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1164  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1165  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1166  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1167  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1168  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1171  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1172  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1173  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1174  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1175  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1176  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1177  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1178  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1179  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1180  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1181  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1182  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1183  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1184  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1185  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1186  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1187  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1188  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1189  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1190  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1191  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1192  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1193  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1194  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1195  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1196  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1197  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1198  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1199  ; /* Changed from null*/
        UPDATE css_networking_device_type SET class_id = 22 where   id = 1200  ; /* Changed from 30*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1200  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1201  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1202  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1203  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1204  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1205  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1206  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1207  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1208  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1209  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1210  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1211  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1212  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1213  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1214  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1215  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1216  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1217  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1218  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1219  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1220  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1221  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1222  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1223  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1224  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1225  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1227  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1228  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1229  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1230  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1231  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1232  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1233  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1234  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1235  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1236  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1242  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1243  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1244  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1255  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1256  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1257  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1258  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1259  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1260  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1261  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1262  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1263  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1264  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1265  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1267  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1268  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1269  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1271  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1272  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1273  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1274  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1278  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1279  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1280  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1281  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1282  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 1 where   id = 1283  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1284  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1285  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1286  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1287  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1288  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1291  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1292  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1293  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1294  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1295  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1296  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1297  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1299  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1300  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1301  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1302  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1303  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1304  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1306  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1307  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1308  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1309  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1314  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1315  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1316  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1317  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1318  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1319  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1320  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1321  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1322  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1323  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1324  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1325  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1326  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1327  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1330  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1331  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1332  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1333  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1334  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1335  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1336  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1337  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1338  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1339  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1340  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1342  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1343  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1344  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1345  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1348  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1349  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1350  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1351  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1352  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1353  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1354  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1355  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1356  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1357  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1358  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1359  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1360  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1365  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1366  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1367  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1368  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1369  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1370  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1371  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1372  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1373  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1374  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1375  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1376  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1377  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1378  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1379  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1380  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1381  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1382  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1383  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1384  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1385  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1386  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1387  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1388  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1389  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1390  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1391  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1392  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1393  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1394  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1395  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1396  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1397  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1398  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1399  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1400  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1401  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1402  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1403  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1404  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1405  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1406  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1407  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1408  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1410  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1411  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1412  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1413  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1417  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1418  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1420  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1421  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1422  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1423  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1424  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1425  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1426  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1427  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1428  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1430  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1431  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1432  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1433  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1434  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1435  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1436  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1437  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1440  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1441  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1442  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1443  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1444  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1445  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1446  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1447  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1448  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1449  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1450  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1451  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1452  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1453  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1454  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1455  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1456  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1457  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1458  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1459  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1460  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1461  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1462  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1463  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1464  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1465  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1466  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1467  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1468  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1469  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1470  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1471  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1472  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1473  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1474  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1475  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1476  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1477  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1478  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1479  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1480  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1481  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1482  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1483  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1484  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1485  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1486  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1487  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1488  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1489  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1490  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1491  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1492  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1493  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1494  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1495  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1496  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1497  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1498  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1499  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1500  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1501  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1502  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1503  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1504  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1505  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1506  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1507  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1508  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1509  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1510  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1511  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1512  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1513  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1514  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1515  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1516  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1517  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1518  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1519  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1520  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1521  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1525  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1526  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1527  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1528  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1529  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1530  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1531  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1532  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1533  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1534  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1535  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1536  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1537  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1538  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1539  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1540  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1541  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1542  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1543  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1544  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1545  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1546  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1547  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1548  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1549  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1550  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1551  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1552  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1553  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1554  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1555  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1556  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1557  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1558  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1559  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1560  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1561  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1562  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1563  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1564  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1565  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1566  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1567  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1568  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1569  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1570  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1571  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1572  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1573  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1576  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1577  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1578  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1579  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1580  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1581  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1582  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1583  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1584  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1585  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1586  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1587  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1588  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1589  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1980  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1981  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1982  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1983  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1984  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1985  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1986  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1987  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1988  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1989  ; /* Changed from null*/
        UPDATE css_networking_device_type SET class_id = 22 where   id = 1990  ; /* Changed from 30*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1990  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1991  ; /* Changed from null*/
        UPDATE css_networking_device_type SET class_id = 22 where   id = 1992  ; /* Changed from 30*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1992  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1993  ; /* Changed from null*/
        UPDATE css_networking_device_type SET class_id = 22 where   id = 1994  ; /* Changed from 30*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1994  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1995  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1996  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1997  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1998  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 1999  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2000  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2001  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2002  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2003  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2004  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2005  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2006  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2007  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2008  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2009  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2010  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2011  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2012  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2013  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2014  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2015  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2017  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2018  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2019  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2020  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2021  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2022  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2023  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2024  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2025  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2026  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2027  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2028  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2029  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2030  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2031  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2032  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2033  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2034  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2035  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2036  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2037  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2041  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2042  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2043  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2044  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2045  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2046  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2047  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2048  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2049  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2050  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2051  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2052  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2053  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2054  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2055  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2056  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2057  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2058  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2059  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2060  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2061  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2062  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2063  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2064  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2120  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2121  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2122  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2123  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2124  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2125  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2126  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2127  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2128  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2129  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2130  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2131  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2132  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2133  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2134  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2135  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2136  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2137  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2138  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2139  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2140  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2141  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2142  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2143  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2144  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2145  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2146  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2147  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2148  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2149  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2150  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2151  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2152  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2153  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2154  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2155  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2156  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2157  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2158  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2160  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 2500  ; /* Changed from null*/
        UPDATE css_networking_device_type SET scan_file = 'siteportal_device_alarm_scanner_launcher.php' where   id = 5000  ; /* Changed from null*/
        UPDATE css_networking_device_type SET prop_scan_file = 'siteportal_device_prop_scanner_launcher.php' where   id = 5000  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5000  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5001  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5002  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5003  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5004  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5005  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5006  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5007  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5008  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5009  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5010  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5011  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5012  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5020  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5021  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5022  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5023  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5024  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5025  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5026  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5027  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5028  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5029  ; /* Changed from null*/
        UPDATE css_networking_device_type SET heartbeat_threshold_enabled = 0 where   id = 5050  ; /* Changed from null*/
        ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('css_snmp_alarm_map ');
        Schema::dropIfExists('css_snmp_incoming_trap');

        if (Schema::hasColumn('css_networking_device_type', 'heartbeat_threshold_enabled')) {
            Schema::table('css_networking_device_type', function ($table) {
                $table->dropColumn('heartbeat_threshold_enabled');
            });
        }
        if (Schema::hasColumns('data_power_rtu', ['rtu_timestamp', 'power_plant_timestamp', 'date_created'])) {
            Schema::table('css_networking_device_type', function ($table) {
                $table->dropColumn(['rtu_timestamp', 'power_plant_timestamp', 'date_created']);
            });
        }
        DB::table('css_networking_device_class')->where('id',22)->delete();
    }
}
