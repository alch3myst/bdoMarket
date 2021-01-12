<?php

$file = `
const fetch = require("node-fetch");
const timer = (ms) => new Promise((res) => setTimeout(res, ms));
const fs = require("fs");

let categoryes = [
  // Materials
  ["25", "1", "Minerio_Gema"],
  ["25", "2", "Plantas"],
  ["25", "3", "Semente_Fruto"],
  ["25", "4", "Couro"],
  ["25", "5", "Sangue"],
  ["25", "6", "Carne"],
  ["25", "7", "Frutos_do_Mar"],
  ["25", "8", "Variado"],
  // Aprimoramento
  ["30", "1", "Pedra_Negra"],
  ["30", "2", "Melhorar"],
  // Consumiveis
  ["35", "1", "Elixir_Ofencivo"],
  ["35", "2", "Elixir_Defensivo"],
  ["35", "3", "Elixir_Funcional"],
  ["35", "4", "Culinaria"],
  ["35", "5", "Pocao"],
  ["35", "8", "Outros_Consumiveis"],
  // Animal
  ["65", "2", "Alimentacao"],
  // Cristal
  ["50", "1", "Arma_Principal"],
  ["50", "2", "Arma_Secundaria"],
  ["50", "3", "Arma_Despertada"],
  ["50", "4", "Elmo"],
  ["50", "5", "Armadura"],
  ["50", "6", "Luvas"],
  ["50", "7", "Sapatos"],
  ["50", "8", "Versatil"],
  // // Navio
  // ["70", "2", "Carga"],
  // ["70", "3", "Proa"],
  // ["70", "4", "Decoração"],
  // ["70", "5", "Totem"],
  // ["70", "6", "Estátua de Proa"],
  // ["70", "7", "Luvas"],
  // ["70", "8", "Canhão"],
  // ["70", "9", "Vela"]
];

categoryes.forEach((c) => {
  // Start forEach
  fetch("https://blackdesert-tradeweb.playredfox.com/Home/GetWorldMarketList", {
    headers: {
      "User-Agent":
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36",
      accept: "*/*",
      "accept-language": "en-US,en;q=0.9,pt;q=0.8",
      "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
      "sec-fetch-dest": "empty",
      "sec-fetch-mode": "cors",
      "sec-fetch-site": "same-origin",
      "x-requested-with": "XMLHttpRequest",
      "cookie": "__RequestVerificationToken=AQskKoaVEwpT8ukAGzYRcWuyi5K1rdFIRc3A6nhfvaazcOnvG76PbPE39BnGhX-GfqmoehELLPIMtdy4NlGG8GapeGlkihoT2TZ7DJ50wlI1; tradeHistory=; rfuid=bdd194c59d556e519b6b18f10121a2c3; _redfox-web_session=UTB0TmRRWTJvYXRaMUF0dHNRMmp2ZThFTEROdUhtQ1Y2Y0pxS0JsMHdYK1I3QmlkWVYvVXZ4STZoYVovSDVycnJCbGZHSGhrUy9rbGVRcFp5cGJOQ0Mwa2poTjlJUWloN3VldmVGTnlacERpbm1iNHJZcDJtWEl5a0JmNklQdUdnR1dwcXF3bVMrblMwZVdEbzNVQW9jN2IrQzVZdWJuNENxdFFPSURGUTNGWC9wTkJRRVZkRmVjQjVkYk93TUlZd1h0bWg2OGFxVTViQnFETFFFaDZwRnd4bDJDVEZsclBCSVMzZnN4bldWZjAyMThRcEhLMWpLSk5YVWhRQlBmZDl5QzNEOEh2VXYrYlRCaVozZE5MY3Nhbmp3enk1Qk9sWEh0YXBvNnpPVVRMTlBJVnRMU04xc1h4a09lcGhQUHJKL091QXJ5Q1lPWklYZkFrRGRYdE9Nc3Yva0kvMTRnUUpXMDk2ZkdwaFprPS0tdHBpR2ZBUVltQWpQajJpcmdNbHJSZz09--3eb19a39b517db6517ef5fc0f6f4076349b2c837; ASP.NET_SessionId=4agmvy24ht2zbzol2iw3tqd0"
    },
    referrer: "https://blackdesert-tradeweb.playredfox.com/Home/list/25-2",
    referrerPolicy: "strict-origin-when-cross-origin",
    body: "__RequestVerificationToken=n2TP3MHhj_X5wU1ZPjqqLHsOOvcXLOcoWeODcWW5xtUeGkIE0AkrbOuuU711bVsZY6Jwf-UfO8AlXCouwl6SSON-xQz_ibI69bjrTtEM7N01&mainCategory=" +
      c[0] +
      "&subCategory=" +
      c[1],
    method: "POST",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((json) => {
      try {
        let file = fs.writeFileSync(
          "../../src/MarketData/" + c[2] + ".json",
          JSON.stringify(json, null, 4)
        );
      } catch (error) {
        console.log(error);
      }
    });

  timer(1500);
  // End forEach
});
`;