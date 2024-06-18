<?php
require_once 'cors.php';
require_once __DIR__ . '/vendor/autoload.php';
use App\Entity\Usuarios; 
use App\Entity\Response;

/************* I- INSERT **************/
 function InsertExample()              {
    $input = '{ "nome": "Aline Barros", "email": "aline@gmail.com", "senha": "123", "rua": "R. Jagua OK",  "bairro": "Jaguaribe", "municipio":"João Pessoa", "uf": "PB", "profissao": "professor", "tipo": "paciente" }';
    $data_array = json_decode($input, true);
    echo "Dados novo usuario: <br>";
    print_r( $data_array );
    $usu = new Usuarios;
    $resp = $usu->insert( $data_array );
    print_r( $resp );
}
// InsertExample();

/************* II- UPDATE **************/
function UpdateExample()                {
    $input = '{ "id": "2", "nome": "Pedro G F Paiva", "email": "pidow@gmail.com", "senha": "abc123", "rua": "R. Teste OK",  "bairro": "Jaguaribe", "municipio":"João Pessoa", "uf": "PB", "profissao": "professor", "tipo": "paciente" }';
    $data_array = json_decode($input, true);

    $usu = new Usuarios;
    $resp = $usu->update( $data_array );
    print_r( $resp );
}
// UpdateExample();

/************* III- DELETE **************/
function DeleteExample()                {
    $input = '{ "id": "1" }';
    $data_array = json_decode($input, true);

    $usu = new Usuarios;
    $resp = $usu->delete( $data_array );
    print_r( $resp );
}

/**************** IV- GET **************/
function GetExample()    {
    $usu = new Usuarios;
    
    // 1) Get By ID
    // $resp = $author->getBookById( 1 );

    // 2) Get By Field Value
    /* $resp = $usu->getUsuarioByField('nome', "'Bruno Gustavo'");
    echo "<br><br>---- Get Example Data ----<br>";
    print_r( $resp );*/

    // 3) Get By where clase
    $resp = $usu->getAllUsuarios("( uf = 'PB' ) and ( bairro = 'Jaguaribe' ) ");
    echo "<br><br>---- Get Example Data ----<br>";
    print_r( $resp );

}
// DeleteExample();
InsertExample();
// UpdateExample();
// GetExample();
?>