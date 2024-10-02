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

        $(document).ready(function () {

            //canvas per gestire pdf

            var url = 'https://proton-uploads-production.s3.amazonaws.com/56a8acb445e721195ba43fc9351f678be514e1fdda497333057a7dc755e07404.pdf'; // Sostituisci con il percorso del PDF

            var pdfDoc = null,
                pageNum = 1,
                canvas = document.getElementById("pdf-canvas"),
                ctx = canvas.getContext('2d');
                scalePage = 1;

            var drawCanvas = document.getElementById("draw-canvas");
            var ctx = canvas.getContext("2d");

            var drawing = false;

            var lastX = 0, lastY = 0;


            // Carica il PDF
            pdfjsLib.getDocument(url).promise.then(function (pdfDoc_) {

                pdfDoc = pdfDoc_;
                renderPage(pageNum); // Renderizza la prima pagina

            });

            // Funzione per renderizzare una pagina
            function renderPage(num) {

                pdfDoc.getPage(num).then(function (page) {

                    viewport = page.getViewport({ scale: scalePage });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    drawCanvas.height = viewport.height;
                    drawCanvas.width = viewport.width;

                    let renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };

                    page.render(renderContext);
                    $('#page-num').text(num);

                });

            }

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

            //Canvas per disegnare

            $("#draw-canvas").mousedown(function (e) {

                drawing = true;
                lastx = e.offsetX;
                lastY = e.offsetY;

            });

            $("#draw-canvas").mousemove(function (e) {

                if (!drawing){
                    
                    lastX = e.offsetX;  // Aggiorna la posizione del mouse
                    lastY = e.offsetY;
                    
                    return;
                }

                ctx.strokeStyle = $("#segment-color").val();
                ctx.lineWidth = $("#segment-width").val()/50;

                ctx.beginPath();
                ctx.moveTo(lastX, lastY);  // Inizia da dove il mouse si trovava l'ultima volta
                ctx.lineTo(e.offsetX, e.offsetY);  // Traccia una linea fino alla nuova posizione del mouse
                ctx.stroke();

                lastX = e.offsetX;  // Aggiorna la posizione del mouse
                lastY = e.offsetY;

            });

            $("#draw-canvas").mouseup(function (e) {

                drawing = false;

            });


            $("#draw-canvas").mouseleave(function () {

                drawing = false;

            });



        });

    </script>

</head>

<body>

    <button id="prev-page">Precedente</button>
    <button id="next-page">Successiva</button>
    <p>Pagina: <span id="page-num"></span></p>
    
    <input type="color" id="segment-color">
    <input type="range" id="segment-width">

    <div>

        <canvas id="pdf-canvas"></canvas>
        <canvas id="draw-canvas"></canvas>

    </div>



</body>

</html>