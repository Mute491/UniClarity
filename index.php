<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizza PDF</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="JS/PdfRender.js"></script>
    <!-- <script src="JS/drawing.js"></script>
    <script src="JS/zoomAndNavigation.js"></script> -->

    <script type="module">

        import { PdfRender } from './JS/PdfRender.js';

        $(document).ready(function () {
            let url = 'https://proton-uploads-production.s3.amazonaws.com/56a8acb445e721195ba43fc9351f678be514e1fdda497333057a7dc755e07404.pdf';
            let vp = null;
            let pageNumber = 1;

            let canvas = document.getElementById("pdf-canvas");

            const pdfRender = new PdfRender(url, 0.7, canvas);

            pdfRender.renderPage(pageNumber);

            // vp = pdfRender.getViewport();
            
            // let drawCanvas = $("#draw-svg-div").children();

            console.log("prova");
            

            // $("#canvas-div").css({
            //     "height": vp.height + "px",
            //     "width": vp.width + "px"
            // });
    
            // $("#pdf-canvas-div").css({
            //     "height": vp.height + "px",
            //     "width": vp.width + "px"
            // });

            
            // $('#page-num').text(pageNumber);
    
            // // Aggiorna la visibilità del layer SVG
            // if (num > 1) {
            //     drawCanvas[num - 2].style.display = "none";
            // }
            // if (num < pdfDoc.numPages) {
            //     drawCanvas[num].style.display = "none";
            // }
            // drawCanvas[num - 1].style.display = "block";
    
            // // Aggiorna la dimensione dell'SVG per corrispondere alla pagina PDF
            // drawCanvas.each(function () {
            //     this.setAttribute('width', vp.width);
            //     this.setAttribute('height', vp.height);
            // });

        });

    </script>


</head>

<body>

    <div id="body-div">
        <div>
            <input type="color" id="segment-color">
            <input type="range" min="1" max="100" id="segment-width">
            <select id="tool-selector">
                <option value="1">Penna</option>
                <option value="2">Evidenziatore</option>
            </select>

            <button id="zoomin"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
            <button id="zoomout"><i class="fa-solid fa-magnifying-glass-minus"></i></button>

        </div>

        <div class="canvas-and-buttons">

            <div class="navigation">

                <div class="change-page-buttons">
                    <button id="prev-page"><i class="fa-solid fa-arrow-left"></i></button>
                    <button id="next-page"><i class="fa-solid fa-arrow-right"></i></button>
                </div>

                <div class="page-label">
                    <p>Pagina: <span id="page-num"></span></p>
                </div>

            </div>

            <div id="canvas-div">

                <div id="pdf-canvas-div">

                    <canvas id="pdf-canvas"></canvas>

                </div>
                <div id="draw-svg-div">

                </div>

            </div>
        </div>
    </div>
</body>

</html>