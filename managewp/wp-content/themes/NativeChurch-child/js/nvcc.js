jQuery(document).ready(function(){
	//sidebarHeaders();
});

function sidebarHeaders(){
	jQuery('h3.widgettitle').each(function(){
		var rawText = jQuery(this).text();
		var headerArray = rawText.split(" ");
		var numWords = headerArray.length - 1;
		jQuery(this).empty();

		for(i = 0; i <= numWords; i++){
			var newTitle = '<span class="word">'+headerArray[i]+' </span>';
			jQuery(this).append(newTitle);
		}
	})
}