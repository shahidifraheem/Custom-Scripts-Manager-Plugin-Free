/**
 * Script Editor Initialization and UI Handling
 * Manages the CodeMirror editor instance and form interactions
 * for the Custom Scripts Manager plugin.
 */
jQuery(document).ready(function ($) {
    // Initialize CodeMirror with dynamic mode switching
    if (typeof wp !== 'undefined' && wp.codeEditor) {
        // Store the editor instance for later reference
        let codeEditor;

        /**
         * Initializes or reinitializes the CodeMirror editor with specified mode
         * @param {string} mode - The editing mode ('css' or 'javascript')
         */
        function initCodeEditor(mode) {
            // Configuration settings for CodeMirror
            const editorSettings = {
                codemirror: {
                    mode: mode,               // Syntax highlighting mode
                    lineNumbers: true,        // Show line numbers
                    indentUnit: 4,            // Indentation size
                    tabSize: 4,              // Tab size
                    extraKeys: {
                        "Ctrl-Space": "autocomplete"  // Enable autocomplete
                    },
                    lineWrapping: true,      // Enable line wrapping
                }
            };

            // Clean up previous editor instance if it exists
            if (codeEditor && codeEditor.codemirror) {
                codeEditor.codemirror.toTextArea();
            }

            // Initialize new editor instance on the code_content textarea
            codeEditor = wp.codeEditor.initialize($('#code_content'), editorSettings);
        }

        // Determine initial editor mode based on current selection
        const initialMode = $('#code_type').val() === 'css' ? 'css' : 'javascript';
        initCodeEditor(initialMode);

        // Handle code type changes (CSS/JS toggle)
        $('#code_type').on('change', function () {
            const newMode = $(this).val() === 'css' ? 'css' : 'javascript';
            initCodeEditor(newMode);
        });
    }

    /**
     * Handles scope toggle between global and page-specific scripts
     * Shows/hides the target pages selector based on scope selection
     */
    $('#is_global').change(function () {
        if ($(this).val() === '0') {
            // Show target pages selector when scope is "Specific Pages"
            $('#target-pages-field').show();
        } else {
            // Hide target pages selector when scope is "Global"
            $('#target-pages-field').hide();
        }
    });
});