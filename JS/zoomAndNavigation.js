
function zoom(scaleChange, isZoomIn) {

    let drawSvg = $("#draw-svg-div").children();

    if (isZoomIn) {
        scalePage += scaleChange;
    } else {
        scalePage -= scaleChange;
    }

    pdfRender.renderPage(pageNumber);

    // Scala anche il livello SVG per mantenerlo sincronizzato con il PDF
    
    drawSvg.each(function () {
        this.width = $("#canvas-div").width();
        this.height = $("#canvas-div").height();
    });
}

function updateSize(div, vp){

    $(div).css({
        "height": vp.height + "px",
        "width": vp.width + "px"
    });

}


$(document).ready(function () {

    // Navigazione pagine
    $("#prev-page").click(function () {
        if (pageNumber > 1) {
            pageNumber--;
            pdfRender.renderPage(pageNumber);
        }
    });

    $("#next-page").click(function () {
        if (pageNumber < pdfDoc.numPages) {
            pageNumber++;
            pdfRender.renderPage(pageNumber);
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
