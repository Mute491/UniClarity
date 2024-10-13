
async function zoom(pdfObject, pageNo, scaleChange, isZoomIn) {

    if (isZoomIn) {
        pdfObject.scale += scaleChange;
    } else {
        pdfObject.scale -= scaleChange;
    }

    await pdfObject.renderPage(pageNo); 
    // Scala anche il livello SVG per mantenerlo sincronizzato con il PDF
    
    updateSizes(pdfObject);

}

function updateSizes(pdfObject){

    let vp = pdfObject.viewport;

    let drawSvg = $("#draw-svg-div").children();

    $("#canvas-div").css({
        "height": vp.height + "px",
        "width": vp.width + "px"
    });

    $("#draw-svg-div").css({
        "height": vp.height + "px",
        "width": vp.width + "px"
    });

    drawSvg.each(function () {
        this.width = $("#canvas-div").width();
        this.height = $("#canvas-div").height();
    });

    console.log("fatto!");

}
