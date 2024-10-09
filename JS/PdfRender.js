import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

class PdfRender{

    constructor(pdfUrl, scale, canvasId){

        this.pdf = null;
        this.pageNum = 1;
        this.scale = scale;
        this.url = pdfUrl;
        this.canvas = document.getElementById(canvasId);
        this.ctx = canvas.getContext("2d");

        this.viewport = null;

        this.pdf = pdfjsLib.getDocument(this.url).promise;
        this.pageMaxNumber = this.pdf.numPages;

    }
    
    getViewport(){

        return page.getViewport({ scale: this.scale });

    }

    setScale(newScale){

        this.scale = newScale;

    }


    renderPage(num) {
    
        this.pdf.getPage(num).then(function (page) {
            
            this.viewport = page.getViewport({ scale: scale });
    
            this.canvas.height = this.viewport.height;
            this.canvas.width = this.viewport.width;
    
            // Rendering della pagina del PDF
            var renderContext = {
                canvasContext: this.ctx,
                viewport: this.viewport
            };
    
            page.render(renderContext);

        });
    }

}
