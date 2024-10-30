(function() {
    tinymce.PluginManager.add( 'insert_update_time', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('insert_update_time', {
            title: 'Insert Update Time Tag',
            cmd: 'insert_update_time_class',
            image: url + '/icon.png',
        });

        // Add Command when Button Clicked
        editor.addCommand('insert_update_time_class', function(ui, v) {
            var username = document.getElementById("insert-update-time-name").value;
            var email = document.getElementById("insert-update-time-email").value;
            var time = document.getElementById("insert-update-time-time").value;
            editor.execCommand('mceInsertContent', false, '<!--more--><br><a href="mailto:'+email+'" >@'+username+'</a> Edit at ' + time);
        });
    });
})();