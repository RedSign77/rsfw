<?php

/**
 * Class RsDatabase
 */
class Database extends Singleton
{

    private $host = "localhost";
    private $database = "rsfw";
    private $user = "root";
    private $password = "";
    private $connection;
    private $query = "";

    protected function __construct()
    {
        $this->connect();
    }

    /**
     * Connect to database
     */
    public function connect()
    {
        try {
            @$this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
            if ($this->connection->connect_error)
                throw new Exception("File: " . basename(__FILE__) . ", line: " . __LINE__ . " >> " . $this->connection->connect_error, $this->connection->connect_errno);
            if (!$this->connection->set_charset("utf8")) {
                throw new Exception("File: " . basename(__FILE__) . ", line: " . __LINE__ . " >> " . $this->connection->error, $this->connection->connect_errno);
            }
        } catch (Exception $e) {
            die("Sorry, database error. Try again later.");
        }
    }

    /**
     * Close mysql connection
     *
     * @return mixed
     */
    public function close()
    {
        return $this->connection->close();
    }

    /**
     * Get mysqli info
     *
     * @return string
     */
    public function info()
    {
        return $this->connection->stat();
    }

    /**
     * Get current or last query string
     *
     * @return string
     */
    public function getLastQuery()
    {
        return $this->query;
    }

    /**
     * Run mysql select query
     *
     * @param $query
     * @return array
     * @throws Exception
     */
    public function select($query)
    {
        $ret = array();
        if ($this->valid($this->query = $query)) {
            $result = $this->connection->query($this->query, MYSQLI_USE_RESULT);
            if ($result) {
                while ($tmp = $result->fetch_assoc()) {
                    $ret[] = $tmp;
                }
            } else {
                throw new Exception("IDatabase (" . __METHOD__ . ", " . __LINE__ . ")<br>Error: <i>" . $this->connection->error . "</i>");
            }
            mysqli_free_result($result);
        } else {
            throw new Exception("IDatabase (" . __METHOD__ . ", " . __LINE__ . ")<br>Error: <i>" . $this->connection->error . "</i>");
        }
        return $ret;
    }

    /**
     * Check mysql select string
     *
     * @param $query
     * @return string
     */
    private function valid($query)
    {
        return $this->connection->real_escape_string($query);
    }

    /**
     * Execute a query
     *
     * @param $query
     * @param bool $validate
     * @param int $use
     * @return bool|mysqli_result
     */
    public function executeQuery($query, $validate = false, $use = MYSQLI_USE_RESULT)
    {
        if ($validate)
            $query = $this->valid($query);
        return $this->connection->query($query, $use);
    }

    /**
     * Run a delete query
     *
     * @param $query
     * @return int
     */
    public function delete($query)
    {
        $ret = 0;
        if ($this->executeQuery($query))
            $ret = $this->connection->affected_rows;
        return $ret;
    }

    /**
     * Run an insert query
     *
     * @param $query
     * @return int|mixed
     */
    public function insert($query)
    {
        $ret = 0;
        if ($this->executeQuery($query, false))
            $ret = $this->connection->insert_id;
        return $ret;
    }

    /**
     * Run an update query
     *
     * @param $query
     * @return int
     */
    public function update($query)
    {
        return $this->delete($query);
    }

    /**
     * Get a row number from a table with a condition
     *
     * @param $table
     * @param string $where
     * @return int|mixed
     * @throws Exception
     */
    public function getRowNumber($table, $where = "")
    {
        $this->query = "SELECT count(*) as counter FROM " . $table . ($where != "" ? " WHERE " . $where : null);
        $arrRet = $this->select($this->query);
        $ret = current($arrRet);
        if (isset($ret['counter'])) {
            $ret = intval($ret['counter']);
        } else {
            $ret = 0;
        }
        return $ret;
    }

    /**
     * Get a single field value from a query
     *
     * @param $table
     * @param $field
     * @param string $where
     * @return mixed
     * @throws Exception
     */
    public function getSingleData($table, $field, $where = "")
    {
        $this->query = "SELECT " . $field . " FROM " . $table . ($where != "" ? " WHERE " . $where : null);
        $arrRet = $this->select($this->query);
        $arrRet = current($arrRet);
        if ($arrRet[$field]) {
            return ($arrRet[$field]);
        }
        return false;
    }

    /**
     * Get a single row
     *
     * @param $table
     * @param string $where
     * @param string $orderBy
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    public function getOneRow($table, $where = "", $orderBy = "", $fields = array())
    {
        $this->query = "SELECT " . (count($fields) > 0 ? join(",", $fields) : "*") . " FROM " . $table . ($where != "" ? " WHERE " . $where : null) . ($orderBy != "" ? " ORDER BY " . $orderBy : null . " LIMIT 1");
        $arrRet = $this->select($this->query);
        return current($arrRet);
    }

    /**
     * Insert an array on the table key
     *
     * @param array $data
     * @return int affected row id
     * @throws Exception
     */
    public function insertFromArray($data)
    {
        if (!isset($data['table'])) {
            throw new Exception("No table found on " . __METHOD__, 1002);
        }
        if (count($data) < 2) {
            throw new Exception("No data fields found on " . __METHOD__, 1003);
        }
        $query = "INSERT INTO " . $data['table'];
        unset($data['table']);
        $query .= "(" . implode(",", array_keys($data)) . ") VALUES";
        $query .= "('" . implode("','", $data) . "')";
        return $this->insert($query);
    }

    /**
     * Update row on the pairs of key(s) and value(s) with where key!
     *
     * @param array $data
     * @return int affected row numbers
     * @throws Exception
     */
    public function updateFromArray($data)
    {
        if (!isset($data['table'])) {
            throw new Exception("NO TABLE: " . __METHOD__ . ", " . __LINE__);
        }
        if (count($data) < 2) {
            throw new Exception("NO DATAFIELDS: " . __METHOD__ . ", " . __LINE__);
        }
        $where = " WHERE " . $data['where'];
        unset($data['where']);
        $query = "UPDATE " . $data['table'] . " SET ";
        unset($data['table']);
        $i = 0;
        foreach ($data as $k => $v) {
            if ($i > 0) {
                $query .= "," . $k . "='" . $v . "'";
            } else {
                $query .= $k . "='" . $v . "'";
            }
            $i++;
        }
        $query .= $where;
        return $this->update($query);
    }

    /**
     * Check table is exists
     *
     * @param $table
     * @return bool
     * @throws Exception
     */
    public function tableExists($table)
    {
        $ret = $this->select("SHOW TABLES LIKE '" . $table . "'");
        if (count($ret) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Call a mysqli procedure
     *
     * @param $function
     * @return array
     * @throws Exception
     */
    public function call($function)
    {
        return $this->select($function);
    }

    /**
     * Close connection if object dismissed
     */
    public function __destruct()
    {
        $this->close();
    }

}