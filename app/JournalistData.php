<?php

namespace App;

/**
 * Data from table journalist
 */
class JournalistData
{

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * create journalist table
     */
    public function createTable()
    {
        $command = 'CREATE TABLE IF NOT EXISTS journalist (
                        j_id   INTEGER PRIMARY KEY,
                        j_name TEXT NOT NULL,
                        j_alias TEXT NOT NULL,
                        j_group TEXT NOT NULL
                      )';

        // execute the sql command to create new table
        $this->pdo->exec($command);
    }

    /**
     * Insert a new journalist into the table
     */

    public function insertJournalist($journalists)
    {
        $sql = "";
        $columns = ['j_name', 'j_alias', 'j_group'];

        foreach ($journalists as $journalist) {

            $sql .= "INSERT INTO journalist ( " . implode(', ', $columns) . ") VALUES (\"" . implode('","', array_values($journalist)) . "\");";
        }
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        header("Refresh:0");
    }

    /**
     * get the journalist list in the database
     */
    public function getJournalist($param)
    {

        if (!empty($param['group'])) {
            $stmt = $this->pdo->prepare('SELECT *       
            FROM journalist
            WHERE j_group = :group;');
            $stmt->execute([':group' => $param['group']]);
        } elseif (!empty($param['id'])) {
            $stmt = $this->pdo->prepare('SELECT *       
            FROM journalist
            WHERE j_id = :id;');
            $stmt->execute([':id' => $param['id']]);
        } else {
            $stmt = $this->pdo->prepare('SELECT *       
            FROM journalist
            ;');

            $stmt->execute();
        }

        $result = "<tbody>";
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $result .= "<tr><td>" . $row['j_id'] . "</td><td>" . $row['j_name'] . "</td><td>" . $row['j_alias'] . "</td><td>" . $row['j_group'] . "</td></tr>";
        }
        $result .= "</tbody>";

        return $result;
    }
    /**
     * get the journalist group list in the database
     */
    public function getJournalistGroups()
    {

        $stmt = $this->pdo->query("SELECT j_group
            FROM journalist
            GROUP BY j_group
            ");

        $groups = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $groups[] = $row['j_group'];
        }
        return $groups;
    }
    /**
     * get the journalist id list in the database
     */
    public function getJournalistIds()
    {

        $stmt = $this->pdo->query("SELECT j_id
            FROM journalist
            ");

        $ids = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $ids[] = $row['j_id'];
        }
        return $ids;
    }
}
