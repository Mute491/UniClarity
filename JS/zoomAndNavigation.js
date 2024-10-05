function zoom(scaleChange, isZoomIn) {
    scalePage += (isZoomIn ? scaleChange : -scaleChange);
    renderPage(pageNum, document.getElementById("pdf-canvas"));

    $(".draw-svg").css("transform", "scale(" + scalePage + ")");

    let drawCanvas = $("#draw-svg-div").children();
    drawCanvas.each(function () {
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
        zoom(0.1, true);
    });

    $("#zoomout").click(function () {
        zoom(0.1, false);
    });

});
