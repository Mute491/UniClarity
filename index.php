<?php

    if( 
        isset($_POST["fileUrl"])&& 
        isset($_POST["fileName"])&&
        isset($_POST["fileId"])
    ){

        $fileUrl = $_POST["fileUrl"];
        $fileId = $_POST["fileId"];
        $fileName = $_POST["fileName"];

    }
    else{

        //fai il redirect indietro per non far accedere

    }

    if(isset($_POST["svgData"])){

        $svgData = json_decode($_POST['svgData'], true);
        //in base a questo stampi già gli svg disegnati

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

        $(document).ready(async function () {

            <?php echo('let url = "'.$fileUrl.'";'); ?>

            //let url = 'https://proton-uploads-production.s3.amazonaws.com/56a8acb445e721195ba43fc9351f678be514e1fdda497333057a7dc755e07404.pdf';
            let vp = null;
            let pageNumber = 1;

            let canvas = document.getElementById("pdf-canvas");

            var pdfRender = new PdfRender(url, 0.7, canvas);

            let drawSvg;


            await pdfRender.renderPage(pageNumber);

            vp = pdfRender.viewport;

            <?php
                    
                if(!isset($_POST["svgData"])){

                    //se non è settato genera le canvas nuove
                    echo("generateSvg(pdfRender.pageMaxNumber);");

                }
                else{

                    $inputParameter = "";
                    if (is_array($svgData)) {

                        $len = count($svgData)-1;

                        foreach ($svgData as $index => $svgValue) {

                            $inputParameter .= "'".$svgValue."'";

                            if($index < $len){

                                $inputParameter .= ", ";

                            }
                            
                        }

                    }

                    echo("printSvg(".$inputParameter.")");

                }
                    
            ?>

            drawSvg = $("#draw-svg-div").children();

            $("#canvas-div").css({
                "height": vp.height + "px",
                "width": vp.width + "px"
            });

            $("#pdf-canvas-div").css({
                "height": vp.height + "px",
                "width": vp.width + "px"
            });

            //------------ EVENTI -------------

            $('#page-num').text(pageNumber);

            drawSvg[pageNumber - 1].style.display = "block";

            // Aggiorna la dimensione dell'SVG per corrispondere alla pagina PDF
            drawSvg.each(function () {
                this.setAttribute('width', vp.width);
                this.setAttribute('height', vp.height);
            });



            $("#prev-page").click(async function () {
                if (pageNumber > 1) {
                    pageNumber--;

                    $(".draw-svg").eq(pageNumber - 1).css("display", "block");
                    $(".draw-svg").eq(pageNumber).css("display", "none");

                    await pdfRender.renderPage(pageNumber);

                    updateSizes(pdfRender);

                    $('#page-num').text(pageNumber);
                }
            });

            $("#next-page").click( async function () {
                if (pageNumber < pdfRender.pageMaxNumber) {
                    pageNumber++;

                    $(".draw-svg").eq(pageNumber - 1).css("display", "block");
                    $(".draw-svg").eq(pageNumber - 2).css("display", "none");

                    await pdfRender.renderPage(pageNumber);

                    updateSizes(pdfRender);

                    $('#page-num').text(pageNumber);

                }
            });

            // Zoom In/Out
            $("#zoomin").click(function () {

                if (pdfRender.scale < 3) {
                    zoom(pdfRender, pageNumber, 0.1, true);

                }

                console.log("zoom in: " + pdfRender.scale);

            });

            $("#zoomout").click(function () {
                if (pdfRender.scale > 0.7) {
                    zoom(pdfRender, pageNumber, 0.1, false);

                }
                console.log("zoom out: " + pdfRender.scale);

            });

            $("#saveButton").click(function () {

                <?php echo("saveSvg(".$fileId.");"); ?>

            });

        });

    </script>


</head>

<body>

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

                </select>

                <div class="zoom-in-out">

                    <button id="zoomin"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                    <button id="zoomout"><i class="fa-solid fa-magnifying-glass-minus"></i></button>

                </div>
                <div class="navgation-buttons">

                    <button id="prev-page"><i class="fa-solid fa-arrow-left"></i></button>
                    <button id="next-page"><i class="fa-solid fa-arrow-right"></i></button>

                </div>

                <div class="page-label">

                    <p>Pagina: <span id="page-num"></span></p>

                </div>

                <div>

                    <button id="saveButton"><i class="fa-solid fa-floppy-disk"></i></button>

                </div>

            </div>

        </section>
</body>

</html>