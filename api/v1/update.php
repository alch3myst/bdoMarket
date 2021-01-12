<?php

error_reporting(E_ERROR);

// Download market prices
shell_exec("node ../download_market.js");
require_once "./update/fixDuplicates.php";

// Build market prices db
require_once "./buildPrices.php";

// Cooking
require_once "./cooking/buildCookingRecipes.php";
require_once "./cooking/buildCookingProffit.php";

// Cooking
require_once "./alchemy/buildAlchemyRecipes.php";
require_once "./alchemy/buildAlchemyProffit.php";

// Process
require_once "./process/buildProcessingRecipes.php";
require_once "./process/buildProcessingProffit.php";

echo "{'status': 'Update done'}";
echo "<script>window.close();</script>";