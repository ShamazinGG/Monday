<?php
include 'App/Config.php';
abstract class Model
{

    protected $attributes = [];
    protected $table;


    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            // показывать предупреждение если есть ошибки
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $db;
    }

    function getAll()
    {
        $table = $this->table;
        $db = static::getDB();
        $attributes = $db->query("SELECT * FROM $table");
        return $attributes->fetchAll(PDO::FETCH_ASSOC);

        //return json_decode(file_get_contents($file), true);

    }

    function getById($id)
    {
        $db = static::getDB();
        $attributes = $this->getAll();
        foreach ($attributes as $attribute) {
            if ($attribute['id'] == $id) {
                return $attribute;
            }
        }
        return null;
    }

    function putDB($data)
    {
        $attributes = implode(',', $this->attributes);
        $db = static::getDB();
        $stmt = "INSERT INTO $this->table ($attributes) VALUES (NULL, '$data')";
        $db->query($stmt);
    }


    function create ($data)
    {
        $attributes = implode("','", $data);
        var_dump($attributes);
        $this->putDB($attributes);
        return $data;

    }

    function update($id,$data)
    {
        $attributes = $this->getAll();
        foreach ($attributes as $i => $attribute)
        {
                if($attribute['id'] == $id)
                {
                    $attributes = implode("','", $data);
                    $this->putDB($attributes);
                }
            }
    }


    function delete($id)
    {
        $attributes = $this->getAll();
        foreach ($attributes as $i => $attribute) {
            if ($attribute['id'] == $id) {
                unset($attributes[$i]);
                $this->putDB($attributes);
            }
        }
    }




//    function validate($attribute, &$errors)
//    {
//
//    }
    public function getId()
    {
        $url = $_SERVER['REQUEST_URI'];
        $routeParts = explode('/', $url);
        $id = $routeParts[2];
        return $id;
    }




}