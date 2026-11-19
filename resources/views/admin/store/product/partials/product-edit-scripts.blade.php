{{-- =====================================================
     PRODUCT EDIT STYLES & INTERACTIVE JAVASCRIPT
     ===================================================== --}}
<style>
    .store-panel-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .store-image-box:hover .store-gallery-preview-img {
        transform: scale(1.06);
    }
    .store-gallery-preview-img {
        transition: transform 0.25s ease;
    }
    .store-quick-add-tile:hover {
        border-color: #4f46e5 !important;
        background: #f8fafc !important;
        transform: translateY(-2px);
    }
    .store-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: storePulse 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
    }
    @keyframes storePulse {
        to {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Character counter helpers
        const setupCounter = (inputId, counterId, max) => {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            if (!input || !counter) return;

            const update = () => {
                const len = input.value.length;
                counter.textContent = `${len}/${max}`;
            };
            input.addEventListener('input', update);
        };

        setupCounter('store_title', 'storeTitleCharCount', 255);
        setupCounter('short_description', 'shortDescCharCount', 500);
        setupCounter('meta_title', 'metaTitleCount', 255);
        setupCounter('meta_description', 'metaDescCount', 500);

        // Real-time Google SERP Snippet Preview Updater
        const storeTitleInput = document.getElementById('store_title');
        const metaTitleInput = document.getElementById('meta_title');
        const shortDescInput = document.getElementById('short_description');
        const metaDescInput = document.getElementById('meta_description');

        const serpTitlePreview = document.getElementById('serpTitlePreview');
        const serpSnippetPreview = document.getElementById('serpSnippetPreview');

        const fallbackTitle = "{{ $product->name }}";
        const fallbackSnippet = "Artisanal treats, celebration cakes, and gourmet bakes made fresh daily in Lagos.";

        function updateSerp() {
            if (serpTitlePreview) {
                const title = metaTitleInput?.value.trim() || storeTitleInput?.value.trim() || fallbackTitle;
                serpTitlePreview.textContent = title.includes('Moshel') ? title : `${title} | Moshel Edibles`;
            }

            if (serpSnippetPreview) {
                const snippet = metaDescInput?.value.trim() || shortDescInput?.value.trim() || fallbackSnippet;
                serpSnippetPreview.textContent = snippet.length > 155 ? snippet.substring(0, 155) + '...' : snippet;
            }
        }

        [storeTitleInput, metaTitleInput, shortDescInput, metaDescInput].forEach(el => {
            if (el) el.addEventListener('input', updateSerp);
        });

        // Copy direct link to clipboard with Toast
        const copyBtn = document.getElementById('copyStoreLinkBtn');
        const urlInput = document.getElementById('storeProductDirectUrl');
        const feedback = document.getElementById('copyFeedback');

        if (copyBtn && urlInput) {
            copyBtn.addEventListener('click', function () {
                urlInput.select();
                urlInput.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(urlInput.value).then(() => {
                    if (feedback) {
                        feedback.classList.remove('d-none');
                        setTimeout(() => feedback.classList.add('d-none'), 2500);
                    }
                    if (window.Swal) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Customer share URL copied to clipboard!'
                        });
                    }
                });
            });
        }

        // Live image file preview in modal dropzone
        const fileInput = document.getElementById('image');
        const dropzonePrompt = document.getElementById('dropzonePrompt');
        const dropzonePreview = document.getElementById('dropzonePreview');
        const previewImg = document.getElementById('imagePreviewImg');
        const previewName = document.getElementById('imagePreviewName');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        previewName.textContent = file.name;
                        dropzonePrompt.classList.add('d-none');
                        dropzonePreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // SweetAlert enhancement for image deletion
        document.querySelectorAll('.store-delete-image-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (window.Swal) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Delete this Photo?',
                        text: 'This image will be permanently removed from the storefront gallery.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
