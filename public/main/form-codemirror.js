$(document).ready(function () {

    var textareas = $('.codemirror-element');

    if (textareas.length > 0) {

        textareas.each(function () {

            let mode = $(this).data('mode');
            let height = $(this).data('height');
            let lineNumbers = $(this).data('line-numbers');

            var editor = CodeMirror.fromTextArea(this, {
                lineNumbers: lineNumbers,
                mode: mode,
                indentUnit: 4,
                indentWithTabs: false,
                matchBrackets: true,
                theme: 'default',
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                },
                hintOptions: {
                    completeSingle: false
                }
            });

            if (height) {
                editor.setSize('100%', height);
            }
        });
    }
});