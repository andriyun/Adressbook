<?php

class MySql
{
    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpass;
    private $col_conn;
    private $status;
    private $message = array(
		0 => 'DB conntent status "read only"',
		1 => 'DB conntent status "read, write"',		
		);
		
    private static $dblink;
    private static $col_connections;
    public static $count;

    function __construct($connect_data = array())
    {
		$this->count = 1;

		if (isset($connect_data['dbhost'])
			&& isset($connect_data['dbname'])
			&& isset($connect_data['dbuser'])
			&& isset($connect_data['dbpass'])
			) {
			$this->dbhost = $connect_data['dbhost'];
			$this->dbname = $connect_data['dbname'];
			$this->dbuser = $connect_data['dbuser'];
			$this->dbpass = $connect_data['dbpass'];			
		} else {
			$this->dbhost = DBHOST;
			$this->dbname = DBNAME;
			$this->dbpass = DBPASS;
			$this->dbuser = DBUSER;
			}
		/*
		 * 1 - все разрешено
		 * 0 - только чтение
		 */
		if (isset($connect_data['status'])) {
			 $this->status = $connect_data['status'];
			} else $this->status = 0;		

        if (!isset($this->dblink)) {
            $this->dblink = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) or
                die("MySQL error: --> " . mysql_last_error($this->dblink));
        }

        mysql_select_db($this->dbname);
        mysql::$col_connections = mysql::$col_connections + 1;
    
        mysql_query("set names utf8", $this->dblink); # cp1251
    }

    public function query($sql)
    {
		$this->checkPermission($sql);
        $this->count++;
        $hRes = mysql_query($sql, $this->dblink);
		return mysql_affected_rows($this->dblink);
    }		
	
    public function select($sql)
    {
		$this->checkPermission($sql);	
        $this->count++;
        $sql = mysql_real_escape_string($sql);
        $sql = stripslashes($sql);
        $hRes = mysql_query($sql, $this->dblink);
        if (!is_resource($hRes)) {
            $err = mysql_error($this->dblink);
            throw new Exception($err);
        }
        $arReturn = array();
        while (($row = mysql_fetch_assoc($hRes))) {
            $arReturn[] = $row;
        }
        return $arReturn;
    }

    public function insert($table, $arFieldValues)
    {	
		$this->count++;
        $fields = array_keys($arFieldValues);
        $values = array_values($arFieldValues);

        $escVals = array();
        foreach ($values as $val) {
            if (!is_numeric($val)) {
                $val = "'" . mysql_real_escape_string($val) . "'";
            }
            $escVals[] = $val;
        }

        $sql = "INSERT INTO $table(";
        $sql .= join(', ', $fields);
        $sql .= ') VALUES(';
        $sql .= join(', ', $escVals);
        $sql .= ')';
		$this->checkPermission($sql);	
        if (mysql_query($sql, $this->dblink)) return mysql_insert_id($this->dblink);
			return false;
    }

    public function update($table, $arFieldValues, $arConditions)
    {
        $this->count++;	
        $arUpdates = array();
        foreach ($arFieldValues as $field => $val) {
            if (!is_numeric($val)) {
               if ($val == null) $val = 'NULL';
				else $val = "'" . mysql_real_escape_string($val) . "'";
            }

			$arUpdates[] = "$field = $val";
        }

        $arWhere = array();
        foreach ($arConditions as $field => $val) {
            if (!is_numeric($val)) {
                $val = "'" . mysql_real_escape_string($val) . "'";
            }

            $arWhere[] = "$field = $val";
        }

        $sql = "UPDATE $table SET ";
        $sql .= join(', ', $arUpdates);
        $sql .= ' WHERE ' . join(' AND ', $arWhere);
		$this->checkPermission($sql);	
        $hRes = mysql_query($sql, $this->dblink);

        return $hRes;
    }
    public function delete($table, $arConditions)
    {
        $this->count++;

        $arWhere = array();
        foreach ($arConditions as $field => $val) {
            if (!is_numeric($val)) {
                $val = "'" . mysql_real_escape_string($val) . "'";
            }

            $arWhere[] = "$field = $val";
        }

        $sql = "DELETE FROM $table WHERE (" . join(' AND ', $arWhere) . ")";

		$this->checkPermission($sql);
        $hRes = mysql_query($sql, $this->dblink);

        return mysql_affected_rows($this->dblink);
    }
    public function getColConnections()
    {
        return mysql::$col_connections;
    }
	public function escape($value) {

		return mysql_real_escape_string($value, $this->dblink);

	}
	public function checkPermission($sql) {
		if (!$this->status){
			if (!preg_match('|(SELECT)|is',$sql)) {
				echo '<pre>';print_r($sql);echo '</pre>';
				die($this->message[$this->status]);
				}
			}
	}
}

?>