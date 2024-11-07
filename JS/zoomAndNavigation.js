
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

function saveSvg(acquistiId){

    let drawSvg = $("#draw-svg-div").children();
    let svgStringList = [];

    drawSvg.each(function () {

        svgStringList.push(new XMLSerializer().serializeToString(this));

    });

    console.log(JSON.stringify({
        content: svgStringList,
    }));

    
    // $.ajax({
    //     url: '/your-server-endpoint',
    //     method: 'POST',
    //     contentType: 'application/json',
    //     data: JSON.stringify({
    //             acquistiId: acquistiId,
    //             content: svgStringList,
    //             type: "image/svg+xml"
    //         }),
    //     success: function(response) {
    //         console.log('SVGList salvata con successo!');
    //     },
    //     error: function(error) {
    //         console.error('Errore nell\'invio dell\'SVGList:', error);
    //     }
    // });

}