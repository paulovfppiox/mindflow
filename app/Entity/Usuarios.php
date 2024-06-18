<?php
namespace App\Entity;

use \App\Db\Database;
use \App\Utils\Response;
use \App\Entity\Profissional;
use \PDO;

class Usuarios
{
    public $id;
    public $nome;
    public $genero;
    public $email;
    public $senha; 
    public $celular;
    public $profissao;
    public $tipo;
    public $dataNascimento;
    public $telefone;
    public $crm_crp;
    public $tipoMoradia;
    public $rua;
    public $bairro;
    public $municipio;
    public $uf;
    public $dataRegistro;

    public $meuMedicoId1; 
    public $meuMedicoId2; 
    public $meuPsicologoId1; 
    public $meuPsicologoId2;
    public $meuPsicologoId3;

    private const TABLE_NAME = 'usuarios';

    public function __construct(array $data = null) {
        if ($data != null) {
            $this->setData($data);
        }
    }

    public function setData(array $data)            {
        foreach ($data as $key => $value)   {
            $this->$key = $value;
        }
    }

    public function insert( array $data = null )    {
        if ( $data != null )      {
             $this->setData($data);
        }
        $this->dataRegistro = date('Y-m-d H:i:s');
        $response = null;
        
        // ================== Validar insercao de campos Únicos (Nome) ================== 
        $key = "'" . $this->nome . "'";
        $dataExists = $this->getUsuarioByField('nome', $key );
        if ( isset( $dataExists ) ) {
             $response = new Response( 99, "Nome de usuário já cadastrado", $this->id );
             return $response;
        }
        // ================== Validar insercao de campos Únicos (Email) ================== 
        $key = "'" . $this->email . "'";
        $dataExists = $this->getUsuarioByField('email', $key );
        if ( isset( $dataExists ) ) {
             $response = new Response( 99, "Email já cadastrado", $this->id );
             return $response;
        }

        $msg = "Psicólogo não cadastrado.";

        // ================== Validar existencia dos IDs de profissionais ================== 
        if ( $this->meuPsicologoId1 > 0 )  {
                $dataExists = $this->getUsuarioById( $this->meuPsicologoId1 );
                if ( !isset( $dataExists ) )       {
                    $response = new Response( 99, $msg, $this->id );
                    return $response;
                }
         } 
         if ( $this->meuPsicologoId2 > 0 )  {
                $dataExists = $this->getUsuarioById( $this->meuPsicologoId2 );
                if ( !isset( $dataExists ) )       {
                    $response = new Response( 99, $msg, $this->id );
                    return $response;
                }
         }
         if ( $this->meuPsicologoId3 > 0 )  {
                $dataExists = $this->getUsuarioById( $this->meuPsicologoId3 );
                if ( !isset( $dataExists ) )       {
                    $response = new Response( 99, $msg, $this->id );
                    return $response;
                }
         }
        

        // ================== Outras validações (campos obrigatórios) =====================
        if ( isset($this->genero) == 0 )    {
             $response = new Response( 99, "Campo obrigatório (Gênero) não definido.", $this->id );
             return $response;
        }
        if ( isset($this->email) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Email) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->senha) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Senha) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->celular) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Celular) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->profissao) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Profissão) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->tipo) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Tipo de Usuário) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->dataNascimento) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Data de Nascimento) não definido.", $this->id );
            return $response;
        } 

        $obDatabase = (new Database( self::TABLE_NAME ));
        $this->id = $obDatabase->insert(get_object_vars($this));
        // echo "ID?? " . $this->id;
       
        if ( $this->id > 0 )    {
             $response = new Response( 0, "Cadastro realizado com sucesso!", $this->id );
        }
        return $response;
    }


    public function registrar( array $data = null )    {
        if ( $data != null )      {
             $this->setData($data);
        }
        $this->dataRegistro = date('Y-m-d H:i:s');
        $response = null;
        
        // ================== Validar insercao de campos Únicos (Nome) ================== 
        $key = "'" . $this->nome . "'";
        $dataExists = $this->getUsuarioByField('nome', $key );
        if ( isset( $dataExists ) ) {
             $response = new Response( 99, "Nome de usuário já cadastrado", $this->id );
             return $response;
        }
        // ================== Validar insercao de campos Únicos (Email) ================== 
        $key = "'" . $this->email . "'";
        $dataExists = $this->getUsuarioByField('email', $key );
        if ( isset( $dataExists ) ) {
             $response = new Response( 99, "Email já cadastrado", $this->id );
             return $response;
        }

        // ================== Outras validações (campos obrigatórios) =====================
        if ( isset($this->email) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Email) não definido.", $this->id );
            return $response;
        }
        if ( isset($this->senha) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Senha) não definido.", $this->id );
            return $response;
        }  
        if ( isset($this->tipo) == 0 )    {
            $response = new Response( 99, "Campo obrigatório (Tipo de Usuário) não definido.", $this->id );
            return $response;
        } 

        $obDatabase = (new Database( self::TABLE_NAME ));
        $this->id = $obDatabase->insert(get_object_vars($this));
        // echo "ID?? " . $this->id;
       
        if ( $this->id > 0 )    {
             $response = new Response( 0, "Cadastro realizado com sucesso!", $this->id );
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

        $listaUsuarios = null; // Caso busca de mais de um user.
        $usuario = null;

        if ( $this->nome )        {
             $usuario = $this->getUsuarioByField('nome', "'" . $this->nome . "'"  );
        }
        else if ( $this->email )  {
             $usuario = $this->getUsuarioByField('email', "'" . $this->email . "'" );
        }
        else if ( $this->meuMedicoId1  ) {
            $listaUsuarios = $this->getAllUsuarios('meuMedicoId1 = ' .  "'" . $this->meuMedicoId1 . "'" );
            // print_r( $listaUsuarios );
            $response = new Response( 0, "Sucesso! ", $listaUsuarios );
            return $response;
        } else {
            return new Response( 99, "Forneca chaves de busca de usuario! ", null );
        }
        if ( $usuario == null )
            return new Response( 99, "Usuário(s) não encontrado(s)! ", null );

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

