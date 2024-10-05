var drawing = false;
var lastX = 0, lastY = 0;

function caricaEventi() {
    // Eventi per disegnare su SVG
    $("#draw-svg-div").children().on("mousedown", function (e) {
        drawing = true;
        lastX = e.offsetX;
        lastY = e.offsetY;
    });

    $("#draw-svg-div").children().on("mousemove", function (e) {
        if (!drawing) return;

        let line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', lastX);
        line.setAttribute('y1', lastY);
        line.setAttribute('x2', e.offsetX);
        line.setAttribute('y2', e.offsetY);
        line.setAttribute('stroke', $("#segment-color").val());
        line.setAttribute('stroke-width', $("#segment-width").val() / 10);
        line.setAttribute('stroke-linecap', 'round');

        this.appendChild(line);

        lastX = e.offsetX;
        lastY = e.offsetY;
    });

    $("#draw-svg-div").children().on("mouseup", function () {
        drawing = false;
    });

    $("#draw-svg-div").children().on("mouseleave", function () {
        drawing = false;
    });
}

function generateCanvas(num) {
    for (let i = 0; i < num; i++) {
        $("#draw-svg-div").append($("<svg class='draw-svg'></svg>"));
    }

    let drawCanvas = $("#draw-svg-div").children();
    drawCanvas.each(function () {
        this.width = $("#canvas-div").width();
        this.height = $("#canvas-div").height();
    });

    caricaEventi();
}