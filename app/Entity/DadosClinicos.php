<?php
namespace App\Entity;

use \App\Db\Database;
use \App\Utils\Response;
use \PDO;

class DadosClinicos
{
    public $id;
    public $pacienteId;
    public $doencasPreexistentes; /* Definidas a nivel de GUI */
    public $cirurgias; /* Definidas a nivel de GUI */             
    public $alergias;  /* Definidas a nivel de GUI */
    public $medicacoesEmUso; /* Definidas a nivel de GUI */
    public $fuma;
    public $bebe;
    public $drogas;
    public $historicoFamiliar;
    public $historicoPsiquiatrico;
    public $ideacoesSuicidas;
    public $numTentativasSuicidio;

    private const TABLE_NAME = 'dados_clinicos';

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
 
        // ================== Outras validações (campos obrigatórios) =====================
        if ( isset($this->doencasPreexistentes) == 0 )    {
                $response = new Response( 99, "Campo obrigatório (doencasPreexistentes) não definido.", $this->id );
                return $response;
        }
        if ( isset($this->cirurgias) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (cirurgias) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->alergias) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (alergias) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->medicacoesEmUso) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (medicacoesEmUso) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->fuma) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (fuma) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->bebe) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (bebe) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->drogas) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (drogas) não definido.", $this->id );
            return $response;
        } 
        if ( isset($this->historicoFamiliar) == 0 )    {
                $response = new Response( 99, "Campo obrigatório (historicoFamiliar) não definido.", $this->id );  
                return $response;
            } 
        if ( isset($this->historicoPsiquiatrico) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (historicoPsiquiatrico) não definido.", $this->id );  
            return $response;
        }
        if ( isset($this->ideacoesSuicidas) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (ideacoesSuicidas) não definido.", $this->id );  
            return $response;
        }
        if ( isset($this->numTentativasSuicidio) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (numTentativasSuicidio) não definido.", $this->id );  
            return $response;
        }

        $obDatabase = (new Database( self::TABLE_NAME ));
        $this->id = $obDatabase->insert(get_object_vars($this));
        return true;
    }

    public function update(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }

        return ((new Database( self::TABLE_NAME ))->update('id = ' . $this->id, get_object_vars($this)));
    }

    public function delete(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
        return ((new Database( self::TABLE_NAME ))->delete('id = ' . $this->id));
    }

    public static function getDadosClinicosById($id) {
        $obj = ((new Database( self::TABLE_NAME ))->select('id = ' . $id)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getDadosClinicosByField($field, $value) {
        $obj = ((new Database( self::TABLE_NAME ))->select("$field = " . $value)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getAllDadosClinicoss($where = null, $order = null, $limit = null) {
        return ((new Database( self::TABLE_NAME ))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class));
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
?>
