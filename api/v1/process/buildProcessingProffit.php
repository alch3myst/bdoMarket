<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function buildProcessProffit()
{
    // "Connect to db"
    $db = new SQLite3("../../src/db/bdomarket.sqlite");

    $db->query("DROP TABLE processProffit");
    $db->query("CREATE TABLE IF NOT EXISTS processProffit (
        id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        name TEXT,
        marketPrice INTEGER,
        rareProcMarketPrice INTEGER,
        recipe TEXT,
        cost INTEGER,
        proffitWPE INTEGER,
        proffitWTPE INTEGER
    )");

    try {
        // Get all recipes
        $recipesQuery = $db->query("SELECT * FROM processRecipes");

        // Market price query
        $getPriceQuery = $db->prepare("SELECT name, price FROM prices WHERE name = ?");

        // For each recipe found do
        while ($recipesQueryResult = $recipesQuery->fetchArray(SQLITE3_ASSOC)) {

            // Final optmized recipe cost
            $finalCost = 0;

            // Recipe array
            $recipeArray = array();

            // For each ingredient check if has a recipe to do
            $ingredients = unserialize($recipesQueryResult["recipe"]);
            foreach ($ingredients as $inkey => $ingredient) {

                // Try to get the recipe from processRecipes table
                $ingredientQuery = $db->prepare("SELECT * FROM processRecipes WHERE name = ?");
                // Bind recipe name on the search query
                $ingredientQuery->bindValue(1, $ingredient["name"]);
                // Run search query
                $ingredientQueryState = $ingredientQuery->execute();
                // Get results
                $ingredientResult = $ingredientQueryState->fetchArray(SQLITE3_ASSOC);


                // If has a recipe
                if ($ingredientResult != false) {
                    // check if cost is less then marketprice * 2 (Rouded proc rate)
                    if ($ingredientResult["cost"] < $ingredientResult["price"]) {

                        // Accumulate final total cost
                        $finalCost += $ingredientResult["cost"] * $ingredient["qtd"];

                        // Push array value
                        array_push($recipeArray, array(
                            "Make: " . $ingredientResult["name"],
                            $ingredient["qtd"],
                            $ingredientResult["cost"],
                            $ingredientResult["cost"] * $ingredient["qtd"]
                        ));
                    } else { // Other wise buy from market

                        // Accumulate final total cost
                        $finalCost += $ingredientResult["price"] * $ingredient["qtd"];

                        // Push array value
                        array_push($recipeArray, array(
                            "Buy: " . $ingredientResult["name"],
                            $ingredient["qtd"],
                            $ingredientResult["price"],
                            $ingredientResult["price"] * $ingredient["qtd"]
                        ));
                    }
                } else { // Get price from market
                    // BInd ingredient value to querry
                    $getPriceQuery->bindValue(1, $ingredient["name"]);
                    $getPriceState = $getPriceQuery->execute();
                    $getPrice = $getPriceState->fetchArray(SQLITE3_ASSOC);

                    // Accumulate final total cost
                    $finalCost += $getPrice["price"] * $ingredient["qtd"];

                    // Push array value
                    array_push($recipeArray, array(
                        "Buy: " . $getPrice["name"],
                        $ingredient["qtd"],
                        $getPrice["price"],
                        $getPrice["price"] * $ingredient["qtd"]
                    ));
                }
            } // End ingredient foreach

            $insertRecipeProffit = $db->prepare("INSERT INTO processProffit (name, marketPrice, rareProcMarketPrice, recipe, cost, proffitWPE, proffitWTPE) values(?, ?, ?, ?, ?, ?, ?)");
            // Recipe name
            $insertRecipeProffit->bindValue(1, $recipesQueryResult["name"]);
            // Recipe market price
            $insertRecipeProffit->bindValue(2, $recipesQueryResult["price"]);
            // Rare proc market price
            $insertRecipeProffit->bindValue(3, $recipesQueryResult["rare_price"]);
            // Serialized recipe (Buy or make: Ingredient name, quantity, price, final makecost | final cost | final market price)
            $insertRecipeProffit->bindValue(4, json_encode($recipeArray, JSON_UNESCAPED_UNICODE));
            // Final cost
            $insertRecipeProffit->bindValue(5, $finalCost);
            // proffit with value pack
            $insertRecipeProffit->bindValue(6, calc_proffit_cpe($finalCost, $recipesQueryResult["price"], $recipesQueryResult["canProc"] ));
            // "Profit" without value pack
            $insertRecipeProffit->bindValue(7, calc_proffit_spe($finalCost, $recipesQueryResult["price"], $recipesQueryResult["canProc"] ));

            $insertRecipeProffit->execute();
        } // End recipe while
    } catch (\Throwable $th) {
        echo $th;
    }

    // CLose connection
    $db->close();
}

// Calculate proffit with value pack
function calc_proffit_cpe($cost, $price, $canProc)
{
    if ($canProc != "false") {
        $profit = (($price * 2) * 0.845) - $cost;
    } else {
        $profit = ($price * 0.845) - $cost;
    }
    return $profit;
}

// Calculate proffit without value pack
function calc_proffit_spe($cost, $price, $canProc)
{
    if ($canProc != "false") {
        $profit = (($price * 2) * 0.65) - $cost;
    } else {
        $profit = ($price * 0.65) - $cost;
    }
    return $profit;
}

buildProcessProffit();
