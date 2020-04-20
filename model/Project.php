<?php

namespace Model;

use Core\Model;
use Libs\Message;

class Project extends Model
{
    public $id;
    public $name;
    public $userId;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'projects';
    }

    /**
     * Get all user project
     *
     * @param integer $userId
     * @return array|bool
     */
    public function getUserProjects($userId)
    {
        if ($userId) {
            $query = "SELECT * FROM " . $this->table . " WHERE user_id = " . $userId;
            $result = $this->mysqli->query($query);
            $projects = [];
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
            return count($projects) > 0 ? $projects : false;
        }
        return false;
    }

    /**
     * Validate data before save project
     * @return bool
     */
    public function validate()
    {
        $hasError = false;

        if (!$this->name) {
            Message::Error('Name can not be empty');
            $hasError = true;
        }

        if (strlen($this->name) > 255) {
            Message::Error('Name must be less than 255 characters');
            $hasError = true;
        }

        if (!$this->userId) {
            Message::Error('You are not login');
            $hasError = true;
        }

        return $hasError ? false : true;
    }

    /**
     * Save project to DB
     * if project save - return record id
     * @return bool|mixed
     */
    public function save()
    {
        $query = "INSERT INTO " . $this->table . " (name, user_id) VALUES ('" .
            $this->name . "', " . $this->userId . ");";
        return $this->mysqli->query($query) ? $this->mysqli->insert_id : false;
    }

    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = " . $id . " LIMIT 1";
        $response = $this->mysqli->query($query)->fetch_assoc();
        $this->id = $response['id'];
        $this->name = $response['name'];
        $this->userId = $response['user_id'];
    }

    /**
     * Function update project name
     *
     * @param $name
     * @return bool
     */
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET name = '" . $this->name . "' WHERE id = " . $this->id;
        return $this->mysqli->query($query);
    }

    /**
     * Function delete project from DB
     *
     * @return bool
     */
    public function delete()
    {
        $this->deleteProjectTasks();
        $query = "DELETE FROM " . $this->table . " WHERE id = " . $this->id;
        return $this->mysqli->query($query);
    }

    /**
     * Delete all tasks from current project
     */
    private function deleteProjectTasks()
    {
        $model = new Task();
        $model->deleteTasksFromProject($this->id);
    }
}