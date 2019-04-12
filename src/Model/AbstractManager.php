<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */

namespace App\Model;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    /**
     * @var \PDO
     */
    protected $pdo; //variable de connexion
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $className;
    /**
     * @var string
     */
    protected $tableToJoin;

    protected $secondTableToJoin;

    /**
     * AbstractManager constructor.
     * @param string $table
     * @param string $tableToJoin
     * @param string $secondTableToJoin
     */
    public function __construct(string $table, string $tableToJoin = '', string $secondTableToJoin = '')
    {
        $this->table = $table;
        $this->tableToJoin = $tableToJoin;
        $this->secondTableToJoin = $secondTableToJoin;
        $this->className = __NAMESPACE__ . '\\' . ucfirst($table);
        $this->pdo = (new Connection())->getPdoConnection();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }

    /**
     * Get one row from database by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }


    /**
     * get one table joined with two other tables
     * @param string $foreignKey
     * @param string $primaryKey
     * @param string $secondForeignKey
     * @return array
     */
    public function selectAllDoubleJoin(string $foreignKey, string $primaryKey, string $secondForeignKey): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' JOIN ' . $this->tableToJoin . ' ON ' .
            $this->tableToJoin . '.' . $foreignKey . '=' . $this->table . '.' . $primaryKey . ' JOIN ' .
            $this->secondTableToJoin . ' ON ' .
            $this->secondTableToJoin . '.' . $secondForeignKey . '=' . $this->table . '.' . $primaryKey)->fetchAll();
    }

    /**
     * get one of the two tables joined
     * @return array
     */
    public function selectAllFromFirstJoined(): array
    {

        return $this->pdo->query('SELECT * FROM ' . $this->tableToJoin)->fetchAll();
    }

    /**
     * get the second of the two tables joined
     * @return array
     */
    public function selectAllFromSecondJoined(): array
    {

        return $this->pdo->query('SELECT * FROM ' . $this->secondTableToJoin)->fetchAll();
    }
}
