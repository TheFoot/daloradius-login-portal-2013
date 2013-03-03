<?php

	/*
	 * File				: class.oad.database.php
	 * Author			: Barry Jones (barry@OnAllDevices.com)
	 * Purpose			: This class encapsulates all mysql database access functions
	 * Notes			:
	*/
	class c_oad_database {

		///////////////////////////////////////////////////
		// Properties
		protected $last_error = '';
		protected $in_trans = false;
		protected $db_host = false;
		protected $db_name = false;
		protected $db_user = false;
		protected $db_pass = false;
		protected $db_conn = null;
		protected $last_resultset = array();
		protected $last_sql = '';
		protected $last_auto_id = null;
		protected $last_rowcount = 0;
		protected $last_resource = null;
		protected $last_paging_total = null;
		protected $last_paging_filter_total = null;

		//////////////////////////////////////////////////
		// Constructor/Destructor
		function __construct(){

		}

		function __destruct(){

		} // __destruct()

		//////////////////////////////////////////////////
		// Private Methods


		//////////////////////////////////////////////////
		// Public Methods

		////////////////////////////////
		// Getters
		public function getDbConn(){
			return $this->db_conn;
		}

		public function getLastError(){
			return $this->last_error;
		}

		public function getInTrans(){
			return $this->in_trans;
		}

		public function getLastRunState(){
			return array(
				"conn"		=> $this->db_conn,
				"sql"		=> $this->last_sql,
				"resultset"	=> $this->last_resultset,
				"rowcount"	=> $this->last_rowcount,
				"resource"	=> $this->last_resource,
				"auto_id"	=> $this->last_auto_id,
				"error"		=> $this->last_error,
				"paging_total"			=> $this->last_paging_total,
				"paging_filter_total"	=> $this->last_paging_filter_total
			);
		}

		public function getLastPagingTotal(){
			return $this->last_paging_total;
		}

		public function getLastPagingFilterTotal(){
			return $this->last_paging_filter_total;
		}

		// Disconnect from the mysql instance
		public function disconnect(){
			unset($this->db_conn);
		} // disconnect()

		// Connect to the mysql instance
		public function connect($v_db_host, $v_db_user, $v_db_pass, $v_db_name){
			$this->last_error = '';

			// Set connection config
			$this->db_host = $v_db_host;
			$this->db_user = $v_db_user;
			$this->db_pass = $v_db_pass;
			$this->db_name = $v_db_name;

			// Connect to mysql instance
			$this->db_conn = @mysql_connect(
				$this->db_host,
				$this->db_user,
				$this->db_pass
			);

			// Check for connection error
			if (!$this->db_conn){
				$this->last_error = "Unable to connect to mysql instance: ".mysql_error();
			}

			// Connect to default database if required
			if (strlen($this->last_error) == 0){
				if (!mysql_select_db($this->db_name)){
					$this->last_error = "Unable to connect to database '".$this->db_name."': ".mysql_error();
				}
			}

			// Return success state
			return (strlen($this->last_error) == 0);

		} // $this->connect()

		// Read schema info from database and return as array resultset
		public function schema($v_tbl_name){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Build and execute sql
			$v_sql = "select * from information_schema.columns where table_schema = '".$this->db_name."'".
				" and table_name = '".$v_tbl_name."'";
			$a_schema = $this->select($v_sql);
			if ($a_schema === false){
				return false;
			} else if (count($a_schema) == 0){

				// @TODO - Check ds_name for better errors

				$this->last_error = "Error - No schema information found.";
				return false;
			}

			// Format return array
			$v_resultset = array();
			foreach ($a_schema as $v_idx => $a_rec){
				$v_resultset[$a_rec['COLUMN_NAME']] = array(
					"column_name"		=> strval($a_rec['COLUMN_NAME']),
					"position"			=> intval($a_rec['ORDINAL_POSITION']),
					"default"			=> strval($a_rec['COLUMN_DEFAULT']),
					"max_length"		=> intval($a_rec['CHARACTER_MAXIMUM_LENGTH']),
					"max_length_raw"	=> intval($a_rec['CHARACTER_OCTET_LENGTH']),
					"data_type"			=> strval($a_rec['DATA_TYPE']),
					"required"			=> (strval($a_rec['IS_NULLABLE']) == "YES" ? false : true),
					"auto_id"			=> (strpos($a_rec['EXTRA'], 'auto_increment') !== false),
					"permissions"		=> explode(',', strval($a_rec['PRIVILEGES']))
				);
			}
			$this->last_resultset = $v_resultset;
			return $this->last_resultset;

		}

		// Build an sql statement from config arrays
		// Filters array param looks like:
		/*
		 * $a_filters = array(
		* 		"field1"	=> array(
		* 			"combine"	=> 'or', // Can be 'and' or 'or'
		* 			"value"		=> '', 	 // Value to search for
		* 			"operand"	=> ''	 // =, !=, >, <, >=, <=, contains, startswith, endswith
		* 		)
		* );
		*/
		// Order by array param looks like:
		/*
		 * $a_orderby = array(
		* 		"field1"	=> "asc",
		* 		"field2"	=> "desc"
		* );
		*/
		public function buildSQL (
			$a_select_cols,
			$v_ds_name,
			$a_filters,
			$a_orderby = false,
			$v_start = false,
			$v_length = false
		){

			$this->last_error = '';

			// Build select columns
			if (count($a_select_cols) == 0){$a_select_cols[] = '*';}
			$v_sql = 'select '.implode(',', $a_select_cols).' ';

			// Build from statement
			$v_sql .= 'from '.$v_ds_name.' ';

			// Build filter statement
			if ($a_filters && count($a_filters) > 0){
				$a_fils = array();
				foreach ($a_filters as $v_fld_name => $a_filter){

					// Wrap value in quotes if necessary, and escape the value
					$a_filter['value'] = $this->escape($a_filter['value']);

					// Combine method
					if (count($a_fils) == 0){
						$v_combine = '';
					} else {
						$v_combine = ($a_filter['combine'] == 'and') ? 'and ' : 'or ';
					}

					// Build depends on operand
					switch ($a_filter['operand']){
						case '=':
						case '!=':
						case '>':
						case '<':
						case '>=':
						case '>=':
							$a_val = $this->wrapArrayQuotes(array($a_filter['value']));
							$a_filter['value'] = $a_val[0];
							$a_fils[] = $v_combine.$v_fld_name.' '.$a_filter['operand'].' '.$a_filter['value'];
							break;
						case 'contains':
							$a_fils[] = $v_combine.$v_fld_name." like '%".$a_filter['value']."%'";
							break;
						case 'startswith':
							$a_fils[] = $v_combine.$v_fld_name." like '".$a_filter['value']."%'";
							break;
						case 'endswith':
							$a_fils[] = $v_combine.$v_fld_name." like '%".$a_filter['value']."'";
							break;
						default:
							$a_val = $this->wrapArrayQuotes(array($a_filter['value']));
							$a_fils[] = $v_combine.$v_fld_name.' = '.$a_val[0];
					}
				}

				// Now combine filters together
				$v_sql .= 'where '.implode('', $a_fils).' ';
			} else {
				$v_sql .= 'where 1 = 1 '; // Always include where for paging feature in select()
			}

			// Build order by
			if ($a_orderby){
				$a_ob = array();
				foreach ($a_orderby as $v_orderby_fld => $v_orderby_dir){
					$v_obdir = ($v_orderby_dir == 'asc') ? 'asc' : 'desc';
					$a_ob[] = $this->escape($v_orderby_fld).' '.$v_obdir;
				}
				$v_sql .= 'order by '.implode(',', $a_ob).' ';
			}

			// Build limit
			if ($v_start || $v_length){
				if ($v_start === false){
					$v_sql .= 'limit '.$this->escape($v_length).' ';
				} else {
					$v_sql .= 'limit '.$this->escape($v_start).', '.$this->escape($v_length).' ';
				}
			}

			return $v_sql;

		} // $this->buildSQL()

		// Read records from the database, return as indexed array of rows
		/* CAUTION! Even if one row is returned, you still need the row index to access it, i.e.:
			$a_row[0]['customer_id']
		*/
		public function select($v_sql, $v_calc_paging = false){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			$v_sql_run = $v_sql;

			// Are we calculating paging stats?
			// NOTE: Slightly rudimentary, but we rely on a WHERE clause to parse out the full sql
			// Otherwise it defaults to running the same orig query twice !NOT GOOD!
			if ($v_calc_paging){

				// Strip out the from section only
				$v_pos1 = stripos($v_sql, ' from');
				$v_pos2 = stripos($v_sql, ' where');

				if ($v_pos1 === false || $v_pos2 === false){
					$this->last_error = 'Either FROM or WHERE is missing from sql statement.';
					return false;
				}
				$v_sql_from = substr($v_sql, $v_pos1, ($v_pos2 - $v_pos1));

				// Get the total recs count
				$a_res = $this->select('select count(1) as "cnt" '.$v_sql_from);
				$this->last_paging_total = intval($a_res[0]['cnt']);

				// Alter the source query to calc found rows
				$pos = strpos($v_sql, 'select '); $v_sql_run = $v_sql;
				if ($pos !== false) {
					$v_sql_run = substr_replace($v_sql, 'select SQL_CALC_FOUND_ROWS ', $pos, strlen('select '));
				}

			}

			// Execute query
			$this->last_sql = $v_sql_run;
			$v_res = @mysql_query($v_sql_run);
			if (!$v_res){
				$this->last_error = "Error executing SQL: ".mysql_error();
				return false;
			} else {
				$this->last_resource = $v_res;
			}

			// Convert to 2d array
			$a_ret = $this->results($v_res);
			$this->last_rowcount = count($a_ret);
			if ($v_calc_paging){
				// Now ask how many rows we found in total
				$a_res = $this->select('SELECT FOUND_ROWS() as "cnt"', false);
				$this->last_paging_filter_total = intval($a_res[0]['cnt']);
			}
			return $a_ret;
		}


		// Fetches a query and converts the entire dataset into a 2d array
		public function results($v_resource_id){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Loop through
			$this->last_resultset = array();
			while ($a_row = mysql_fetch_assoc($v_resource_id)){
				$this->last_resultset[] = $this->unescapeArrayRow($a_row);
			}

			// Set stats
			$this->last_rowcount = count($this->last_resultset);

			// Return resultset
			return $this->last_resultset;

		}

		// Check that a single field value exists
		public function existsSingleValue($v_tbl_name, $v_key, $v_value){
			$v_cnt = $this->count("select $v_key from $v_tbl_name where $v_key = ".$this->escape($v_value));
			return ($v_cnt > 0);
		} // $this->existsSingleValue()

		// Counts the rows that would be returned from a sql query
		public function count($v_sql){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Strip out the SELECT part of the query
			$a_tmp = explode(' from ', $v_sql);
			array_shift($a_tmp);
			if (count($a_tmp) < 1){
				$this->last_error = "Error - Invalid SQL statement - no FROM clause.";
				return false;
			} else {
				$v_sql_run = "select count(1) as cnt from ".implode(' from ', $a_tmp);
			}

			// Execute the query
			$a_ret = $this->select($v_sql_run);
			if ($a_ret !== false){
				$this->last_rowcount = intval($a_ret[0]['cnt']);
				return $this->last_rowcount;
			} else {
				return false;
			}

		}


		// Performs either an update or insert (checks db for record id first)
		// NOTE: fields / values ordinal positions in the param arrays must match
		// Returns rows affected
		// IMPORTANT! This function only works if the table has an auto_increment id column
		public function save($v_tbl_name, $v_auto_id_name, $a_fields, $a_values){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Make sure that auto_id field is found in the fields array
			if (!in_array($v_auto_id_name, $a_fields)){
				$a_fields[] = $v_auto_id_name;
			}

			// Look for the auto_id field
			$v_auto_id_idx = array_search($v_auto_id_name, $a_fields);

			// New record?
			$v_is_insert = false;
			if ($v_auto_id_idx === false){
				$v_is_insert = true;
			} else if (!isset($a_values[$v_auto_id_idx])){
				$v_is_insert = true;
			} else if (!$a_values[$v_auto_id_idx]){
				$v_is_insert = true;
			} else {
				$v_auto_id_value = $a_values[$v_auto_id_idx];
				$v_sql = " from ".$v_tbl_name." where ".$v_auto_id_name." = ".$v_auto_id_value;
				$v_is_insert = ($this->count($v_sql) == 0);
			}

			// Remove auto_id from fields and values arrays
			if (isset($a_fields[$v_auto_id_idx])){
				unset($a_values[$v_auto_id_idx]);
				$a_values_save = array_values($a_values);
				unset($a_fields[$v_auto_id_idx]);
				$a_fields_save = array_values($a_fields);
			}

			// Perform relevant save method
			if ($v_is_insert){

				// Perform insert
				$v_ret = $this->insert($v_tbl_name, $a_fields_save, $a_values_save);
				if($v_ret !== false){
					if ($this->last_auto_id != $v_ret){
						$this->last_auto_id = $v_ret;
					}
					return $this->last_auto_id;
				} else {
					return false;
				}

			} else {

				// Perform update
				$v_ret = $this->update($v_tbl_name, $v_auto_id_name." = ".$v_auto_id_value, $a_fields_save, $a_values_save);
				if($v_ret !== false){
					return $v_auto_id_value;
				} else {
					return false;
				}

			}

		}


		// Performs an update
		// Returns rows affected
		public function update($v_tbl_name, $v_filter, $a_fields, $a_values){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Safeguard
			if (strlen($v_filter) == 0){
				$this->last_error = "Error - No filter for update.";
				return false;
			}

			// Escape values
			$a_values = $this->escapeArrayRow($a_values);

			// Wrap strings and dates in quotes
			$a_values = $this->wrapArrayQuotes($a_values);

			// Ensure we update the modified_ts field
			// Make sure that auto_id field is found in the fields array
			/*if (!in_array('modified_ts', $a_fields)){
				$a_fields[] = 'modified_ts';
				$a_values[] = 'current_timestamp()';
			}*/

			// Build SQL
			$v_sql = "update ".$v_tbl_name.' set ';
			$a_sets = array();
			for ($i = 0; $i < count($a_fields); $i++){
				$v_val = ($a_values[$i] === null) ? 'null' : $a_values[$i];
				$a_sets[] = $a_fields[$i]." = ".$v_val;
			}
			$v_sql .= implode(',', $a_sets);
			$v_sql .= ' where '.$v_filter;

			// Execute SQL
			return $this->executeSQL($v_sql);

		}


		// Delete record(s)
		// Returns rows affected
		// ** v_filter value must be escaped before this call
		public function delete ($v_tbl_name, $v_filter){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Safeguard
			if (strlen($v_filter) == 0){
				$this->last_error = "Error - No filter for delete.";
				return false;
			}

			// Build SQL
			$v_sql = "delete from ".$v_tbl_name.
				" where ".$v_filter;

			// Execute SQL
			return $this->executeSQL($v_sql);

		}

		// Insert a record into a table
		// Returns new auto id
		public function insert($v_tbl_name, $a_fields, $a_values){

			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return false;
			}

			// Escape values
			$a_values = $this->escapeArrayRow($a_values);

			// Wrap strings and dates in quotes
			$a_values = $this->wrapArrayQuotes($a_values);

			// Prepare null values
			$nullify = function($a){
				return ($a === null ? 'null' : $a);
			};
			$a_values = array_map($nullify, $a_values);

			// Ensure we update the created_ts field
			// Make sure that auto_id field is found in the fields array
			/*if (!in_array('created_ts', $a_fields)){
				$a_fields[] = 'created_ts';
				$a_values[] = 'current_timestamp()';
			}*/

			// Build SQL
			$v_sql = "insert into ".$v_tbl_name.
				" (".implode(',', $a_fields).") values".
				" (".implode(',', $a_values).")";

			// Execute SQL
			$v_count = $this->executeSQL($v_sql);
			return $this->last_auto_id;

		}


		// Format a unix timestamp according to a sql datatype
		public function formatDate($v_timestamp, $v_fieldType = 'DATETIME') {
			$v_date = '';
			if (!$v_timestamp === false && $v_timestamp > 0) {
				switch ($v_fieldType) {
					case 'DATE' :
						$v_date = date('Y-m-d', $v_timestamp);
						break;
					case 'TIME' :
						$v_date = date('H:i:s', $v_timestamp);
						break;
					case 'YEAR' :
						$v_date = date('Y', $v_timestamp);
						break;
					default :
						$v_date = date('Y-m-d H:i:s', $v_timestamp);
						break;
				}
			}
			return $v_date;
		}


		// Unescapes a single record array (coming from db)
		public function unescapeArrayRow($a_row){
			$a_clean = array();
			foreach($a_row as $v_key => $v_val){
				$a_clean[$v_key] = stripslashes($v_val);
			}
			return $a_clean;
		}


		// Escapes a single record array (going into db)
		public function escapeArrayRow($a_row){
			$a_clean = array();
			foreach($a_row as $v_key => $v_val){
				$a_clean[$v_key] = $this->escape($v_val);
			}
			return $a_clean;
		}

		// Escapes a single value (going into db)
		public function escape($v_val){
			if (is_float($v_val)){
				return floatval($v_val);
			} else if (is_int($v_val)) {
				return intval($v_val);
			} elseif ($v_val === null){
				return null;
			} else {
				return mysql_real_escape_string($v_val);
			}
		} // $this->escape()

		// Wrap each string in array with quotes
		// Ready for inserting/updating
		public function wrapArrayQuotes($a_row){
			$a_clean = array();
			foreach($a_row as $v_key => $v_val){

				// Is string?
				if (is_string($v_val)){
					$a_clean[$v_key] = "'".$v_val."'";
				} else {
					$a_clean[$v_key] = $v_val;
				}

			}
			return $a_clean;
		}


		// Execute SQL (returns rows affected)
		public function executeSQL($v_sql){
			$this->last_error = '';
			if (!$this->db_conn){
				$this->last_error = "Error - No DB connection.";
				return 0;
			}
			$v_result = @mysql_query($v_sql);
			if (!$v_result){
				$this->last_error = "Error executing SQL: ".mysql_error();
				return 0;
			} else {
				$this->last_sql = $v_sql;
				$this->last_resource = $v_result;
				$this->last_rowcount = mysql_affected_rows($this->db_conn);
				$this->last_auto_id = mysql_insert_id($this->db_conn);
				return $this->last_rowcount;
			}
		} // executeSQL()

		// Return current mysql version info
		public function getVersion() {
			return mysql_get_server_info();
		}


		// Start a transaction
		public function beginTrans(){
			$this->executeSQL("begin", $this->last_error);
			if (strlen($this->last_error) == 0){$this->in_trans = true;}
			return (strlen($this->last_error) == 0);
		} // beginTrans()

		// Rollback a transaction
		public function rollbackTrans(){
			$this->executeSQL("rollback", $this->last_error);
			if (strlen($this->last_error) == 0){$this->in_trans = false;}
			return (strlen($this->last_error) == 0);
		} // beginTrans()

		// Commit a transaction
		public function commitTrans(){
			$this->executeSQL("commit", $this->last_error);
			if (strlen($this->last_error) == 0){$this->in_trans = false;}
			return (strlen($this->last_error) == 0);
		} // commitTrans()

		// Create database user
		public function createUser($v_userid, $v_password, $v_host = "%"){
			$this->last_error = '';
			$v_userstring = "'$v_userid'@'$v_host'";

			// Drop user if exists
			$this->executeSQL("drop user $v_userstring", $v_null);

			// Create user
			$v_sql = "create user $v_userstring identified by '$v_password'";
			$this->executeSQL($v_sql, $this->last_error);
			return (strlen($this->last_error) == 0);

		} // createUser()

		// Create a database and assign user/owner permissions
		public function createDatabase($v_db_name, $v_owner_userid, $v_owner_user_host = "%"){
			$this->last_error = '';
			$v_userstring = "'$v_owner_userid'@'$v_owner_user_host'";

			// Drop database if it exists
			$this->executeSQL("drop database if exists $v_db_name", $v_null);

			// Create database
			$this->executeSQL("create database $v_db_name", $this->last_error);

			// Grant permissions to user
			if (strlen($this->last_error) == 0){
				$this->executeSQL("grant all on $v_db_name.* to $v_userstring", $this->last_error);
			}

			return (strlen($this->last_error) == 0);

		} // createDatabase()

	} // c_oad_database

?>