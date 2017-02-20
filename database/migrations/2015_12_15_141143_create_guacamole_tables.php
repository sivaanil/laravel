<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Create tables for the Guacamole MySQL authentication plugin
 */

class CreateGuacamoleTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// SiteGate only!
		if (env('C2_SERVER_TYPE') == 'sitegate') {
			$sql = <<<'SQL'
--
-- Copyright (C) 2013 Glyptodon LLC
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy
-- of this software and associated documentation files (the "Software"), to deal
-- in the Software without restriction, including without limitation the rights
-- to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
-- copies of the Software, and to permit persons to whom the Software is
-- furnished to do so, subject to the following conditions:
--
-- The above copyright notice and this permission notice shall be included in
-- all copies or substantial portions of the Software.
--
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
-- IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
-- FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
-- AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
-- LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
-- OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
-- THE SOFTWARE.
--

--
-- Table of connection groups. Each connection group has a name.
--
DROP TABLE IF EXISTS guacamole_connection_group;
CREATE TABLE guacamole_connection_group (

  connection_group_id   int(11)      NOT NULL AUTO_INCREMENT,
  parent_id             int(11),
  connection_group_name varchar(128) NOT NULL,
  type                  enum('ORGANIZATIONAL',
                               'BALANCING') NOT NULL DEFAULT 'ORGANIZATIONAL',

  -- Concurrency limits
  max_connections          int(11),
  max_connections_per_user int(11),

  PRIMARY KEY (connection_group_id),
  UNIQUE KEY connection_group_name_parent (connection_group_name, parent_id),

  CONSTRAINT guacamole_connection_group_ibfk_1
    FOREIGN KEY (parent_id)
    REFERENCES guacamole_connection_group (connection_group_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of connections. Each connection has a name, protocol, and
-- associated set of parameters.
-- A connection may belong to a connection group.
--

DROP TABLE IF EXISTS guacamole_connection;
CREATE TABLE guacamole_connection (

  connection_id       int(11)      NOT NULL AUTO_INCREMENT,
  connection_name     varchar(128) NOT NULL,
  parent_id           int(11),
  protocol            varchar(32)  NOT NULL,

  -- Concurrency limits
  max_connections          int(11),
  max_connections_per_user int(11),

  PRIMARY KEY (connection_id),
  UNIQUE KEY connection_name_parent (connection_name, parent_id),

  CONSTRAINT guacamole_connection_ibfk_1
    FOREIGN KEY (parent_id)
    REFERENCES guacamole_connection_group (connection_group_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of users. Each user has a unique username and a hashed password
-- with corresponding salt. Although the authentication system will always set
-- salted passwords, other systems may set unsalted passwords by simply not
-- providing the salt.
--

DROP TABLE IF EXISTS guacamole_user;
CREATE TABLE guacamole_user (

  user_id       int(11)      NOT NULL AUTO_INCREMENT,

  -- Username and optionally-salted password
  username      varchar(128) NOT NULL,
  password_hash binary(32)   NOT NULL,
  password_salt binary(32),

  -- Account disabled/expired status
  disabled      boolean      NOT NULL DEFAULT 0,
  expired       boolean      NOT NULL DEFAULT 0,

  -- Time-based access restriction
  access_window_start    TIME,
  access_window_end      TIME,

  -- Date-based access restriction
  valid_from  DATE,
  valid_until DATE,

  -- Timezone used for all date/time comparisons and interpretation
  timezone VARCHAR(64),

  PRIMARY KEY (user_id),
  UNIQUE KEY username (username)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of connection parameters. Each parameter is simply a name/value pair
-- associated with a connection.
--

DROP TABLE IF EXISTS guacamole_connection_parameter;
CREATE TABLE guacamole_connection_parameter (

  connection_id   int(11)       NOT NULL,
  parameter_name  varchar(128)  NOT NULL,
  parameter_value varchar(4096) NOT NULL,

  PRIMARY KEY (connection_id,parameter_name),

  CONSTRAINT guacamole_connection_parameter_ibfk_1
    FOREIGN KEY (connection_id)
    REFERENCES guacamole_connection (connection_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of connection permissions. Each connection permission grants a user
-- specific access to a connection.
--

DROP TABLE IF EXISTS guacamole_connection_permission;
CREATE TABLE guacamole_connection_permission (

  user_id       int(11) NOT NULL,
  connection_id int(11) NOT NULL,
  permission    enum('READ',
                       'UPDATE',
                       'DELETE',
                       'ADMINISTER') NOT NULL,

  PRIMARY KEY (user_id,connection_id,permission),

  CONSTRAINT guacamole_connection_permission_ibfk_1
    FOREIGN KEY (connection_id)
    REFERENCES guacamole_connection (connection_id) ON DELETE CASCADE,

  CONSTRAINT guacamole_connection_permission_ibfk_2
    FOREIGN KEY (user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of connection group permissions. Each group permission grants a user
-- specific access to a connection group.
--

DROP TABLE IF EXISTS guacamole_connection_group_permission;
CREATE TABLE guacamole_connection_group_permission (

  user_id             int(11) NOT NULL,
  connection_group_id int(11) NOT NULL,
  permission          enum('READ',
                             'UPDATE',
                             'DELETE',
                             'ADMINISTER') NOT NULL,

  PRIMARY KEY (user_id,connection_group_id,permission),

  CONSTRAINT guacamole_connection_group_permission_ibfk_1
    FOREIGN KEY (connection_group_id)
    REFERENCES guacamole_connection_group (connection_group_id) ON DELETE CASCADE,

  CONSTRAINT guacamole_connection_group_permission_ibfk_2
    FOREIGN KEY (user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of system permissions. Each system permission grants a user a
-- system-level privilege of some kind.
--

DROP TABLE IF EXISTS guacamole_system_permission;
CREATE TABLE guacamole_system_permission (

  user_id    int(11) NOT NULL,
  permission enum('CREATE_CONNECTION',
		    'CREATE_CONNECTION_GROUP',
                    'CREATE_USER',
                    'ADMINISTER') NOT NULL,

  PRIMARY KEY (user_id,permission),

  CONSTRAINT guacamole_system_permission_ibfk_1
    FOREIGN KEY (user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of user permissions. Each user permission grants a user access to
-- another user (the "affected" user) for a specific type of operation.
--

DROP TABLE IF EXISTS guacamole_user_permission;
CREATE TABLE guacamole_user_permission (

  user_id          int(11) NOT NULL,
  affected_user_id int(11) NOT NULL,
  permission       enum('READ',
                          'UPDATE',
                          'DELETE',
                          'ADMINISTER') NOT NULL,

  PRIMARY KEY (user_id,affected_user_id,permission),

  CONSTRAINT guacamole_user_permission_ibfk_1
    FOREIGN KEY (affected_user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE,

  CONSTRAINT guacamole_user_permission_ibfk_2
    FOREIGN KEY (user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table of connection history records. Each record defines a specific user's
-- session, including the connection used, the start time, and the end time
-- (if any).
--

DROP TABLE IF EXISTS guacamole_connection_history;
CREATE TABLE guacamole_connection_history (

  history_id    int(11)  NOT NULL AUTO_INCREMENT,
  user_id       int(11)  NOT NULL,
  connection_id int(11)  NOT NULL,
  start_date    datetime NOT NULL,
  end_date      datetime DEFAULT NULL,

  PRIMARY KEY (history_id),
  KEY user_id (user_id),
  KEY connection_id (connection_id),

  CONSTRAINT guacamole_connection_history_ibfk_1
    FOREIGN KEY (user_id)
    REFERENCES guacamole_user (user_id) ON DELETE CASCADE,

  CONSTRAINT guacamole_connection_history_ibfk_2
    FOREIGN KEY (connection_id)
    REFERENCES guacamole_connection (connection_id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

		DB::unprepared($sql);

		$sql = <<<'SQL'
--
-- Copyright (C) 2015 Glyptodon LLC
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy
-- of this software and associated documentation files (the "Software"), to deal
-- in the Software without restriction, including without limitation the rights
-- to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
-- copies of the Software, and to permit persons to whom the Software is
-- furnished to do so, subject to the following conditions:
--
-- The above copyright notice and this permission notice shall be included in
-- all copies or substantial portions of the Software.
--
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
-- IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
-- FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
-- AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
-- LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
-- OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
-- THE SOFTWARE.
--
-- Create default user "guacadmin" with password 6$Ie9K+B?[y+
INSERT INTO guacamole_user (username, password_hash, password_salt)
VALUES ('guacadmin',
    x'C05E7A43B7D5DABD257927ACD5D9B042C64BEB50E0183A2D85634BDD1E03E7DF',  -- 'guacadmin'
    x'E630C1B860E512FFA2D3E1FCCA7EDB25C7D01159171FF01CCA31709967BD219E');

-- Grant this user all system permissions
INSERT INTO guacamole_system_permission
SELECT user_id, permission
FROM (
          SELECT 'guacadmin'  AS username, 'CREATE_CONNECTION'       AS permission
    UNION SELECT 'guacadmin'  AS username, 'CREATE_CONNECTION_GROUP' AS permission
    UNION SELECT 'guacadmin'  AS username, 'CREATE_USER'             AS permission
    UNION SELECT 'guacadmin'  AS username, 'ADMINISTER'              AS permission
) permissions
JOIN guacamole_user ON permissions.username = guacamole_user.username;

-- Grant admin permission to read/update/administer self
INSERT INTO guacamole_user_permission
SELECT guacamole_user.user_id, affected.user_id, permission
FROM (
          SELECT 'guacadmin' AS username, 'guacadmin' AS affected_username, 'READ'       AS permission
    UNION SELECT 'guacadmin' AS username, 'guacadmin' AS affected_username, 'UPDATE'     AS permission
    UNION SELECT 'guacadmin' AS username, 'guacadmin' AS affected_username, 'ADMINISTER' AS permission
) permissions
JOIN guacamole_user          ON permissions.username = guacamole_user.username
JOIN guacamole_user affected ON permissions.affected_username = affected.username;
SQL;
		DB::unprepared($sql);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// SiteGate only!
		if (env('C2_SERVER_TYPE') == 'sitegate') {
			Schema::drop('guacamole_connection_history');
			Schema::drop('guacamole_user_permission');
			Schema::drop('guacamole_system_permission');
			Schema::drop('guacamole_connection_group_permission');
			Schema::drop('guacamole_connection_permission');
			Schema::drop('guacamole_connection_parameter');
			Schema::drop('guacamole_user');
			Schema::drop('guacamole_connection');
			Schema::drop('guacamole_connection_group');
		}
	}
}
