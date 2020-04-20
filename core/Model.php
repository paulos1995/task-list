<?php

namespace Core;

abstract class Model
{
    /**
     * @var \mysqli;
     */
    public $mysqli;
    protected $table;

    function __construct()
    {
        $this->mysqli = include CONFIG_PATH . 'database.php';
    }

    function __destruct()
    {
        $this->mysqli->close();
    }
}