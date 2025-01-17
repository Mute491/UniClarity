<?php
/*
    "fileUrl": "https://prova...",
    "acquistiId": number ,
    "svgData":{

        "svgN0": "svg..",
        "svgN1": "svg..",
        ...

    },
    "mode": "read" (o study)

*/

/*

    Features da aggiungere:
        sezione per commenti sulla pagina

*/

if (
    isset($_POST["fileUrl"]) && $_POST["fileUrl"] != "" &&
    isset($_POST["acquistiId"]) && $_POST["acquistiId"] != null
) {

    $fileUrl = $_POST["fileUrl"];
    $acquistiId = $_POST["acquistiId"];

} else {

    header("Location: accessNegated.html");

    // Termina l'esecuzione dello script
    exit();

}


if (!($_POST["svgData"] == "{}")) {

    $svgData = $_POST["svgData"];
    // Decodifica il JSON in un array associativo
    $svgArray = json_decode($svgData, true);

}

$mode = $_POST["readMode"];


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizza PDF</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Css/stylePdfViewer.css">
    <link rel="stylesheet" herf="Css/styleLoadingPopUp.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="JS/PdfRender.js"></script>
    <script src="JS/drawing.js"></script>
    <script src="JS/zoomAndNavigation.js"></script>

    <script type="module">

        import { PdfRender } from './JS/PdfRender.js';

        async function loadContent() {

            <?php echo ('let url = "' . base64_encode($fileUrl) . '";'); ?>

            let vp = null;
            let pageNumber = 1;

            let canvas = document.getElementById("pdf-canvas");

            var pdfRender = new PdfRender(atob(url), 0.7, canvas);

            let firstSvg;
            
            let svgDictionary;
            
            await pdfRender.getPdfInfo();

            svgDictionary = generateSvgDictionary(pdfRender.pageMaxNumber());

            <?php

            if ($_POST["svgData"] != "{}" && $mode != "read") {

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

            caricaEventi();

            //------------ EVENTI -------------

            $("#prev-page").click(async function () {

                let jqueryElement;

                if (pageNumber > 1) {

                    pageNumber--;

                    jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                    await changePage(pageNumber, jqueryElement, pdfRender);

                    $('#page-num').val(pageNumber);

                }
            });

            $("#next-page").click(async function () {

                let jqueryElement;

                if (pageNumber < pdfRender.pageMaxNumber) {

                    pageNumber++;

                    jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                    await changePage(pageNumber, jqueryElement, pdfRender);

                    $('#page-num').val(pageNumber);

                }
            });

            // Zoom In/Out
            $("#zoom-in").click(async function () {

                if (pdfRender.scale < 1.7) {

                    await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), true);

                }

                console.log("zoom in: " + pdfRender.scale);

            });

            $("#zoom-out").click(async function () {

                if (pdfRender.scale > 0.7) {

                    await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), false);

                }
                console.log("zoom out: " + pdfRender.scale);

            });

            $("#save-button").click(function () {

                <?php echo ("saveSvg(" . $acquistiId . ", svgDictionary);"); ?>

            });

            $('#page-num').change(async function (){

                let jqueryElement;

                let value = $('#page-num').val();

                if(value <= pdfRender.pageMaxNumber && value > 0){

                    pageNumber = parseInt(value);

                    jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                    await changePage(pageNumber, jqueryElement, pdfRender);

                }
                else{

                    $("#output-label").text("Pagina inesistente");
                    $("#output-label").css("color", "red");

                }

            });

        }

        $(window).on("load", async function (){

            $("#body-div").hide();

            // Simula il caricamento e poi nasconde il loader
            setTimeout(function () {
                document.querySelector('.loader-container').style.display = 'none';
                document.querySelector('.content').style.display = 'block';
            }, 3000); // 3 secondi di attesa per il caricamento

            await loadContent();

            $("#loading-screen-section").hide();
            $("#body-div").show();

        });

    </script>


</head>

<body>
    
    <section id="loading-screen-section">

        <div id="loading-screen">

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

                    <button id="zoom-in"><i class="fa-solid fa-magnifying-glass-plus fa-xl"></i></button>
                    <button id="zoom-out"><i class="fa-solid fa-magnifying-glass-minus fa-xl"></i></button>

                </div>
                <div class="navgation-buttons">

                    <button id="prev-page"><i class="fa-solid fa-arrow-left fa-xl"></i></button>
                    <button id="next-page"><i class="fa-solid fa-arrow-right fa-xl"></i></button>

                </div>

                <div class="page-label">

                    <p>Pagina: <input type="number" id="page-num"></p>

                </div>

                <div>

                    <button id="save-button"><i class="fa-solid fa-floppy-disk fa-xl"></i></button>

                </div>

                <div>

                    <p id="output-label"></p>

                </div>

            </div>

        </section>

    </body>

</html>