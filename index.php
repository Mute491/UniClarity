<?php
/*
    "fileUrl": "https://prova...",
    "acquistiId": number ,
    "svgData":{

        "0": "svg..",
        "1": "svg..",
        ...

    }

    Tutto funzionante

    Ottimizzazione:
    compressione degli svg
    salvataggio di quelli sono con dei disegni dentro
    aggiunta raccolta del webhook come parametro di input (ragioni di sicurezza)
*/

$_POST["fileUrl"] = "book.pdf";
$_POST["acquistiId"] = 11;
$_POST["svgData"] = '{"svgN3": "<svg xmlns=\"http://www.w3.org/2000/svg\" id=\"svgN0\" viewBox=\"0 0 100 100\" preserveAspectRatio=\"xMidYMid meet\" class=\"draw-svg\" style=\"width: 428.391px; height: 554.391px;\"><line x1=\"73.61125946044922\" y1=\"25.677499771118164\" x2=\"73.61125946044922\" y2=\"25.910930633544922\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"73.61125946044922\" y1=\"25.910930633544922\" x2=\"73.84469604492188\" y2=\"25.910930633544922\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"73.84469604492188\" y1=\"25.910930633544922\" x2=\"74.078125\" y2=\"25.910930633544922\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"74.078125\" y1=\"25.910930633544922\" x2=\"74.078125\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"74.078125\" y1=\"26.144363403320312\" x2=\"74.31156158447266\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"74.31156158447266\" y1=\"26.144363403320312\" x2=\"74.54499053955078\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"74.54499053955078\" y1=\"26.144363403320312\" x2=\"74.7784194946289\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"74.7784194946289\" y1=\"26.144363403320312\" x2=\"75.01185607910156\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"75.01185607910156\" y1=\"26.144363403320312\" x2=\"75.24528503417969\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/><line x1=\"75.24528503417969\" y1=\"26.144363403320312\" x2=\"75.47871398925781\" y2=\"26.144363403320312\" stroke=\"#000000\" stroke-width=\"5.1\" stroke-linecap=\"round\"/></svg>"}';
if (
    isset($_POST["fileUrl"]) &&
    isset($_POST["acquistiId"])
) {

    $fileUrl = $_POST["fileUrl"];
    $acquistiId = $_POST["acquistiId"];

} else {

    header("Location: accessNegated.html");

    // Termina l'esecuzione dello script
    exit();

}

/*  
    tutto questo viene salvato nel record su adalo
    , "svgData":{

        "content":["svg1...", ...]

    }
*/


