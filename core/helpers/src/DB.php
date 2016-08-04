<?php
/**
 * Contain Mvc\helpers\DB
 * Handle database
 */

namespace Mvc\helpers;


use PDO;
use PDOException;

class DB
{
    public $db = null;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        try
        {
            // Connect to database.
            $dsn = Config::getConfig('db.driver') . ':host=' . Config::getConfig('db.host');
            $dsn .= ';dbname=' . Config::getConfig('db.name');
            $dsn .= ';charset=' . Config::getConfig('db.charset');
            $this->db = new PDO($dsn, Config::getConfig('db.username'), Config::getConfig('db.password'));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $ex)
        {
            echo 'Connection failed: ' . $ex->getMessage();
            exit;
        }
    }

    /**
     * @param $table
     * @param array $sql_conditions
     * @param array $orderColumn
     * @param null $limit
     * @param int $offset
     * @return array
     */
    public function getRecords($table, $sql_conditions = array(), $orderColumn = array(), $limit = null, $offset = 0)
    {
        $num_of_cond = 0;
        $num_of_orders = 0;
        $sql = 'SELECT * FROM ' . $table . ' WHERE 1';

        // Implement WHERE conditions
        if (count($sql_conditions))
        {
            foreach ($sql_conditions as $conditions)
            {
                $num_of_cond++;
                $sql .= ' AND ' . $conditions['column'] . ' ' . $conditions['op'] . ' :condition' . $num_of_cond;
            }
        }

        // Implement ORDER BY
        if (count($orderColumn))
        {
            $sql .= ' ORDER BY';
            foreach ($orderColumn as $column) {
                if ($num_of_orders) $sql .= ' ,';
                $num_of_orders++;
                $sql .= ' ' . $column['column'] . ' ' . $column['order'];
            }

        }

        //Implement LIMIT
        $sql .= $limit ? ' LIMIT :offset, :limit' : '';

        // Prepare, bind and execute
        $stmt = $this->db->prepare($sql);
        while ($num_of_cond)
        {
           $stmt->bindParam(':condition' . $num_of_cond,
                $sql_conditions[$num_of_cond - 1]['cond']);
            $num_of_cond--;
        }
        if ($limit)
        {
            $limit = (int)$limit;
            $offset = (int) $offset;
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @return array
     */
    public function executeSql($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $table
     * @return array
     * Get all columns name existing in table
     */
    public function getColumnNames($table)
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $table . ' LIMIT 1');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($result) ? array_keys($result) : array();
    }

}