<?php
/*
 * MySQL DataBase Class, Modified From PHPLib by hightman.
 * $Id: db_mysql.php,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $
 */ 

class db_mysql {
    // public: connection parameters
    var $Host     = "";
    var $User     = "";
    var $Password = "";
    var $Database = "";
    
    // public: configuration parameters
    var $Use_Pconn     = 1;
    var $Auto_Free     = 1;     // Set to 1 for automatic mysql_free_result()
    var $Debug         = 0;     // Set to 1 for debugging messages.
    var $Halt_On_Error = "yes"; // "yes" (halt with message), 
				// "no" (ignore errors quietly),
				// "report" (ignore errror, but spit a warning)
    
    // public: result array and current row number
    var $Record   = array();
    var $Row;
    
    // public: current error number and error text
    var $Errno    = 0;
    var $Error    = "";
    
    // private: link and query handles
    var $Link_ID  = 0;
    var $Query_ID = 0;
    
    // public: constructor
    function db_mysql($params = "") {
	settype($params, "array");
	foreach($params as $key => $value) {
	    $this->$key = $value;
	}
    }
    
    // public: some trivial reporting
    function link_id() {
	return $this->Link_ID;
    }
    
    function query_id() {
	return $this->Query_ID;
    }
    
    // public: connection management
    function &connect($params = "") {
	// Use this to  db_mysql::connect(...), and return a mysql.
	if (!isset($this->Link_ID)){
	    $obj = new db_mysql($params);
	    $obj->connect();
	    return $obj;
	}
	
	// Handle defaults
	if (is_array($params)) {
	    foreach($params as $key => $value) {
		$this->$key = $value;
	    }
	}	
	
	// establish connection, select database 
	if ( 0 == $this->Link_ID ) {
	    if ($this->Use_Pconn)
		$this->Link_ID = mysql_pconnect($this->Host, $this->User, $this->Password);
	    else
		$this->Link_ID = mysql_connect($this->Host, $this->User, $this->Password);
	    if (!$this->Link_ID) {
		$this->halt("pconnect(" . $this->Host . ", " . $this->User . ", \$Password) failed.");
		return 0;
	    }
	    
	    if (!@mysql_select_db($this->Database,$this->Link_ID)) {
		$this->halt("cannot use database " . $this->Database);
		return 0;
	    }
	}
	return $this->Link_ID;
    }

    // public: change the database
    function select_db($Database = "") {
	if ("" == $Database || $Database == $this->Database)
	    return 0;
	elseif (!@mysql_select_db($Database,$this->Link_ID)) {
	    $this->halt("cannot use database " . $Database);
	    return 0;
	}
    }

    // public: close the connection
    function close() {
	$this->free();
	if (!$this->Use_Pconn && $this->Link_ID) {
//	    $this->close();
	    @mysql_close($this->Link_ID);
	}
    }
    
    // public: discard the query result
    function free() {
	if ($this->Query_ID) {
	    @mysql_free_result($this->Query_ID);
	    $this->Query_ID = 0;
	}
    }

    // public: perform a query
    function query($Query_String = "") {
	if ($Query_String == "") {
	    return 0; // Empty Query String ?
	}
	
	if (!$this->connect()) {
	    return 0; // we already complained in connect() about that.
	}
	
	// New query, discard previous result.
	if ($this->Query_ID) {
	    $this->free();
	}
	
	if ($this->Debug)
	    printf("Debug: query = %s<br>\n", $Query_String);
	
	$this->Query_ID = @mysql_query($Query_String,$this->Link_ID);
	$this->Row   = 0;
	if (!$this->Query_ID) {
	    $this->halt("Invalid SQL: " . $Query_String);
	}
	
	// Will return nada if it fails. That's fine.
	return $this->Query_ID;
    }

