<?php
/**
 * Contain Mvc\app\Model
 * Handel DB query and results
 */

namespace Mvc\app;

use Mvc\helpers\DB;

class Model
{

    protected $sql_query = null;
    protected $sql_conditions = array();
    protected $sql_order = array();
    protected $sql_limit = null;
    protected $sql_offset = 0;
    protected $existing_columns = array();
    protected $table = null;
    protected $db = null;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = new DB;
        // Generate $existing_columns, $table in controller of child class
    }

    /**
     * @return array
     */
    public function db_getResults()
    {
        // Create query from protected vars and call method in DB => $results
        if (isset($this->sql_query))
        {
            $sql = $this->sql_query;
            $results = $this->db->executeSql($sql);
        }
        else
        {
            $results = $this->db->getRecords($this->table, $this->sql_conditions,
                $this->sql_order, $this->sql_limit, $this->sql_offset);
        }
        $this->sql_conditions = array();
        $this->sql_offset = 0;
        $this->sql_limit = null;
        $this->sql_order = array();
        $this->sql_query = null;

        return $results;
    }

    /**
     * @param $column
     * @param string $order
     * @return $this
     * @throws \Exception
     */
    public function db_setOrder($column, $order='DESC')
    {
        if (in_array($column, $this->existing_columns) &&
            in_array(strtoupper($order), ['DESC', 'ASC']))
        {
            $this->sql_order[]= array(
                'column' => $column,
                'order' => $order,
            );
        }
        else
        {
            throw new \Exception ('Wrong order parameters. Column: ' . $column . ' Order: ' . $order);
        }
        return $this;
    }

    /**
     * @param $column
     * @param $cond
     * @param string $op
     * @return $this
     * @throws \Exception
     */
    public function db_setConditions($column, $cond, $op = '=')
    {
        if (in_array($column, $this->existing_columns) &&
            in_array(strtoupper($op), ['<', '<=', '>', '>=', 'LIKE', '=', ])) // Add more operations
        {
            $this->sql_conditions[] = array(
                'column' => $column,
                'cond' => $cond,
                'op' => $op,
                );
        }
        else
        {
            $cond = trim(stripslashes(htmlspecialchars($cond)));
            throw new \Exception ('Wrong order parameters. Column: ' . $column . ' Op: ' . $op . ' Condition: ' . $cond);
        }
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function db_setOffset($offset = 0)
    {
        $offset = (int) $offset;
        $this->sql_offset = $offset;
        return $this;
    }

    public function db_setLimit($limit = 0)
    {
        $limit = (int) $limit;
        if ($limit>0) $this->sql_limit = $limit;
        return $this;
    }

    public function db_setQuery($sql)
    {
        $this->sql_query = $sql;
        return $this;
    }

}