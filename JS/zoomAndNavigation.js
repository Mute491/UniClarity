
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

async function saveSvg(acquistiId, svgDictionary){

    let svgStringList = {};
    let counter = 0;

    for (var key in svgDictionary){

        if(svgDictionary[key] === ""){
            svgStringList[key] = svgDictionary[key];
        }
    }

    // svgDictionary.each(function () {

    //     svgStringList[counter.toString()] = new XMLSerializer().serializeToString(this);
    //     counter++;

    // });
    
    console.log("contatto il webhook");
    const response = await fetch("https://hooks.zapier.com/hooks/catch/20680064/25varql/", {
        method: "POST",
        body: JSON.stringify({
            acquistiId: acquistiId, 
            content: [JSON.stringify(svgStringList)]
        }),
      });
      
    console.log(response);

    if (response.ok) {
        $("#saveOutput").text("Appunti salvati con successo!");
        $("#saveOutput").css("color", "green");
    } else {
        $("#saveOutput").text("Errore durante il salvataggio degli appunti");
        $("#saveOutput").css("color", "red");
        console.error("Errore nella richiesta:", statusCode);
    }
    

}