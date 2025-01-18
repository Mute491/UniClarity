<?php
/*
    "fileUrl": "https://prova...",
    "acquistiId": number ,
    "svgData":{

        "svgN0": "svg..",
        "svgN1": "svg..",
        ...

    }

    nei record acquisti in svg data il valore nullo è {}

*/

/*

    Features da aggiungere:
        sezione per commenti sulla pagina

*/

$_POST["fileUrl"] = "https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf";
$_POST["acquistiId"] = 11;
$_POST["svgData"] = "{}";
$_POST["mode"] = "read";
if (
    isset($_POST["fileUrl"]) && $_POST["fileUrl"] != "" &&
    isset($_POST["acquistiId"]) && $_POST["acquistiId"] != null &&
    isset($_POST["mode"]) && ($_POST["mode"] == "study" || $_POST["mode"] == "read")
) {

    $fileUrl = $_POST["fileUrl"];
    $acquistiId = $_POST["acquistiId"];
    $mode = $_POST["mode"];

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

    <?php 
    
        if($mode == "read"){

            include("./Php/readMode.php");

        }
        else{

            include("./Php/studyMode.php");

        }

    ?>

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
                    <button id="multipage-button"><i id="multipage-button-icon" class="fa-solid fa-file fa-xl"></i></button>

                </div>

                <div>

                    <p id="output-label"></p>

                </div>

            </div>

        </section>

    </body>

</html>