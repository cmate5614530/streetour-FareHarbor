(function ($) {
    let editor, $modal, currentObj;

    $.fn.booklyEditable.types.ace = function (obj) {
        if (!editor) {
            construct();
        }

        // Click on editable field.
        obj.$el.on('click', function (e) {
            e.preventDefault();

            currentObj = obj;

            $('.modal-title', $modal).text(obj.$el.data('title') || obj.opts.l10n.edit);

            // Set content and codes
            editor.booklyAceEditor('setValue', obj.values[obj.option]);
            editor.booklyAceEditor('setCodes', obj.$el.data('codes'));

            $modal.booklyModal('show');
        });
    };

    function construct() {
        editor = $('#bookly-ace-editor').booklyAceEditor();
        $modal = $('#bookly-editable-modal');

        $modal.on('shown.bs.modal', function () {
            editor.booklyAceEditor('focus');
        });
        $modal.on('hide.bs.modal', function () {
            editor.booklyAceEditor('setValue', '');
        });

        // "Save" button
        $('#bookly-ace-save', $modal).on('click', function () {
            currentObj.values[currentObj.option] = editor.booklyAceEditor('getValue');
            currentObj.update();

            $modal.booklyModal('hide');
        });
    }
})(jQuery);