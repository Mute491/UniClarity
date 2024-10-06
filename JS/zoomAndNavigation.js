function zoom(scaleChange, isZoomIn) {

    let drawSvg = $("#draw-svg-div").children();

    if (isZoomIn) {
        scalePage += scaleChange;
    } else {
        scalePage -= scaleChange;
    }

    renderPage(pageNum, document.getElementById("pdf-canvas"));

    // Scala anche il livello SVG per mantenerlo sincronizzato con il PDF
    
    drawSvg.each(function () {
        this.width = $("#canvas-div").width();
        this.height = $("#canvas-div").height();
    });
}



$(document).ready(function () {

    // Navigazione pagine
    $("#prev-page").click(function () {
        if (pageNum > 1) {
            pageNum--;
            renderPage(pageNum, document.getElementById("pdf-canvas"));
        }
    });

    $("#next-page").click(function () {
        if (pageNum < pdfDoc.numPages) {
            pageNum++;
            renderPage(pageNum, document.getElementById("pdf-canvas"));
        }
    });

    // Zoom In/Out
    $("#zoomin").click(function () {

        if(scalePage < 3){
            zoom(0.1, true);
        }
        console.log("zoom in: "+scalePage);
        
    });

    $("#zoomout").click(function () {
        if(scalePage > 0.7){
            zoom(0.1, false);
        }
        console.log("zoom out: "+scalePage);

    });

});
