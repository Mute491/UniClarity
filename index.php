<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza PDF</title>
    <!-- Includi PDF.js da un CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="Css/style.css">
    <script src="JS/pdfRendering.js"></script>
    <script src="JS/drawing.js"></script>
    <script src="JS/zoomAndNavigation.js"></script>

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