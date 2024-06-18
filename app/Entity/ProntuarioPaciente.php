<?php
namespace App\Entity;

use \App\Db\Database;
use \App\Utils\Response;
use \App\Entity\Profissional;
use \App\Entity\Usuarios;
use \App\Entity\DadosClinicos;
use \PDO;

class ProntuarioPaciente
{
    public $usuario;
    public $dadosClinicos;

    private const TABLE_NAME_I  = 'usuarios';
    private const TABLE_NAME_II = 'dados_clinicos';

    public function __construct()       {
    } 

    public function insert( array $data = null )    {
        
        /** Caso dados não definidos */
        if ( isset( $data['usuario'] ) == 0 )       {
             $response = new Response( 99, "Dados de usuário não definidos!", null );
             return $response;
        }
        if ( isset( $data['dadosClinicos'] ) == 0 ) {
            $response = new Response( 99, "Dados clínicos de usuário não definidos!", null );
            return $response;
       }

       $usuarioID = null;
       $usuario = new Usuarios; 
       $response = $usuario->insert( $data['usuario'] );
       // Caso erro de cadastro de usuário 
       if ( $response->code == 99 )
            return $response;
       else {
            // print_r( $response );
            $usuarioID = $response->data;
       }
       
       $dadosClinicos = new DadosClinicos;
       $data['dadosClinicos']['pacienteId'] = $usuarioID;
       $response = $dadosClinicos->insert( $data['dadosClinicos'] );
       if ( $response = 1 ) {
            $response = new Response( 0, "Dados de prontuário cadastrados com sucesso!", null );
       }
       return $response;
    }

    public function update(array $data = null)      {
        if ($data != null) {
            $this->setData($data);
        }
        if ( !isset( $this->id ) )   
             return new Response( 99, "Campo 'id' indefinido", null );
        
        $response = null;
        $retorno = ( (new Database( self::TABLE_NAME ))->update('id = ' . $this->id, get_object_vars($this)));
        if ( $retorno > 0 )    {
             $response = new Response( 0, "Atualizacao realizada com sucesso!", $this->id );
       }
       return $response;
    }

    public function select(array $data = null)      {
        if ( $data != null )      {
             $this->setData($data); 
        }

        $usuario = null;

        if ( $this->nome )        {
             // echo "NOME ?!?! " . $this->nome;
             $usuario = $this->getUsuarioByField('nome', "'" . $this->nome . "'"  );
        }
        else if ( $this->email )  {
             $usuario = $this->getUsuarioByField('email', "'" . $this->email . "'" );
        }
        else {
            return new Response( 99, "Forneca chaves de busca de usuario! ", null );
        }
        if ( $usuario == null )
            return new Response( 99, "Usuário não encontrado! ", null );

        $data = json_encode( $usuario );
        $response = new Response( 0, "Sucesso! ", $usuario );
        return $response;
    }

    /* Deletar por ID. Verificar existência de registro antecipadamente */
    public function delete(array $data = null)      {    
        if ($data != null)      {
            $this->setData($data);
        }

        /**************************************
         * echo '*** ID ?!? ' . $this->id;
        $response = $this->select( $data );
        echo "*** ACHOU ****";
        print_r( $response );
        **************************************/
        
        $retorno = ( (new Database( self::TABLE_NAME ))->delete('id = ' . $this->id));
        $response = new Response( 0, "Sucesso: Usuario removido ", null );
        return $response;
    }

    public static function getUsuarioById($id)        {
        $obj = ( (new Database( self::TABLE_NAME ))->select('id = ' . $id)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getUsuarioByField($field, $value) {
        $obj = ( (new Database( self::TABLE_NAME ))->select("$field = " . $value)->fetchObject(self::class));
        return $obj ?: null;
    }

    public static function getAllUsuarios($where = null, $order = null, $limit = null) {
        return ( (new Database( self::TABLE_NAME ))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class));
    }
 
    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
?>

