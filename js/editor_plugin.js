function valideratext(text) {
 
    return "&lt;p class='xyz' style='font-size:36px' &gt;"+text+"&lt;/p&gt;";
 
}
 
(function() {
 
    tinymce.create('tinymce.plugins.valideratext', {
 
        init : function(ed, url){
 
            ed.addButton('valideratext', {
                title : 'Insert Valideratext',
                onclick : function() {

						var txt = ed.getContent();
                    var sData;
                    var sDomain = ajaxurl + "?action=valideratext";
                    sData = "<form name='loginform' id='loginform' action='" + sDomain;
                    sData = sData + "' method='post' charset='UTF-8' accept-charset='UTF-8'>";
                    sData = sData + "<input type='submit' name='wp-submit' id='wp-submit' value='valideratext' />";
                    sData = sData + "<textarea name='validatetext'>" + txt  + "</textarea>";
                    sData = sData + "</form>";
                    sData = sData + "<script type='text/javascript'>";
                    sData = sData + "document.cookie='wordpress_test_cookie=home; expires=Fri, 11 Jul 2009 05:23:14 +0000; path=/';";
                    sData = sData + "document.loginform.submit();</sc" + "ript>";
                    OpenWindow=window.open("", "_blank","top=100, left=100, height=730, width=850");
                    OpenWindow.document.write(sData);
                    OpenWindow.document.close()
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