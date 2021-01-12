<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function buildProcessRecipes()
{
    // "Connect to db"
    $db = new SQLite3("../../src/db/bdomarket.sqlite");

    // Reset Table
    $db->exec("DROP TABLE processRecipes");

    // Create the values table
    $db->exec("CREATE TABLE IF NOT EXISTS processRecipes(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name TEXT, cost INTEGER, price INTEGER, rare_price INTEGER, recipe TEXT, canProc TEXT)");

    // Get the json file content
    $file_path = "../../src/recipes/process.json";
    $json_file = json_decode(file_get_contents($file_path), true);

    // For each recipe on cooking.json
    foreach ($json_file["allrecipes"] as $recipes => $recipie) {

        // Make insert query on cookingRecies table
        $main_query = $db->prepare("INSERT INTO processRecipes(name, cost, price, rare_price, recipe, canProc) VALUES(?, ?, ?, ?, ?, ?)");

        // BInd recipie name
        $main_query->bindValue(1, $recipie["name"]);

        $cost = 0;

        // if has a recipie to do calculate the cost
        if (sizeof($recipie["recipe"]) > 1) {

            // For each ingredient in recipe do
            foreach ($recipie["recipe"] as $subrecipes => $subrecipe) {
                // Subrecipes query
                $subrecipes_query = $db->prepare('SELECT name, price FROM prices WHERE name = ?');
                // Get the name of ingredient use on key
                $subrecipes_query->bindValue(1, $subrecipe["name"]);
                // Execute query
                $result = $subrecipes_query->execute();
                // Return name and price of recipie
                $subrecipe_value = $result->fetchArray(SQLITE3_ASSOC);

                // Total cost
                $cost += $subrecipe_value["price"] * $subrecipe["qtd"];

                // Clear memory
                $result->finalize();
            }

            // Get current recipie price
            $recipe_price = $db->prepare("SELECT price FROM prices WHERE name = ?");
            $recipe_price->bindValue(1, $recipie["name"]);
            $rp_result = $recipe_price->execute();
            $price = $rp_result->fetchArray(SQLITE3_ASSOC);

            // Get current recipie rare price
            $rare_recipe_price = $db->prepare("SELECT price FROM prices WHERE name = ?");
            $rare_recipe_price->bindValue(1, $recipie["result"][1]["rare"]);
            $rare_rp_result = $rare_recipe_price->execute();
            $rare_price = $rare_rp_result->fetchArray(SQLITE3_ASSOC);

            // Custo de uma receita
            $main_query->bindValue(2, $cost);
            // Preço de mercado
            $main_query->bindValue(3, $price["price"]);
            // Preço do prato raro no mercado
            $main_query->bindValue(4, $rare_price["price"]);
            // Serialized recipe
            $main_query->bindValue(5, serialize($recipie["recipe"]));
            // Recipe can proc?
            $main_query->bindValue(6, $recipie["canProc"]);

            $main_query->execute();
        }
    }
    // CLose connection
    $db->close();
}

buildProcessRecipes();
