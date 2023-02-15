/**
** wpam link copy URL jquery
**/


function copyToClipboard($id) {
  var copyText = document.getElementById("myInput"+$id);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  
  var tooltip = document.getElementById("myTooltip"+$id);
  tooltip.innerHTML = "Copied";
}

function outFunc($id) {
  var tooltip = document.getElementById("myTooltip"+$id);
  tooltip.innerHTML = "Copy URL";
}

jQuery(document).ready(function(){
	jQuery(".magnet-tooltip span .fa-info-circle").on("click",function(){
		jQuery(this).parents(".magnet-tooltip").find(".magnet-content").toggle();
	})
	jQuery(".magnet-tooltip .close").on("click",function(){
		jQuery(this).parents(".magnet-tooltip").find(".magnet-content").toggle();
	});
})