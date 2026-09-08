<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membaca: {{ $book->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';</script>
    
    <!-- PageFlip -->
    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

    <style>
        body { background-color: #1a1a24; margin: 0; padding: 0; display: flex; flex-direction: column; height: 100vh; overflow: hidden; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif; }
        .header { background-color: #12121a; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; color: white; height: 60px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); z-index: 50; }
        .workspace { flex: 1; display: flex; justify-content: center; align-items: center; position: relative; overflow: hidden; padding: 20px;}
        
        .flipbook { display: none; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
        .page { background-color: white; overflow: hidden; border: 1px solid #ddd; }
        .page-content { width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; }
        .page canvas { width: 100%; height: 100%; object-fit: contain; }
        .page.--left { border-right: 0; box-shadow: inset -7px 0 30px -7px rgba(0,0,0,0.1); }
        .page.--right { border-left: 0; box-shadow: inset 7px 0 30px -7px rgba(0,0,0,0.1); }
        
        /* Loading Overlay */
        #loader { position: absolute; inset: 0; background: #1a1a24; display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 100; color: white; }
        .spinner { border: 4px solid rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 50%; border-left-color: #3b82f6; animation: spin 1s linear infinite; margin-bottom: 1rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Nav Buttons */
        .nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.1); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; z-index: 40; transition: background 0.3s; display: flex; justify-content: center; align-items: center;}
        .nav-btn:hover { background: rgba(255,255,255,0.3); }
        #btn-prev { left: 20px; }
        #btn-next { right: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('opac.show', $book->id) }}" class="text-white hover:text-blue-400 transition-colors bg-white/10 px-3 py-1.5 rounded-lg flex items-center text-sm font-medium">
                <i class="bi bi-arrow-left mr-2"></i> Kembali
            </a>
            <h1 class="text-base font-bold truncate max-w-xs md:max-w-md m-0">{{ $book->title }}</h1>
        </div>
        <div class="flex items-center gap-4 text-sm text-slate-400">
            <span id="page-counter">Hal 1 dari ?</span>
        </div>
    </div>

    <div class="workspace" id="workspace">
        <div id="loader">
            <div class="spinner"></div>
            <div id="loader-text" class="font-semibold text-slate-300">Memuat Dokumen...</div>
        </div>

        <button class="nav-btn" id="btn-prev" style="display:none;"><i class="bi bi-chevron-left text-xl"></i></button>
        <button class="nav-btn" id="btn-next" style="display:none;"><i class="bi bi-chevron-right text-xl"></i></button>

        <div id="flipbook" class="flipbook">
            <!-- Pages will be generated here -->
        </div>
    </div>

    <script>
        const url = '{{ asset('storage/' . $book->digital_file_path) }}';
        const flipbookContainer = document.getElementById('flipbook');
        const loader = document.getElementById('loader');
        const loaderText = document.getElementById('loader-text');
        
        let pdfDoc = null;
        let pageFlip = null;
        
        // Configuration
        const scale = 2.0; // Render scale for quality (ditingkatkan agar teks lebih tajam)
        
        const renderedPages = new Set();
        let canvases = [];

        async function renderPage(pageNum) {
            if (renderedPages.has(pageNum)) return;
            renderedPages.add(pageNum); // Tandai agar tidak di-render ulang
            
            try {
                const page = await pdfDoc.getPage(pageNum);
                const renderViewport = page.getViewport({ scale: scale });
                const canvas = canvases[pageNum - 1];
                
                // Set resolusi asli canvas sesuai viewport
                canvas.width = renderViewport.width;
                canvas.height = renderViewport.height;
                
                const ctx = canvas.getContext('2d');
                await page.render({ canvasContext: ctx, viewport: renderViewport }).promise;
            } catch (e) {
                console.error('Error rendering page', pageNum, e);
            }
        }
        
        async function renderPDF() {
            try {
                // 1. Fetch PDF
                pdfDoc = await pdfjsLib.getDocument(url).promise;
                const numPages = pdfDoc.numPages;
                loaderText.textContent = `Menyiapkan ${numPages} halaman buku...`;
                
                // Get page 1 dimensions to set book size
                const firstPage = await pdfDoc.getPage(1);
                const viewport = firstPage.getViewport({ scale: 1 }); // gunakan scale 1 untuk ukuran base flipbook
                const renderViewport = firstPage.getViewport({ scale: scale }); // untuk canvas background
                
                const bookWidth = viewport.width;
                const bookHeight = viewport.height;
                
                // 2. Generate Empty Canvas Elements (Biar cepat, hanya bikin bungkusnya dulu)
                for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'page';
                    
                    const content = document.createElement('div');
                    content.className = 'page-content';
                    
                    const canvas = document.createElement('canvas');
                    // Sementara berikan ukuran default
                    canvas.width = renderViewport.width;
                    canvas.height = renderViewport.height;
                    
                    content.appendChild(canvas);
                    wrapper.appendChild(content);
                    flipbookContainer.appendChild(wrapper);
                    
                    canvases.push(canvas);
                }
                
                // 3. Initialize PageFlip langsung tanpa menunggu semua render
                loaderText.textContent = 'Membangun Buku...';
                
                flipbookContainer.style.display = 'block';
                
                // Calculate exact dimensions to maintain aspect ratio
                const workspace = document.getElementById('workspace');
                const ww = workspace.clientWidth - 40; // 20px padding on each side
                const wh = workspace.clientHeight - 40;
                
                const targetRatio = (bookWidth * 2) / bookHeight;
                const workspaceRatio = ww / wh;
                
                let finalPageWidth, finalPageHeight;
                
                if (workspaceRatio > targetRatio) {
                    // Height is the limiting factor
                    finalPageHeight = wh;
                    finalPageWidth = (wh * targetRatio) / 2;
                } else {
                    // Width is the limiting factor
                    finalPageWidth = ww / 2;
                    finalPageHeight = ww / targetRatio;
                }
                
                pageFlip = new St.PageFlip(flipbookContainer, {
                    width: finalPageWidth,
                    height: finalPageHeight,
                    size: "fixed",
                    drawShadow: true,
                    showCover: true,
                    usePortrait: true,
                    mobileScrollSupport: true,
                    maxShadowOpacity: 0.5,
                    showPageCorners: true
                });
                
                pageFlip.loadFromHTML(document.querySelectorAll('.page'));
                
                // 4. Render awal (Cover dan beberapa halaman depan saja)
                for (let i = 1; i <= Math.min(4, numPages); i++) {
                    renderPage(i);
                }
                
                // Hide loader
                loader.style.display = 'none';
                document.getElementById('btn-prev').style.display = 'flex';
                document.getElementById('btn-next').style.display = 'flex';
                
                // Controls
                document.getElementById('btn-prev').addEventListener('click', () => pageFlip.flipPrev());
                document.getElementById('btn-next').addEventListener('click', () => pageFlip.flipNext());
                
                // 5. Render sisa halaman hanya saat halaman dibalik (Lazy Load)
                pageFlip.on('flip', (e) => {
                    const currentPage = e.data + 1;
                    document.getElementById('page-counter').textContent = `Hal ${currentPage} dari ${pageFlip.getPageCount()}`;
                    
                    // Render halaman saat ini, dan 2 halaman di sekitarnya
                    const pagesToRender = [
                        currentPage - 2, currentPage - 1, currentPage, 
                        currentPage + 1, currentPage + 2, currentPage + 3
                    ];
                    
                    pagesToRender.forEach(pageNum => {
                        if (pageNum >= 1 && pageNum <= numPages) {
                            renderPage(pageNum);
                        }
                    });
                });
                
                document.getElementById('page-counter').textContent = `Hal 1 dari ${pageFlip.getPageCount()}`;
                
            } catch (error) {
                console.error(error);
                loaderText.textContent = 'Gagal memuat dokumen. ' + error.message;
                loaderText.style.color = '#ef4444';
                loader.querySelector('.spinner').style.display = 'none';
            }
        }
        
        // Start
        renderPDF();
    </script>
</body>
</html>
