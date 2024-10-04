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
        var scalePage = 0.7;
        var canvasScale = 1;

        var drawing = false;
        var lastX = 0, lastY = 0;

        function caricaEventi() {

            //Canvas per disegnare
            $("#draw-svg-div").children().on("mousedown", function (e) {
                drawing = true;
                lastx = e.offsetX;
                lastY = e.offsetY;

            });

            $("#draw-svg-div").children().on("mousemove", function (e) {

                if (!drawing){
                    
                    lastX = e.offsetX;
                    lastY = e.offsetY;    
                    
                    return;
                }

                let line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', lastX);
                line.setAttribute('y1', lastY);
                line.setAttribute('x2', e.offsetX);
                line.setAttribute('y2', e.offsetY);
                line.setAttribute('stroke', $("#segment-color").val());
                line.setAttribute('stroke-width', $("#segment-width").val() / 10);
                line.setAttribute('stroke-linecap', 'round'); // Imposta il cappello della linea a tondo

                this.appendChild(line);

                lastX = e.offsetX;
                lastY = e.offsetY;
            });

            $("#draw-svg-div").children().on("mouseup", function (e) {

                drawing = false;

            });


            $("#draw-svg-div").children().on("mouseleave", function () {

                drawing = false;

            });

        }

        function generateCanvas(num) {

            for (let i = 0; i < num; i++) {

                $("#draw-svg-div").append($("<svg class='draw-svg'></svg>"));

            }

            let drawCanvas = $("#draw-svg-div").children();

            drawCanvas.each(function () {

                this.width = $("#canvas-div").width();  // Occupa tutto canvas-div
                this.height = $("#canvas-div").height();  // Occupa tutto canvas-div

            });

        }

        function renderPage(num, pdfCanvas) {

            var ctx = pdfCanvas.getContext('2d');

            pdfDoc.getPage(num).then(function (page) {

                let drawCanvas = $("#draw-svg-div").children();

                viewport = page.getViewport({ scale: scalePage });

                pdfCanvas.height = viewport.height;
                pdfCanvas.width = viewport.width;

                // Centra il pdf-canvas nel canvas-div
                $("#pdf-canvas-div").css({
                    "height": viewport.height + "px",
                    "width": viewport.width + "px"
                });


                let renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext);
                $('#page-num').text(num);

                if (num > 1) {

                    drawCanvas[num - 2].style.display = "none";

                }
                else if (num < pdfDoc.numPages) {

                    drawCanvas[num].style.display = "none";

                }
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
                    renderPage(pageNum, canvas);
                }

            });

            $("#next-page").click(function () {

                if (pageNum < pdfDoc.numPages) {
                    pageNum++;
                    renderPage(pageNum, canvas);
                }

            });

            $("#zoomin").click(function () {

                scalePage += 0.2;
                canvasScale += 0.2;
                renderPage(pageNum, canvas);

            });

            $("#zoomout").click(function () {

                scalePage -= 0.2;
                canvasScale -= 0.2;
                console.log(canvasScale);
                renderPage(pageNum, canvas);

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
            <input type="range" min="1" max="100" id="segment-width">
            <select id="tool-selector">
                <option value="1">Penna</option>
                <option value="2">Evidenziatore</option>
            </select>

            <input type="button" id="zoomin" value="Zoom in">
            <input type="button" id="zoomout" value="Zoom out">

        </div>


        <div id="canvas-div">

            <div id="pdf-canvas-div">

                <canvas id="pdf-canvas"></canvas>

            </div>
            <div id="draw-svg-div">

            </div>


        </div>
    </div>


</body>

</html>