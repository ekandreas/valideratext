function valideratext(text) {
 
    return "&lt;p class='xyz' style='font-size:36px' &gt;"+text+"&lt;/p&gt;";
 
}
 
(function() {
 
    tinymce.create('tinymce.plugins.valideratext', {
 
        init : function(ed, url){
 
            ed.addButton('valideratext', {
                title : 'Öppna Valideratext',
                onclick : function() {
									var height = screen.availHeight - 80;
									var width = 850;
									var left = screen.availWidth - width - 10;
									var win = window.open(ajaxurl + "?action=valideratext&ed=" + ed.name, 'WordPress02ValidateWindow', 'top=0,left=' + left + ',width=' + width + ',height=' + height + ',toolbar=0,location=0,directories=0,status=0,menuBar=0,scrollBars=1,resizable=1');
								},
            image: url + "/../images/valideratext.png"
		        });

    }
});
 
tinymce.PluginManager.add('valideratext', tinymce.plugins.valideratext); })();

// Replaces all instances of the given substring.
String.prototype.replaceAll = function(
    strTarget, // The substring you want to replace
    strSubString // The string you want to replace in.
    ){
    var strText = this;
    var intIndexOfMatch = strText.indexOf( strTarget );

    // Keep looping while an instance of the target string
    // still exists in the string.
    while (intIndexOfMatch != -1){
    // Relace out the current instance.
    strText = strText.replace( strTarget, strSubString )

    // Get the index of any next matching substring.
    intIndexOfMatch = strText.indexOf( strTarget );
    }

    // Return the updated string with ALL the target strings
    // replaced out with the new substring.
    return( strText );
}