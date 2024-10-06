var pdfDoc = null;
var pageNum = 1;
var scalePage = 0.7;

function renderPage(num, pdfCanvas) {
    var ctx = pdfCanvas.getContext('2d');

    pdfDoc.getPage(num).then(function (page) {

        let drawCanvas = $("#draw-svg-div").children();
        
        viewport = page.getViewport({ scale: scalePage });

        pdfCanvas.height = viewport.height;
        pdfCanvas.width = viewport.width;

        // Centra e sincronizza la dimensione del canvas-div con il pdf-canvas
        $("#canvas-div").css({
            "height": viewport.height + "px",
            "width": viewport.width + "px"
        });

        $("#pdf-canvas-div").css({
            "height": viewport.height + "px",
            "width": viewport.width + "px"
        });

        // Rendering della pagina del PDF
        var renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        page.render(renderContext);
        $('#page-num').text(num);

        // Aggiorna la visibilità del layer SVG
        if (num > 1) {
            drawCanvas[num - 2].style.display = "none";
        }
        if (num < pdfDoc.numPages) {
            drawCanvas[num].style.display = "none";
        }
        drawCanvas[num - 1].style.display = "block";

        // Aggiorna la dimensione dell'SVG per corrispondere alla pagina PDF
        drawCanvas.each(function () {
            this.setAttribute('width', viewport.width);
            this.setAttribute('height', viewport.height);
        });
    });
}


// Carica PDF
function loadPDF(url) {
    var canvas = document.getElementById("pdf-canvas");
    
    console.log("loadPdf");
    pdfjsLib.getDocument(url).promise.then(function (pdfDoc_) {
        pdfDoc = pdfDoc_;
        renderPage(pageNum, canvas);
        generateCanvas(pdfDoc.numPages);
    });
}

// Call loadPDF with the correct URL
$(document).ready(function () {
    var url = 'https://proton-uploads-production.s3.amazonaws.com/56a8acb445e721195ba43fc9351f678be514e1fdda497333057a7dc755e07404.pdf';
    loadPDF(url);
});