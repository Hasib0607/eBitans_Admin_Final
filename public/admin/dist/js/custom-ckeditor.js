//*************** CKEditor start ***************************//
var ckEditorCurrentDialog = null;

CKEDITOR.on('dialogDefinition', function (e) {
    var dialogName = e.data.name;
    var dialogDefinition = e.data.definition;

    if (dialogName === 'image') {
        var browseButton = dialogDefinition.getContents('info').get('browse');
        var browseButtonLink = dialogDefinition.getContents('Link').get('browse');

        browseButton.onClick = function () {
            fileManagerCKEditorModal();
        };

        browseButtonLink.onClick = function () {
            fileManagerCKEditorModal();
        };

        // Modify the onClick event of the 'Browse' button
        // browseButton.onClick = function () {
        //     e.data.dialog.hide();
        //     fileManagerCKEditorModal();
        // };

        // e.data.dialog.on('show', function () {
        //     ckEditorCurrentDialog = e.data.dialog;
        //     if (browseButton && typeof browseButton.onClick === 'function') {
        //         browseButton.onClick();
        //     }
        // });

    }
});

function fileManagerCKEditorModal() {
    $('#fileManagerCKEditorModal').modal('show');
}

function ckEditorFileSelection(selectedUrls) {
    // const editor = CKEDITOR.currentInstance;
    const dialog = CKEDITOR.dialog.getCurrent();

    if (dialog) {
        if (Array.isArray(selectedUrls) && selectedUrls.length > 0) {
            dialog.setValueOf('info', 'txtUrl', selectedUrls[0].url);
            dialog.setValueOf('Link', 'txtUrl', selectedUrls[0].url);

            // // Method 2 - Direct DOM manipulation (fallback)
            // const input = document.querySelector('.cke_dialog_ui_input_text[name="txtUrl"]');
            // if (input) {
            //     input.value = selectedUrl;
            //     $(input).trigger('change');
            // }

            // Force the preview to update
            if (typeof dialog.preview === 'function') {
                dialog.preview();
            }

            // const okButton = dialog.getButton('ok');
            // if (okButton) {
            //     okButton.click();  // Trigger 'OK' button click to submit and close the dialog
            // } else {
            //     console.error("OK button not found in the dialog.");
            // }

            // 3. Find and click the OK button
            // setTimeout(() => {
            //     const okButton = dialog.getButton('ok');
            //     if (okButton) {
            //         okButton.click();
            //     } else {
            //         const buttons = dialog.getElement().$.querySelectorAll('.cke_dialog_ui_button');
            //         const okBtn = Array.from(buttons).find(btn =>
            //             btn.textContent.includes('OK') || btn.title.includes('OK')
            //         );
            //         if (okBtn) okBtn.click();
            //     }
            // }, 100);
        } else {
            console.error("No valid images were selected or selectedUrls is not an array.");
        }
    } else {
        console.error('Could not find active CKEditor dialog');
    }

    $('#fileManagerCKEditorModal').modal('hide');
}


