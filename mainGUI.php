<?php
require_once 'cors.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Response;

/************* I- INSERT *************/
function InsertExample($data_array) {
    $author = new Book;
    $resp = $author->insert($data_array);
    return $resp;
}

/************* II- UPDATE **************/
function UpdateExample($data_array) {
    $author = new Book;
    $resp = $author->update($data_array);
    return $resp;
}

/************* III- DELETE **************/
function DeleteExample($data_array) {
    $author = new Book;
    $resp = $author->delete($data_array);
    return $resp;
}

/************* IV- GET **************/
function GetExample($id) {
    $author = new Book;
    $resp = $author->getBookById($id);
    return $resp;
}

// Handling form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['insert'])) {
        $input = json_decode($_POST['insert_data'], true);
        $response = InsertExample($input);
    } elseif (isset($_POST['update'])) {
        $input = json_decode($_POST['update_data'], true);
        $response = UpdateExample($input);
    } elseif (isset($_POST['delete'])) {
        $input = json_decode($_POST['delete_data'], true);
        $response = DeleteExample($input);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
</head>
<body>
    <h2>CRUD Operations</h2>

    <!-- INSERT Form -->
    <h3>Insert</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <textarea name="insert_data" rows="4" cols="50">{"id": "1", "title": "Lord", "book_id": "1", "published_date": "2024-01-01"}</textarea><br>
        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- UPDATE Form -->
    <h3>Update</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <textarea name="update_data" rows="4" cols="50">{"id": "1", "title": "Lord of Rings II", "book_id": "1", "published_date": "2023-01-01"}</textarea><br>
        <input type="submit" name="update" value="Update">
    </form>

    <!-- DELETE Form -->
    <h3>Delete</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <textarea name="delete_data" rows="2" cols="50">{"id": "1"}</textarea><br>
        <input type="submit" name="delete" value="Delete">
    </form>

    <!-- GET Form -->
    <h3>Get</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="number" name="get_id" value="1">
        <input type="submit" name="get" value="Get">
    </form>

    <?php
    if (isset($response)) {
        echo "<h3>Response</h3>";
        echo "<pre>";
        print_r($response);
        echo "</pre>";
    }
    ?>
</body>
</html>
