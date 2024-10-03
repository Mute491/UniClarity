<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza PDF</title>
    <!-- Includi PDF.js da un CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="style.css">

    <script>

        //aggiungere grandezza canvas
        //problema con l'offset durante il disegno

        var url = 'https://proton-uploads-production.s3.amazonaws.com/56a8acb445e721195ba43fc9351f678be514e1fdda497333057a7dc755e07404.pdf'; // Sostituisci con il percorso del PDF

        var pdfDoc = null;
        var pageNum = 1;
        var scalePage = 1;

        var drawing = false;
        var lastX = 0, lastY = 0;

        function caricaEventi(){

            console.log($("#draw-canvas-div").children());
            //Canvas per disegnare
            $("#draw-canvas-div").children().on("mousedown", function (e) {
                drawing = true;
                lastx = e.offsetX;
                lastY = e.offsetY;

            });

            $("#draw-canvas-div").children().on("mousemove", function (e) {

                let currentChild = $(this);
                let ctx = currentChild.get(0).getContext("2d");

                if (!drawing) {

                    lastX = e.offsetX;  // Aggiorna la posizione del mouse
                    lastY = e.offsetY;

                    return;
                }

                ctx.strokeStyle = $("#segment-color").val();
                ctx.lineWidth = $("#segment-width").val() / 50;

                ctx.beginPath();
                ctx.moveTo(lastX, lastY);  // Inizia da dove il mouse si trovava l'ultima volta
                ctx.lineTo(e.offsetX, e.offsetY);  // Traccia una linea fino alla nuova posizione del mouse
                ctx.stroke();

                lastX = e.offsetX;  // Aggiorna la posizione del mouse
                lastY = e.offsetY;

            });

            $("#draw-canvas-div").children().on("mouseup", function (e) {

                drawing = false;

            });


            $("#draw-canvas-div").children().on("mouseleave", function () {

                drawing = false;

            });

        }

        function generateCanvas(num){

            for(let i=0; i<num; i++){

                $("#draw-canvas-div").append($("<canvas class='draw-canvas'></canvas>"));

            }

        }

        function renderPage(num, pdfCanvas) {

            var ctx = pdfCanvas.getContext('2d');

            pdfDoc.getPage(num).then(function (page) {

                let drawCanvas = $("#draw-canvas-div").children();

                viewport = page.getViewport({ scale: scalePage });

                pdfCanvas.height = viewport.height;
                pdfCanvas.width = viewport.width;

                $("#canvas-div").css("height", viewport.height);

                let renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext);
                $('#page-num').text(num);

                drawCanvas[num - 1].style.display = "block";

            });

        }

        $(document).ready(function () {

            //canvas per gestire pdf
            var canvas = document.getElementById("pdf-canvas");

            // Carica il PDF
            pdfjsLib.getDocument(url).promise.then(function (pdfDoc_) {

                pdfDoc = pdfDoc_;
                renderPage(pageNum, canvas); // Renderizza la prima pagina
                generateCanvas(pdfDoc.numPages);
                caricaEventi();
            });

            // Pulsante pagina precedente

            $("#prev-page").click(function () {

                if (pageNum > 1) {
                    pageNum--;
                    renderPage(pageNum);
                }

            });

            $("#next-page").click(function () {

                if (pageNum < pdfDoc.numPages) {
                    pageNum++;
                    renderPage(pageNum);
                }

            });

        });

    </script>

</head>

<body>

    <div id="body-div">
        <div>

            <button id="prev-page">Precedente</button>
            <button id="next-page">Successiva</button>
            <p>Pagina: <span id="page-num"></span></p>

            <input type="color" id="segment-color">
            <input type="range" id="segment-width">

        </div>


        <div id="canvas-div">

            <div id="pdf-canvas-div">

                <canvas id="pdf-canvas"></canvas>

            </div>
            <div id="draw-canvas-div">

            </div>


        </div>
    </div>


</body>

</html>