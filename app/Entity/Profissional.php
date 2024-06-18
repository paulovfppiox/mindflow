<?php
namespace App\Entity;

use \App\Db\Database;
use \App\Utils\Response;
use \App\Entity\Usuarios;
use \PDO;

class Profissional
{
    public $id;
    public $usuarioId;
    public $instituicao;
    public $curso;
    public $anoConclusao;
    public $abordagemPsicologia1;
    public $abordagemPsicologia2;
    public $abordagemPsicologia3;
    public $especialidade;
    public $valorConsulta;

    private const TABLE_NAME = 'profissional';

    public function __construct(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
    }

    public function setData(array $data)          {
        foreach ($data as $key => $value)   {
            $this->$key = $value;
        }
    }

    public function insert(array $data = null )    {
        
        $usu = new Usuarios;
        $json = $usu->select( $data['keys'] );
        
        $this->setData( $data['profissional'] );

        if ( $json->data == null )
             return new Response( 99, "Usuário não encontrado!", null ); 

        $this->usuarioId = $json->data->id;
        $this->id = file_get_contents('profissional-id.txt');
        $this->id++;
        
        $obDatabase = new Database( self::TABLE_NAME );
        $this->id = $obDatabase->insert(get_object_vars($this));
        file_put_contents( 'profissional-id.txt', $this->id );

        $response = new Response( 99, "Erro em cadastro de profissional!", null ); 
        if ( $this->id > 0 )    {
            $response = new Response( 0, "Cadastro realizado com sucesso!", $this->id ); 
        }    
        return $response;
    }

    public function update(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
        
        return ( (new Database( self::TABLE_NAME ))->update('id = ' . $this->id, get_object_vars($this)) );
    }

    public function delete(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
        return ( (new Database( self::TABLE_NAME ))->delete('id = ' . $this->id));
    }

    public static function getProfissionalById($id) {
        $obj = ( (new Database( self::TABLE_NAME ))->select('id = ' . $id)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getProfissionalByField($field, $value) {
        $obj = ( (new Database( self::TABLE_NAME ))->select("$field = " . $value)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getAllProfissionals($where = null, $order = null, $limit = null) {
        return ( (new Database( self::TABLE_NAME ))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class));
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
?>