if (!($_POST["svgData"] == "{}")) {


    $svgData = $_POST["svgData"];
    // Decodifica il JSON in un array associativo
    $svgArray = json_decode($svgData, true);

}


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizza PDF</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="JS/PdfRender.js"></script>
    <script src="JS/drawing.js"></script>
    <script src="JS/zoomAndNavigation.js"></script>

    <script type="module">

        import { PdfRender } from './JS/PdfRender.js';

        async function loadContent() {

            <?php echo ('let url = "' . $fileUrl . '";'); ?>

            let vp = null;
            let pageNumber = 1;

            let canvas = document.getElementById("pdf-canvas");

            var pdfRender = new PdfRender(url, 0.7, canvas);

            let firstSvg;
            
            let svgDictionary;
            
            
            await pdfRender.getPdfInfo();

            svgDictionary = generateSvgDictionary(pdfRender.getMaxPdfPageNumber());

            <?php

            if ($_POST["svgData"] != "{}") {

                $inputParameter = "[";
                $len = count($svgArray) - 1;

                foreach ($svgArray as $key => $svgValue) {

                    $inputParameter .= "'$svgValue'";

                    if ($key < $len) {

                        $inputParameter .= ", ";

                    }

                }
                $inputParameter .= "]";

                echo ("svgDictionary = addExistingSvg(" . $inputParameter . ", svgDictionary);");
            }

            ?>

            await pdfRender.renderPage(pageNumber);

            vp = pdfRender.viewport;

            $("#canvas-div").css({
                "height": vp.height + "px",
                "width": vp.width + "px"
            });

            $("#pdf-canvas-div").css({
                "height": vp.height + "px",
                "width": vp.width + "px"
            });


            $('#page-num').text(pageNumber);

            firstSvg = $(svgDictionary["svgN"+(pageNumber-1)]);

            // firstSvg.css({
            //     width: $("#canvas-div").width(),
            //     height: $("#canvas-div").height(),
            // });

            $("#draw-svg-div").append(firstSvg);

            caricaEventi();

            //------------ EVENTI -------------

            $("#prev-page").click(async function () {
                if (pageNumber > 1) {
                    let jqueryElement;

                    pageNumber--;

                    jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                    updateSizes(pdfRender, jqueryElement);

                    $("#draw-svg-div").empty();
                    $("#draw-svg-div").append(jqueryElement);

                    await pdfRender.renderPage(pageNumber);

                    $('#page-num').text(pageNumber);
                    caricaEventi();
                }
            });

            $("#next-page").click(async function () {
                if (pageNumber < pdfRender.pageMaxNumber) {
                    let jqueryElement;

                    pageNumber++;

                    jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                    updateSizes(pdfRender, jqueryElement);

                    $("#draw-svg-div").empty();
                    $("#draw-svg-div").append(jqueryElement);

                    await pdfRender.renderPage(pageNumber);

                    $('#page-num').text(pageNumber);
                    caricaEventi();
                }
            });

            // Zoom In/Out
            $("#zoomin").click(async function () {

                if (pdfRender.scale < 1.7) {
                    await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), true);

                }

                console.log("zoom in: " + pdfRender.scale);

            });

            $("#zoomout").click(async function () {

                if (pdfRender.scale > 0.7) {

                    let currentSvg

                    await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), false);

                }
                console.log("zoom out: " + pdfRender.scale);

            });

            $("#saveButton").click(function () {

                <?php echo ("saveSvg(" . $acquistiId . ", svgDictionary);"); ?>

            });

        }

        $(window).on("load", async function (){

            // Simula il caricamento e poi nasconde il loader
            setTimeout(function () {
                document.querySelector('.loader-container').style.display = 'none';
                document.querySelector('.content').style.display = 'block';
            }, 3000); // 3 secondi di attesa per il caricamento

            await loadContent();

            $("#loadingScreenSection").hide();

        });

    </script>


</head>

<body>
    
    <section id="loadingScreenSection">

        <div id="loadingScreen">

            <!-- Schermata di caricamento -->
            <div class="loader-container">
                <div class="logo"></div>
            </div>

            <!-- Contenuto del sito -->
            <div class="content" style="display:none;">
                <h1>Caricamento del file</h1>
                <p>Attendi...</p>
            </div>

        </div>

    </section>

    <div id="body-div">

        <section class="pdf-draw-section">

            <div id="canvas-div">

                <div id="pdf-canvas-div">

                    <canvas id="pdf-canvas"></canvas>

                </div>
                <div id="draw-svg-div">

                </div>

            </div>

        </section>

        <section class="tools-and-buttons">
            <div class="drawing-tools">

                <input type="color" id="segment-color">
                <input type="range" min="1" max="100" id="segment-width">

                <select id="tool-selector">

                    <option value="1">Penna</option>
                    <option value="2">Evidenziatore</option>
                    <option value="3">Gomma</option>

                </select>

                <div class="zoom-in-out">

                    <button id="zoomin"><i class="fa-solid fa-magnifying-glass-plus fa-xl"></i></button>
                    <button id="zoomout"><i class="fa-solid fa-magnifying-glass-minus fa-xl"></i></button>

                </div>
                <div class="navgation-buttons">

                    <button id="prev-page"><i class="fa-solid fa-arrow-left fa-xl"></i></button>
                    <button id="next-page"><i class="fa-solid fa-arrow-right fa-xl"></i></button>

                </div>

                <div class="page-label">

                    <p>Pagina: <span id="page-num"></span></p>

                </div>

                <div>

                    <button id="saveButton"><i class="fa-solid fa-floppy-disk fa-xl"></i></button>

                </div>

                <div>

                    <p id="saveOutput"></p>

                </div>

            </div>

        </section>

    </body>

</html>