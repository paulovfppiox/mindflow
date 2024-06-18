<?php
require_once __DIR__ . '/Db/Database.php';

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
    $attributes = '';

    foreach ($columns as $column => $definition) {
        $attributes .= "    public \$$column;\n";
    }

    $template = file_get_contents(__DIR__ . '/entity_template.php');
    $entityClass = str_replace(
        ['{CLASS_NAME}', '{TABLE_NAME}', '{ATTRIBUTES}'],
        [$className, $tableName, $attributes],
        $template
    );

    return $entityClass;
}

function main() {
    $schema = parseSchema(__DIR__ . '/schema.json');
    $tables = $schema['tables'];

    foreach ($tables as $tableName => $columns) {
        // Generate and execute CREATE TABLE SQL
        $sql = generateCreateTableSQL($tableName, $columns);
        $db = Database::getInstance();
        $db->execute($sql);

        // Generate PHP entity class
        $entityClass = generateEntityClass($tableName, $columns);
        file_put_contents(__DIR__ . "/Entity/" . ucfirst($tableName) . ".php", $entityClass);
        // file_put_contents("C:\wamp64\www\mindflow\app\Entity\ $tableName.php", $entityClass);

    }
}

main();