// function ckEditorFileSelection(selectedUrls) {
//     if (ckEditorCurrentDialog) {
//         console.log("have editor")
//         if (Array.isArray(selectedUrls) && selectedUrls.length > 0) {
//             ckEditorCurrentDialog.setValueOf('info', 'txtUrl', selectedUrls[0].url);
//
//             // // Method 2 - Direct DOM manipulation (fallback)
//             // const input = document.querySelector('.cke_dialog_ui_input_text[name="txtUrl"]');
//             // if (input) {
//             //     input.value = selectedUrl;
//             //     $(input).trigger('change');
//             // }
//
//             // Force the preview to update
//             if (typeof ckEditorCurrentDialog.preview === 'function') {
//                 ckEditorCurrentDialog.preview();
//             }
//
//             // Re-open the CKEditor dialog
//             const editor = ckEditorCurrentDialog.getParentEditor();
//             if (editor) {
//                 // Open the dialog again (this will bring up the dialog with the updated value)
//                 editor.openDialog(ckEditorCurrentDialog.definition.dialogName);
//             }
//
//             const okButton = ckEditorCurrentDialog.getButton('ok');
//             if (okButton) {
//                 okButton.click();  // Trigger 'OK' button click to submit and close the dialog
//             } else {
//                 console.error("OK button not found in the dialog.");
//             }
//
//             // 3. Find and click the OK button
//             // setTimeout(() => {
//             //     const okButton = ckEditorCurrentDialog.getButton('ok');
//             //     if (okButton) {
//             //         okButton.click();
//             //     } else {
//             //         const buttons = ckEditorCurrentDialog.getElement().$.querySelectorAll('.cke_dialog_ui_button');
//             //         const okBtn = Array.from(buttons).find(btn =>
//             //             btn.textContent.includes('OK') || btn.title.includes('OK')
//             //         );
//             //         if (okBtn) okBtn.click();
//             //     }
//             // }, 100);
//         } else {
//             console.error("No valid images were selected or selectedUrls is not an array.");
//         }
//     } else {
//         console.error('Could not find active CKEditor dialog');
//     }
//
//     $('#fileManagerCKEditorModal').modal('hide');
// }

//*************** CKEditor end ***************************//


//*************** Stand Alone Button start ***************************//
let selectedImages = [];
let singleImage = null;
let insertUrlInputId = null;
let previewContainerId = null;
let imgAddBtnId = null;
let standAloneStatus = null;

function updatePreview() {
    if (standAloneStatus) {
        if (Array.isArray(selectedImages) && selectedImages.length > 0) {
            // document.getElementById(insertUrlInputId).value = selectedImages[0].url;
            const inputElement = resolveElement(insertUrlInputId);
            if (inputElement) {
                inputElement.value = singleImage;
            }
            if (previewContainerId !== null) {
                const container = document.getElementById(previewContainerId);

                // Remove all preview images (not the upload button)
                const existingWrappers = container.querySelectorAll('.image-preview');
                existingWrappers.forEach(wrapper => wrapper.remove());

                if (singleImage) {
                    // Add preview images after the imageBox
                    const wrapper = document.createElement('div');
                    wrapper.className = 'image-preview';
                    wrapper.style.position = 'relative';
                    wrapper.style.display = 'inline-block';

                    const img = document.createElement('img');
                    img.src = resolveGalleryImagePreviewSrc(singleImage);
                    img.style.height = '100px';
                    img.style.border = '1px solid #ccc';
                    img.style.padding = '3px';
                    img.style.marginRight = '10px';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.className = 'imageUploadRemoveBtn';

                    removeBtn.onclick = function () {
                        singleImage = null;
                        updatePreview();
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    container.appendChild(wrapper);
                }

            }
        }
    } else {
        if (previewContainerId !== null && imgAddBtnId !== null) {
            const container = document.getElementById(previewContainerId);
            const imageBox = document.getElementById(imgAddBtnId);

            // Remove all preview images (not the upload button)
            const existingWrappers = container.querySelectorAll('.image-preview');
            existingWrappers.forEach(wrapper => wrapper.remove());

            // Drop server-rendered gallery thumbs (not main product images: .oldImg-wrap)
            container.querySelectorAll('.imgWrapperDiv:not(.oldImg-wrap)').forEach(function (el) {
                el.remove();
            });

            // Add preview images after the imageBox
            selectedImages.forEach((url) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'image-preview';
                wrapper.style.position = 'relative';
                wrapper.style.display = 'inline-block';

                const img = document.createElement('img');
                img.src = resolveGalleryImagePreviewSrc(url);
                img.style.height = '100px';
                img.style.border = '1px solid #ccc';
                img.style.padding = '3px';
                img.style.marginRight = '10px';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '&times;';
                removeBtn.className = 'imageUploadRemoveBtn';

                (function (captureUrl) {
                    removeBtn.onclick = function () {
                        var norm = normalizeGalleryImagePath(captureUrl);
                        selectedImages = selectedImages.filter(function (existing) {
                            return normalizeGalleryImagePath(existing) !== norm;
                        });
                        updatePreview();
                    };
                })(url);

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                imageBox.before(wrapper); // Insert after the upload button
            });

            document.getElementById(insertUrlInputId).value = selectedImages.join(',');
        }
    }

}

