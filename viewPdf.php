<?php


if (isset($_POST["fileUrl"])) {

    $fileUrl = $_POST["fileUrl"];

} else {

    header("Location: accessNegated.html");

    // Termina l'esecuzione dello script
    exit();

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
    <script src="JS/zoomAndNavigation.js"></script>

    <script type="module">

        import { PdfRender } from './JS/PdfRender.js';

        $(document).ready(async function () {

            <?php echo ('let url = "' . $fileUrl . '";'); ?>

            let vp = null;
            let pageNumber = 1;

            let canvas = document.getElementById("pdf-canvas");

            var pdfRender = new PdfRender(url, 0.7, canvas);


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

            $("#next-page").click(async function () {
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

            </div>

        </section>

        <section class="tools-and-buttons">
            <div class="drawing-tools">

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

            </div>

        </section>
</body>

</html>