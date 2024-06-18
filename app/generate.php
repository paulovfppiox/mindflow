<?php
require_once 'Db/Database.php';

use App\Db\Database; 

function parseSchema($jsonFile) {
    $json = file_get_contents($jsonFile);
    return json_decode($json, true);
}

function generateCreateTableSQL($tableName, $columns) {
    $columnsSQL = [];
    foreach ($columns as $column => $definition) {
        $columnsSQL[] = "$column $definition";
    }
    $columnsSQL = implode(", ", $columnsSQL);
    return "CREATE TABLE IF NOT EXISTS $tableName ($columnsSQL);";
}

function generateEntityClass($tableName, $columns) {
    $className = ucfirst($tableName);
    $properties = '';
    $methods = '';

    foreach ($columns as $column => $definition) {
        $properties .= "\tprivate \$$column;\n";

        $camelCaseColumn = ucfirst($column);
        $methods .= "\tpublic function get$camelCaseColumn() {\n\t\treturn \$this->$column;\n\t}\n\n";
        $methods .= "\tpublic function set$camelCaseColumn(\$$column) {\n\t\t\$this->$column = \$$column;\n\t}\n\n";
    }

    return "<?php\nnamespace App\\Entity;\n\nclass $className {\n$properties\n$methods}\n";
}

function main() {
    $schema = parseSchema('C:\wamp64\www\mindflow\schema.json');
    $tables = $schema['tables'];

    foreach ($tables as $tableName => $columns) {
        // Generate and execute CREATE TABLE SQL
        $sql = generateCreateTableSQL($tableName, $columns);
        $db = Database::getInstance($tableName);
        $db->execute($sql);

        // Generate PHP entity class
        $entityClass = generateEntityClass($tableName, $columns);
        file_put_contents("C:\wamp64\www\mindflow\app\Entity\ $tableName.php", $entityClass);
     }
}

main();