function resolveElement(inputOrId) {
    if (typeof inputOrId === 'string') {
        return document.getElementById(inputOrId);
    } else if (inputOrId instanceof HTMLElement) {
        return inputOrId;
    }
    return null;
}

/**
 * Normalize gallery URL/path so file-manager picks merge with hidden-input values (paths vs full URLs).
 */
function normalizeGalleryImagePath(url) {
    if (!url) {
        return '';
    }
    url = String(url).trim();
    if (!url) {
        return '';
    }
    try {
        if (url.indexOf('http://') === 0 || url.indexOf('https://') === 0) {
            var u = new URL(url);
            return u.pathname.replace(/^\//, '');
        }
    } catch (e) {
        /* ignore */
    }
    return url.replace(/^\//, '');
}

/**
 * Absolute URL for <img src> on deep routes (e.g. /products/edit/123).
 * LFM often returns site-relative paths; the browser would otherwise resolve them under /products/edit/...
 */
function resolveGalleryImagePreviewSrc(url) {
    if (!url) {
        return '';
    }
    url = String(url).trim();
    if (!url) {
        return '';
    }
    if (/^https?:\/\//i.test(url)) {
        return url;
    }
    if (url.indexOf('//') === 0) {
        return window.location.protocol + url;
    }
    var base = '';
    if (typeof window.API_URL === 'string' && window.API_URL.length) {
        base = window.API_URL.replace(/\/$/, '');
    } else {
        base = window.location.origin;
    }
    var path = url.replace(/^\//, '');
    return base + '/' + path;
}

function seedGalleryImagesFromInput(inputEl) {
    if (!inputEl || !inputEl.value) {
        return [];
    }
    var parts = inputEl.value.split(',');
    var out = [];
    var seen = {};
    parts.forEach(function (part) {
        var n = normalizeGalleryImagePath(part);
        if (n && !seen[n]) {
            seen[n] = true;
            out.push(part.trim());
        }
    });
    return out;
}

function standalonFileManagerModal(inputId, standAlone, previewDivId = null, imageAddBtn = null) {
    insertUrlInputId = inputId;
    previewContainerId = previewDivId;
    imgAddBtnId = imageAddBtn;
    standAloneStatus = standAlone;

    // Product gallery (standAlone=false): existing URLs live in the hidden input; file manager only
    // appended to selectedImages which was never initialized — so the hidden field was overwritten
    // with only the new pick. Seed from the input and refresh previews before opening the modal.
    if (standAlone === false && previewDivId && imageAddBtn) {
        var inputEl = resolveElement(inputId);
        selectedImages = seedGalleryImagesFromInput(inputEl);
        updatePreview();
    }

    $('#standalonFileManagerModal').modal('show');
}

function handleFileSelectionStandAlone(items) {
    if (!items || items.length === 0) {
        console.log("No files selected.");
        return;
    }

    if (!Array.isArray(items)) {
        items = [items];  // Convert to array if it's a single item
    }

    items.forEach(function (item) {
        var normalized = normalizeGalleryImagePath(item.url);
        var exists = selectedImages.some(function (existing) {
            return normalizeGalleryImagePath(existing) === normalized;
        });
        if (!exists) {
            selectedImages.push(item.url);
        }
    });

    singleImage = items[items.length - 1].url;
    updatePreview();

    closeStandAloneModal();
}

function closeStandAloneModal() {
    $('#standalonFileManagerModal').modal('hide');
    resetIframe("standalonFileManagerIframe");
}

function resetIframe(id) {
    const iframe = document.getElementById(id);
    const iframeSrc = iframe.src;  // Get current iframe source

    iframe.src = '';
    iframe.src = iframeSrc;
}

//*************** Stand Alone Button end ***************************//
