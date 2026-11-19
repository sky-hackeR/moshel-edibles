<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputSiteName = document.getElementById('inputSiteName');
    const inputDesc = document.getElementById('inputDescription');
    const siteNameCount = document.getElementById('siteNameCount');
    const descCount = document.getElementById('descriptionCount');

    const fileInputLogo = document.getElementById('fileInputLogo');
    const fileInputFavicon = document.getElementById('fileInputFavicon');
    const previewLogoImg = document.getElementById('previewLogoImg');
    const previewFaviconImg = document.getElementById('previewFaviconImg');
    const logoFileDetails = document.getElementById('logoFileDetails');
    const faviconFileDetails = document.getElementById('faviconFileDetails');

    const logoDropzone = document.getElementById('logoDropzone');
    const faviconDropzone = document.getElementById('faviconDropzone');

    // Folding Elements
    const editorCol = document.getElementById('editorCol');
    const previewCol = document.getElementById('previewCol');
    const editorDockRail = document.getElementById('editorDockRail');
    const btnFoldEditor = document.getElementById('btnFoldEditor');
    const btnRailUnfold = document.getElementById('btnRailUnfold');
    const btnUnfoldEditorTop = document.getElementById('btnUnfoldEditorTop');

    // Emulation Elements
    const storefrontIframe = document.getElementById('storefrontPreviewIframe');
    const deviceFrameWrapper = document.getElementById('deviceFrameWrapper');
    const deviceButtons = document.querySelectorAll('.device-btn');
    const deviceDimensionIndicator = document.getElementById('deviceDimensionIndicator');
    const btnReloadFrame = document.getElementById('btnReloadFrame');
    const routeSelector = document.getElementById('routeSelector');
    const urlDisplayBar = document.getElementById('urlDisplayBar');
    const btnPopOutLink = document.getElementById('btnPopOutLink');
    const iframeMiniSpinner = document.getElementById('iframeMiniSpinner');
    const btnToggleMaximizeHeight = document.getElementById('btnToggleMaximizeHeight');
    const maximizeIcon = document.getElementById('maximizeIcon');
    const emulatorStageContainer = document.getElementById('emulatorStageContainer');
    const zoomOptions = document.querySelectorAll('.zoom-opt');
    const btnZoomLabel = document.getElementById('btnZoomLabel');
    const btnTriggerManualSync = document.getElementById('btnTriggerManualSync');

    let currentLogoBase64 = null;
    let currentFaviconBase64 = null;
    let isMaximizedHeight = false;

    // =========================================================================
    // 1. FOLDING EDITOR TOWARD SIDEBAR (EXPAND STOREFRONT TO WIDE SCREEN)
    // =========================================================================
    function foldEditor() {
        if (!editorCol || !previewCol) return;

        editorCol.classList.add('d-none');
        if (editorDockRail) {
            editorDockRail.classList.remove('d-none');
            editorDockRail.classList.add('d-flex');
        }

        previewCol.classList.remove('col-xl-6', 'col-lg-6');
        previewCol.classList.add('col-xl-12', 'col-lg-12');

        if (btnUnfoldEditorTop) {
            btnUnfoldEditorTop.classList.remove('d-none');
            btnUnfoldEditorTop.classList.add('d-inline-flex');
        }

        try {
            localStorage.setItem('moshel_sitesettings_editor_folded', 'true');
        } catch (e) {}
    }

    function unfoldEditor() {
        if (!editorCol || !previewCol) return;

        editorCol.classList.remove('d-none');
        if (editorDockRail) {
            editorDockRail.classList.remove('d-flex');
            editorDockRail.classList.add('d-none');
        }

        previewCol.classList.remove('col-xl-12', 'col-lg-12');
        previewCol.classList.add('col-xl-6', 'col-lg-6');

        if (btnUnfoldEditorTop) {
            btnUnfoldEditorTop.classList.remove('d-inline-flex');
            btnUnfoldEditorTop.classList.add('d-none');
        }

        try {
            localStorage.setItem('moshel_sitesettings_editor_folded', 'false');
        } catch (e) {}
    }

    if (btnFoldEditor) btnFoldEditor.addEventListener('click', foldEditor);
    if (btnRailUnfold) btnRailUnfold.addEventListener('click', unfoldEditor);
    if (btnUnfoldEditorTop) btnUnfoldEditorTop.addEventListener('click', unfoldEditor);

    // Restore folding preference
    try {
        if (localStorage.getItem('moshel_sitesettings_editor_folded') === 'true') {
            foldEditor();
        }
    } catch (e) {}

    // =========================================================================
    // 2. LIVE STOREFRONT EMULATOR: REAL-TIME DOM SYNC BRIDGE
    // =========================================================================
    function syncFormToIframe() {
        if (!storefrontIframe) return;

        try {
            const iframeDoc = storefrontIframe.contentDocument || storefrontIframe.contentWindow?.document;
            if (!iframeDoc) return;

            const nameVal = (inputSiteName ? inputSiteName.value.trim() : '') || 'Moshel Edibles';
            const descVal = (inputDesc ? inputDesc.value.trim() : '');

            // 1. Update Title and Meta
            iframeDoc.title = nameVal + ' | Store';
            const metaTitle = iframeDoc.querySelector('meta[name="title"]');
            if (metaTitle) metaTitle.setAttribute('content', nameVal);

            // 2. Update Navbar & Footer Brand Logos
            const allLogos = iframeDoc.querySelectorAll('.navbar-brand img, .footer-logo img, img[alt*="Logo"], img[alt*="logo"]');
            allLogos.forEach(img => {
                img.alt = nameVal;
                if (currentLogoBase64) {
                    img.src = currentLogoBase64;
                }
            });

            // 3. Update Brand text if present
            const brandTexts = iframeDoc.querySelectorAll('.navbar-brand span, .brand-title, .site-name-text');
            brandTexts.forEach(el => el.textContent = nameVal);

            // 4. Update Favicon if selected
            if (currentFaviconBase64) {
                let favLink = iframeDoc.querySelector('link[rel="shortcut icon"], link[rel="icon"]');
                if (!favLink) {
                    favLink = iframeDoc.createElement('link');
                    favLink.rel = 'shortcut icon';
                    iframeDoc.head.appendChild(favLink);
                }
                favLink.href = currentFaviconBase64;
            }

            // Visual pulse on Live Sync badge
            const syncBadge = document.getElementById('liveSyncStatusBadge');
            if (syncBadge) {
                syncBadge.classList.add('bg-success', 'text-white');
                setTimeout(() => {
                    syncBadge.classList.remove('bg-success', 'text-white');
                }, 500);
            }
        } catch (err) {
            // Silently ignore cross-origin restrictions if navigates off-domain
            console.debug('Storefront live sync note:', err);
        }
    }

    if (storefrontIframe) {
        storefrontIframe.addEventListener('load', function () {
            if (iframeMiniSpinner) iframeMiniSpinner.classList.add('d-none');
            syncFormToIframe();
        });
    }

    if (btnTriggerManualSync) {
        btnTriggerManualSync.addEventListener('click', function (e) {
            e.preventDefault();
            syncFormToIframe();
        });
    }

    // =========================================================================
    // 3. FORM INPUT COUNTERS & LIVE PROPAGATION
    // =========================================================================
    function updateTextPreviews() {
        if (siteNameCount && inputSiteName) {
            siteNameCount.textContent = `${inputSiteName.value.length} / 60`;
        }
        if (descCount && inputDesc) {
            descCount.textContent = `${inputDesc.value.length} / 255`;
        }
        syncFormToIframe();
    }

    if (inputSiteName) inputSiteName.addEventListener('input', updateTextPreviews);
    if (inputDesc) inputDesc.addEventListener('input', updateTextPreviews);
    updateTextPreviews();

    // =========================================================================
    // 4. CLIENT-SIDE IMAGE SELECTION & LIVE PREVIEW
    // =========================================================================
    function handleImageSelection(file, previewImg, detailsBadge, isLogo) {
        if (!file) return;

        if (!file.type.startsWith('image/') && !file.name.endsWith('.ico')) {
            alert('Please select a valid image file (PNG, JPG, SVG, or ICO).');
            return;
        }

        const sizeKb = (file.size / 1024).toFixed(1);
        const reader = new FileReader();

        reader.onload = function (e) {
            const resultUrl = e.target.result;
            if (previewImg) previewImg.src = resultUrl;

            if (isLogo) {
                currentLogoBase64 = resultUrl;
            } else {
                currentFaviconBase64 = resultUrl;
            }

            if (detailsBadge) {
                detailsBadge.className = 'badge bg-success-subtle text-success font-size-10';
                detailsBadge.innerHTML = `<i class="bx bx-check me-1"></i>Selected: ${file.name} (${sizeKb} KB)`;
            }

            syncFormToIframe();
        };

        reader.readAsDataURL(file);
    }

    if (fileInputLogo) {
        fileInputLogo.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleImageSelection(this.files[0], previewLogoImg, logoFileDetails, true);
            }
        });
    }

    if (fileInputFavicon) {
        fileInputFavicon.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleImageSelection(this.files[0], previewFaviconImg, faviconFileDetails, false);
            }
        });
    }

    // Drag and Drop & Click to Select
    function setupDropzone(dropzoneEl, fileInputEl) {
        if (!dropzoneEl || !fileInputEl) return;

        // Click anywhere inside dropzone triggers the file picker (unless clicking a button or input directly)
        dropzoneEl.addEventListener('click', function (e) {
            if (e.target !== fileInputEl && !e.target.closest('button')) {
                fileInputEl.click();
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneEl.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneEl.classList.add('border-primary', 'bg-white');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneEl.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneEl.classList.remove('border-primary', 'bg-white');
            }, false);
        });

        dropzoneEl.addEventListener('drop', function (e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInputEl.files = files;
                fileInputEl.dispatchEvent(new Event('change'));
            }
        }, false);
    }

    setupDropzone(logoDropzone, fileInputLogo);
    setupDropzone(faviconDropzone, fileInputFavicon);

    // =========================================================================
    // 5. DEVICE VIEWPORT SWITCHER (DESKTOP, LAPTOP, TABLET, MOBILE)
    // =========================================================================
    deviceButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            deviceButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const device = this.getAttribute('data-device');
            applyDeviceEmulation(device);
        });
    });

    function applyDeviceEmulation(device) {
        if (!deviceFrameWrapper || !deviceDimensionIndicator) return;

        deviceFrameWrapper.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';

        switch (device) {
            case 'laptop':
                deviceFrameWrapper.style.maxWidth = '1024px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '2px solid #cbd5e1';
                deviceFrameWrapper.style.borderRadius = '12px';
                deviceFrameWrapper.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-laptop me-1 text-primary"></i> Laptop Viewport (1024px Screen Standard)';
                break;
            case 'tablet':
                deviceFrameWrapper.style.maxWidth = '768px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '10px solid #334155';
                deviceFrameWrapper.style.borderRadius = '24px';
                deviceFrameWrapper.style.boxShadow = '0 15px 35px rgba(0,0,0,0.18)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-tab me-1 text-info"></i> Tablet Viewport (768px iPad Aspect)';
                break;
            case 'mobile':
                deviceFrameWrapper.style.maxWidth = '390px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '12px solid #1e293b';
                deviceFrameWrapper.style.borderRadius = '36px';
                deviceFrameWrapper.style.boxShadow = '0 20px 40px rgba(0,0,0,0.22)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-mobile-alt me-1 text-success"></i> Smartphone Viewport (390px Mobile Responsive)';
                break;
            case 'desktop':
            default:
                deviceFrameWrapper.style.maxWidth = '100%';
                deviceFrameWrapper.style.margin = '0';
                deviceFrameWrapper.style.border = 'none';
                deviceFrameWrapper.style.borderRadius = '8px';
                deviceFrameWrapper.style.boxShadow = '0 4px 15px rgba(0,0,0,0.06)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-desktop me-1 text-primary"></i> Desktop Viewport (Fluid 100% Full Width)';
                break;
        }
    }

    // =========================================================================
    // 6. ZOOM & SCALE SELECTOR
    // =========================================================================
    zoomOptions.forEach(opt => {
        opt.addEventListener('click', function (e) {
            e.preventDefault();
            zoomOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');

            const zoomVal = parseFloat(this.getAttribute('data-zoom')) || 1;
            if (btnZoomLabel) btnZoomLabel.textContent = `${Math.round(zoomVal * 100)}%`;

            if (storefrontIframe) {
                storefrontIframe.style.transform = zoomVal === 1 ? 'none' : `scale(${zoomVal})`;
                storefrontIframe.style.transformOrigin = 'top center';
                storefrontIframe.style.width = zoomVal === 1 ? '100%' : `${100 / zoomVal}%`;
                storefrontIframe.style.height = zoomVal === 1 ? '100%' : `${100 / zoomVal}%`;
            }
        });
    });

    // =========================================================================
    // 7. ROUTE SWITCHER & RELOAD
    // =========================================================================
    if (routeSelector && storefrontIframe) {
        routeSelector.addEventListener('change', function () {
            const targetUrl = this.value;
            if (iframeMiniSpinner) iframeMiniSpinner.classList.remove('d-none');

            storefrontIframe.src = targetUrl;
            if (urlDisplayBar) urlDisplayBar.textContent = targetUrl;
            if (btnPopOutLink) btnPopOutLink.href = targetUrl;
        });
    }

    if (btnReloadFrame && storefrontIframe) {
        btnReloadFrame.addEventListener('click', function () {
            if (iframeMiniSpinner) iframeMiniSpinner.classList.remove('d-none');
            try {
                storefrontIframe.contentWindow?.location.reload();
            } catch (e) {
                storefrontIframe.src = storefrontIframe.src;
            }
        });
    }

    // =========================================================================
    // 8. MAXIMIZE HEIGHT TOGGLE
    // =========================================================================
    if (btnToggleMaximizeHeight && emulatorStageContainer) {
        btnToggleMaximizeHeight.addEventListener('click', function () {
            isMaximizedHeight = !isMaximizedHeight;
            if (isMaximizedHeight) {
                emulatorStageContainer.style.height = 'calc(100vh - 100px)';
                if (maximizeIcon) maximizeIcon.className = 'bx bx-exit-fullscreen';
                btnToggleMaximizeHeight.classList.add('active');
            } else {
                emulatorStageContainer.style.height = 'calc(100vh - 240px)';
                if (maximizeIcon) maximizeIcon.className = 'bx bx-fullscreen';
                btnToggleMaximizeHeight.classList.remove('active');
            }
        });
    }

    // =========================================================================
    // 9. SUBMIT BUTTON SPINNER
    // =========================================================================
    const form = document.getElementById('siteConfigForm');
    const btnSave = document.getElementById('btnSaveConfig');
    if (form && btnSave) {
        form.addEventListener('submit', function () {
            btnSave.disabled = true;
            btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Saving Configuration...';
        });
    }
});
</script>
