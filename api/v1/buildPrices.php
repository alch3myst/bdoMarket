<?php
ini_set('max_execution_time', 300);
function buildPrices()
{
    // "Connect to db"
    $db = new SQLite3("../../src/db/bdomarket.sqlite");

    // Reset Table
    $db->exec("DROP TABLE prices");

    // Create the values table
    $db->exec("CREATE TABLE IF NOT EXISTS prices(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name TEXT UNIQUE, price INTEGER)");

    // Fetch all jsons
    $files = array_diff(scandir("../../src/MarketData/"), array('.', '..'));

    // For each json insert into db as a value
    foreach ($files as $file => $file_name) {
        try {
            $file_path = "../../src/MarketData/" . $file_name;
            $json_file = json_decode(file_get_contents($file_path), true);

            foreach ($json_file["marketList"] as $key => $value) {
                $query = $db->prepare('INSERT INTO prices(name, price) VALUES(?, ?)');
                $query->bindValue(1, $value["name"]);
                $query->bindValue(2, $value["minPrice"]);
                $query->execute();
            }
        } catch (\Throwable $th) {
            echo "No file :v";
        }
    }
    $db->close();
}

buildPrices();