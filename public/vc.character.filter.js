/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function($){

	var UI = {
		init: function(){
			UI.initWidgets()
		},
		initWidgets : function(ctx){
			UI.widgets = {
				form : $(".dc-character-form")
			}
			if(ctx){
				var parent = ctx.closest(".dc-character-loop")
				UI.widgets.preloader = parent.find(".dc-character-preloader")
				UI.widgets.character_type = parent.find(".character_type")
				UI.widgets.character_search = parent.find(".character_search")
				UI.widgets.character_loop_content = parent.find(".dc-character-loop_content")
				UI.widgets.character_count = parent.find(".dc-character-count-number")
			}
		}
	};

	var MODULE = {
		load : function(){
			UI.widgets.form.submit(function(e){
				e.preventDefault()
				var ctx = jQuery(this)
				UI.initWidgets(ctx)
				UI.widgets.preloader.show()
				var character_type = UI.widgets.character_type.val()
				var character_search = UI.widgets.character_search.val()

				jQuery.ajax({
					url: ajax_script.ajaxurl,
					type: 'GET',
					data: {
						action: 'dc_character_filter',
						character_type: character_type,
						character_search: character_search
					},
					success: function( data ){
						var template = $("#custom_tpl_character")
						if( template.length ){

							var response = JSON.parse(data)
							var html_raw = _.template( template.html() )
							var compiled = html_raw({ characters : response })
							
							UI.widgets.character_loop_content.html(compiled)
							UI.widgets.character_count.html("(" + response.length + ")")
							UI.initWidgets(ctx)
							UI.widgets.preloader.hide()

						} else {
							console.error("Template not found #custom_tpl_character")
						}
					},
					error: function(data){
						console.warn(data)
					}
				});
			});
		},
		init : function(){
			MODULE.load()
		}
	}

	$(document).ready(function(){
		UI.init()
		MODULE.init()
	})

})(jQuery);
