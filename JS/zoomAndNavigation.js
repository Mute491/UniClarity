
function zoom(scaleChange, isZoomIn) {

    let drawSvg = $("#draw-svg-div").children();

    if (isZoomIn) {
        pdfRender.scale += scaleChange;
    } else {
        scalePage.scale -= scaleChange;
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