    // public: query first line and fetch it to an array.
    function &query_first($Query_String) {
	if ($Query_String == "") {
	    return 0; // Empty Query String ?
	}
	
	if (!$this->connect()) {
	    return 0; // we already complained in connect() about that.
	}
	
	// New query, discard previous result.
	if ($this->Query_ID) {
	    $this->free();
	}
	
	if ($this->Debug)
	    printf("Debug: query = %s<br>\n", $Query_String);
	
	$this->Query_ID = @mysql_query($Query_String,$this->Link_ID);
	$this->Row   = 0;

	if (!$this->Query_ID) {
	    $this->halt("Invalid SQL: " . $Query_String);
	}

	$this->Record = @mysql_fetch_array($this->Query_ID);

	if (is_array($this->Record)) {
	    $this->Row ++;
	    return $this->Record;
	}
	else
	    return 0;
    }
    
    // public: walk result set
    function next_record() {
	if (!$this->Query_ID) {
	    $this->halt("next_record called with no query pending.");
	    return 0;
	}
	
	$this->Record = @mysql_fetch_array($this->Query_ID);
	$this->Row ++;
	
	$stat = is_array($this->Record);
	if (!$stat && $this->Auto_Free) {
	    $this->free();
	}
	return $stat;
    }
    
    // public: fetch_array. and return the array.
    function &fetch_array($Query_ID = 0) {
	if (0 == $Query_ID)
	    $Query_ID = $this->Query_ID;
	if (!$Query_ID)
	    return 0;
	$this->Record = @mysql_fetch_array($Query_ID);
	if (is_array($this->Record)) {
	    $this->Row ++;
	    return $this->Record;
	}
	else
	    return 0;
    }

    // public: position in result set
    function seek($pos = 0) {
	$status = @mysql_data_seek($this->Query_ID, $pos);
	if ($status)
	    $this->Row = $pos;
	else {
	    $this->halt("seek(" . $pos . ") failed: result has " . $this->num_rows() . " rows.");
	    @mysql_data_seek($this->Query_ID, $this->num_rows());
	    $this->Row = $this->num_rows();
	    return 0;
	}
	
	return 1;
    }
    
    // public: table locking
    function lock($table, $mode = "write") {
	$query = "LOCK TABLES ";
	if (is_array($table)) {
	    while (list($key,$value) = each($table)) {
		if (!is_int($key)) {
		    // texts key are "read", "read local", "write", "low priority write"
		    $query .= "$value $key, ";
		}
		else {
		    $query .= "$value $mode, ";
		}
	    }
	    $query = substr($query, 0, -2);
	}
	else {
	    $query .= "$table $mode";
	}

	$res = $this->query($query);
	if (!$res) {
	    $this->halt("lock() failed.");
	    return 0;
	}
	return $res;
    }
    
    function unlock() {
	$res = $this->query("UNLOCK TABLES");
	if (!$res) {
	    $this->halt("unlock() failed.");
	}
	return $res;
    }

    // public: evaluate the result (size, width)
    function affected_rows() {
	return @mysql_affected_rows($this->Link_ID);
    }
    
    function num_rows() {
	return @mysql_num_rows($this->Query_ID);
    }
    
    function num_fields() {
	return @mysql_num_fields($this->Query_ID);
    }
    
    // public: shorthand notation
    function nf() {
	return $this->num_rows();
    }
    
    function np() {
	print $this->num_rows();
    }
    
    function f($Name) {
	if (isset($this->Record[$Name])) {
	    return $this->Record[$Name];
	}
    }
    
    function p($Name) {
	if (isset($this->Record[$Name])) {
	    print $this->Record[$Name];
	}
    }
    
    // private: error handling
    function halt($msg) {
	$this->Error = @mysql_error($this->Link_ID);
	$this->Errno = @mysql_errno($this->Link_ID);
	if ($this->Halt_On_Error == "no")
	    return;
	$this->haltmsg($msg);
	
	if ($this->Halt_On_Error != "report")
	    die("Session halted Because Of Mysql Connection.");
    }
    
    function haltmsg($msg) {
	printf("</form></td></tr></table><b>Database error:</b> %s<br>\n", $msg);
	printf("<b>MySQL Error</b>: %s (%s)<br>\n",
	    $this->Errno, $this->Error );
    }
}
?>
