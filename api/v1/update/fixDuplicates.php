<?php

// Muda "Cristal Negro" do Minerio_Gema.json pra "Cristal Negro Minerio"
$file = '../../src/MarketData/Minerio_Gema.json';
file_put_contents($file, str_replace('"name": "Cristal Negro"', '"name": "Cristal Negro Minerio"', file_get_contents($file)));