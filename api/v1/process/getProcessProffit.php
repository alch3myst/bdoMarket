<?php

function getCookingProffit()
{
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    // "Connect to db"
    $db = new SQLite3("../../../src/db/bdomarket.sqlite");

    // Get all recipes
    $recipesQuery = $db->query("SELECT * FROM processProffit ORDER BY proffitWPE DESC");

    $out = array();

    // For each recipe found do
    while ($recipesQueryResult = $recipesQuery->fetchArray(SQLITE3_ASSOC)) {
        array_push($out, $recipesQueryResult);
    }

    echo json_encode($out);
}

getCookingProffit();