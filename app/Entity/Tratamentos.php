<?php
namespace App\Entity;

use \App\Db\Database;
use \PDO;

class Tratamentos
{
    public $id;
    public $paciente_id;
    public $profissional_id;
    public $descricao;
    public $data_inicio;
    public $data_fim;
    public $status;


    private const TABLE_NAME = 'tratamentos';

    public function __construct(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
    }

    public function setData(array $data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function insert(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }

        $obDatabase = Database::getInstance(self::TABLE_NAME);
        $this->id = $obDatabase->insert(get_object_vars($this));
        return true;
    }

    public function update(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }

        return (Database::getInstance(self::TABLE_NAME)->update('id = ' . $this->id, get_object_vars($this)));
    }

    public function delete(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
        return (Database::getInstance(self::TABLE_NAME)->delete('id = ' . $this->id));
    }

    public static function getTratamentoById($id) {
        $obj = (Database::getInstance(self::TABLE_NAME)->select('id = ' . $id)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getTratamentoByField($field, $value) {
        $obj = (Database::getInstance(self::TABLE_NAME)->select("$field = " . $value)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getAllTratamentos($where = null, $order = null, $limit = null) {
        return (Database::getInstance(self::TABLE_NAME)->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class));
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
?>
