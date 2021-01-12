<?php

function searchProcessProffit()
{
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    // "Connect to db"
    $db = new SQLite3("../../../src/db/bdomarket.sqlite");

    $search = filter_var($_GET["recipe"], FILTER_SANITIZE_STRING);
    // Get all recipes
    $recipesQuery = $db->prepare("SELECT * FROM processProffit WHERE name LIKE '%" . $search . "%' ORDER BY proffitWPE DESC;");
    $recipesQueryResult = $recipesQuery->execute();

    $out = array();

    // For each recipe found do
    while ($recipesQuerySearch = $recipesQueryResult->fetchArray(SQLITE3_ASSOC)) {
        array_push($out, $recipesQuerySearch);
    }

    echo json_encode($out);
}

searchProcessProffit();