<?php

namespace App\Model;

class AdminRoomManager extends AbstractManager
{

    const TABLE = 'room';

    /**
     * RoomManager constructor
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Needed : those are used with checkboxes
     * @return array
     */
    public function selectAllCaracteristics()
    {
        return $this->pdo->query('SELECT * FROM caracteristic ;')->fetchAll();
    }

    /**
     * Transfers the room data to the database
     * @param array $data
     * @param int $roomId
     */
    public function insert(array $data, int $roomId): void
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO `room` (`id` ,`name` , `description` , `price`)
        VALUES ( :id, :name, :description, :price );");
        $statement->bindValue('id', $roomId, \PDO::PARAM_INT);
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $data['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $data['price'], \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * transfers the caracteristic data to the database
     * @param array $data
     * @param int $roomId
     */
    public function insertCaracteristics(array $data, int $roomId): void
    {
        foreach ($data as $caracteristic) {
            $statement = $this->pdo->prepare("INSERT INTO `room_caracteristic`
            (`room_id`,`caracteristic_id`)
            VALUES(:room_id, :caracteristic_id);");
            $statement->bindValue('room_id', $roomId, \PDO::PARAM_STR);
            $statement->bindValue('caracteristic_id', $caracteristic, \PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     * transfers the photos names in the database (only the names, not the files!)
     * @param array $photos
     * @param int $roomId
     */
    public function addPhotosNamesInDatabase(array $photos, int $roomId): void
    {
        foreach ($photos as $photo) {
            $statement = $this->pdo->prepare("INSERT INTO `room_photo`
            (`room_id`,`photo_name`)
            VALUES (:room_id, :photo_name);");
            $statement->bindValue('room_id', $roomId, \PDO::PARAM_INT);
            $statement->bindValue('photo_name', $photo, \PDO::PARAM_STR);
            $statement->execute();
        }
    }
}