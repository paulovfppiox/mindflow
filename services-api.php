<?php
require_once 'cors.php';
 
/*******************************************************
 *               VALID INPUT JSON
 * {
"data":
{
	"entity":"author",
	"operation":"insert",
	"object":{
		"attributes":"values"
      }
    }
} * 
******************************************************/

require_once __DIR__ . '/vendor/autoload.php';
use App\Entity\Usuarios;
use App\Entity\Profissional;
use App\Entity\DoencasCID;
use App\Entity\ProntuarioPaciente;
use App\Entity\ContaProfissional;
use App\Utils\Response;
use App\Entity\ConfigurationAPI;

$current_time = microtime(true); // Get the current time with milliseconds
$SERVER_FORMATTED_TIME = date('H:i:s.') . sprintf("%03d", ($current_time - floor($current_time)) * 1000); // Format the time with milliseconds

/********************************************************************************************************************************************

      JSON -> {   "dados":{"entidade":"operacaoDetalhada","operacao":"consultar","objeto":{"data":"2023-04-25"} } }

********************************************************************************************************************************************/
function generateLog( $data )
{
      // Get the current month
      $currentMonth = date('Y-m');

      // Create the log file name
      $logFileName = 'log-' . $currentMonth . '.txt';
      $message = $data;
	
      $file = fopen($logFileName, 'a'); // MODO DE APPEND

      if ($file) {
          fwrite($file, $message . PHP_EOL);
          fclose($file);
      } 
}
// echo "method? . " . $_SERVER[ 'REQUEST_METHOD' ];

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
{
     // if ( ConfigurationAPI::$EXECUTION_MODE == 'DEBUG')
     // print_r( file_get_contents('php://input') );
     $jsonStrIn = file_get_contents( 'php://input' );
     $data = json_decode( $jsonStrIn, true );
     $logIn = "[ " . $SERVER_FORMATTED_TIME . " ] IN >> " . $jsonStrIn;
     generateLog( $logIn );


     if ( ( $data === null ) && ( json_last_error() !== JSON_ERROR_NONE ) )     {
          echo "Invalid JSON error: " . json_last_error_msg();
          exit;
     }
     $entityObj = null;
     $response  = null;

     $DATA_ENTITY = $data['data']['entity'];
     if ( isset( $data['data']['object'] ) )
          $DATA_OBJECT = $data['data']['object'];

     $DATA_OPERATION = $data['data']['operation'];

     switch ( $DATA_ENTITY )
     {
       case 'usuarios' :
             $entityObj = new Usuarios;
             break;

       case 'doencas' :
             $entityObj = new DoencasCID;
             break;      
      
       case 'profissional' :
             $entityObj = new Profissional;
             break;   
             
       case 'prontuarioPaciente' :
             $entityObj = new ProntuarioPaciente;
             break;         
      
       case 'contaProfissional' :
             $entityObj = new ContaProfissional;
             break;  
      }

     switch ( $DATA_OPERATION )
     {
        case 'cadastrar':
              // echo "DADOS: <br>";
              // print_r( $data['data']['object'] );
              // echo 'Cadastra response ------- <br>';
              $response = $entityObj->insert( $DATA_OBJECT );
              $json = $response->toJson();
              echo $json;
		  break;

        case 'atualizar':
              $response = $entityObj->update( $DATA_OBJECT );
              $json = $response->toJson();
              echo $json;
              break;

        case 'consultar':
              $response = $entityObj->select( $DATA_OBJECT );
              $json = $response->toJson();
              echo $json;
              break;

        case 'deletar':
              $response = $entityObj->delete( $DATA_OBJECT );
              $json = $response->toJson();
              echo $json;
              break;
       
       /* Registro de Usuários */
       case 'registrar':
             //$response = $entityObj->registrar( $DATA_OBJECT );
             $response = $entityObj->registrar( $DATA_OBJECT );
             $json = $response->toJson();
             echo $json; 
             break;      
      
    } 
    
    // echo 'Entity?' . $DATA_ENTITY;
    // echo "<br>Response é objeto? " . is_object( $response );
    // echo "KKKKKKKKKKK >>>>" . $response;
 
    $json = stripslashes( json_encode( $response ) );
    generateLog( "[ " . $SERVER_FORMATTED_TIME . " ] OUT << " . $json );
 }

 

?>

