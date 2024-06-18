<?php
namespace App\Entity;

use \App\Db\Database;
use \App\Utils\Response;
use \PDO;

class DoencasCID
{
    public $id;
    public $cod_cid;
    public $nome; 

    private const TABLE_NAME = 'doencas_cid';

    public function __construct(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
    }

    public function setData(array $data)    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function select( array $data )   {
        if ( $data != null )      {
             $this->setData($data); 
        }
        // echo " ID >> " . $this->cod_cid;
        
        $response = null;
        $listaDoencas = $this->getAllDoencas( "id <> 0 " );

        // $n = count( $listaDoencas ); 
        if ( count( $listaDoencas ) > 0 )   {
             $response = new Response( 0, "Sucesso.",  $listaDoencas );
        } 
        return $response; 
    }

    public static function getAllDoencas($where = null, $order = null, $limit = null) {
        return ( (new Database( self::TABLE_NAME ))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class));
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
?>
