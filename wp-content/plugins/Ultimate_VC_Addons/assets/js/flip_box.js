jQuery(window).load(function(a){
	flip_box_set_auto_height();
});
jQuery(window).resize(function(a){
	flip_box_set_auto_height();
});
jQuery(document).ready(function(a) {
	flip_box_set_auto_height();
});
function flip_box_set_auto_height() {
	jQuery('.flip-box').each(function(index, value) {
		var WW = jQuery(window).width() || '';
		if(WW!='') {
			if(WW>=768) {
				var h = jQuery(this).attr('data-min-height') || '';
				if(h!='') {
					jQuery(this).css('height', h);
				}
			} else {
				jQuery(this).css('height', 'initial');
			}
		}
	});
}