window.CKEditorOption = {
    skin: 'moono-lisa', // or 'moono', 'moono-lisa', 'kama', etc.
    height: 400,
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserUploadUrl: '/laravel-filemanager/custom-upload?type=Files&_token=' + window.csrfToken,
    filebrowserImageUploadUrl: '/laravel-filemanager/custom-upload?type=Images&_token=' + window.csrfToken,

    language: 'en',

    on: {
        instanceReady: function () {
            this.setData('<p></p>');
        }
    },

    toolbar: [
        {
            name: 'document',
            items: ['Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates']
        },
        {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
        {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']},
        {
            name: 'forms',
            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']
        },
        '/',
        {
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
        },
        {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
        },
        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
        {
            name: 'insert',
            items: ['Image', 'Flash', 'Table', 'MediaEmbed', 'CodeBlock', 'HtmlEmbed', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
        },
        '/',
        {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
        {name: 'colors', items: ['TextColor', 'BGColor']},
        {name: 'tools', items: ['Maximize', 'ShowBlocks']},
        {name: 'about', items: ['About']}
    ],

    font_names: 'Arial/Arial, Helvetica, sans-serif;' +
        'Courier New/Courier New, Courier, monospace;' +
        'Georgia/Georgia, serif;' +
        'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
        'Tahoma/Tahoma, Geneva, sans-serif;' +
        'Times New Roman/Times New Roman, Times, serif;' +
        'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
        'Verdana/Verdana, Geneva, sans-serif',

    fontSize_sizes: '10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;',

    extraPlugins: 'uploadimage,embed,autoembed,autogrow,colorbutton,colordialog,font,justify',
    removePlugins: 'elementspath,embed,embedbase',
    resize_enabled: true,
    autoGrow_onStartup: true,
    autoGrow_minHeight: 200,
    autoGrow_maxHeight: 600,
};
