jQuery(document).ready(function($) {
	jQuery('#was-editor').fadeTo('fast',0.5,function(){
	});
	jQuery('#was-editor select').prop('disabled',true);
	jQuery('#was-editor :submit').prop('disabled',true);
	jQuery('#list-entity a.btn').bind('click',false);
	jQuery('#was-nav').fadeTo('fast',0.5,function(){
	});
	jQuery('#was-nav a').bind('click',false);
});
