/*------------------------------------*\
    ::Button Shortcode tinymce Plugin
\*------------------------------------*/
(function () {
    tinymce.PluginManager.add('custom_mce_button1', function(editor, url) {
        editor.addButton('custom_mce_button1', {
            icon: false,
            text: '[SVG]',
            onclick: function (e) {
                editor.windowManager.open({
                    title: 'Add Button Shortcode',
                    body: [
                        {
                            type: 'textbox',
                            name: 'bold',
                            placeholder: 'Bolded Text',
                            multiline: false,
                            minHeight: 25,
                            minWidth: 300,
                        },
                        {
                            type: 'textbox',
                            name: 'unbold',
                            placeholder: 'Unbolded Text',
                            multiline: false,
                            minHeight: 25,
                            minWidth: 300,
                        },
                        {
                            type: 'textbox',
                            name: 'link',
                            placeholder: 'Link (e.g. http://example.com)',
                            classes: 'button-link-for-shortcode',
                            multiline: false,
                        },
                        {
                            type: 'checkbox',
                            classes: 'button-target-for-shortcode',
                            name: 'target',
                            checked: false,
                            text: 'Open link in a new tab'
                        },
                        {
                            type: 'checkbox',
                            name: 'back',
                            checked: false,
                            text: 'Is back button (ignores link URL value if so).'
                        },
                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[button bold="' + e.data.bold + '" unbold="' + e.data.unbold + '" link="' + (!e.data.back ? e.data.link : 'n/a') + '" tab="' + e.data.target + '" back="' + e.data.back + '"]');
                    }
                });
            }
        });
    });
})();
