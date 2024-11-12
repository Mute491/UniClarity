
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

async function saveSvg(acquistiId){

    let drawSvg = $("#draw-svg-div").children();
    let svgStringList = {};
    let counter = 0;

    drawSvg.each(function () {

        svgStringList[counter.toString()] = new XMLSerializer().serializeToString(this);
        counter++;

    });
    
    console.log("contatto il webhook");
    const response = await fetch("https://hooks.zapier.com/hooks/catch/20292832/25nr9qc/", {
        method: "POST",
        body: JSON.stringify({
            acquistiId: acquistiId, 
            content: [JSON.stringify(svgStringList)]
        }),
      });
      
    console.log(response);
    alert("Salvataggio effettuato");

}