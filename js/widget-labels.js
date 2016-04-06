/* $widget-labels.js */
;( function ( $, undefined ) {
	var MCWidgetLabels = window.MCWidgetLabels || {};

	MCWidgetLabels.init = function() {
		var $document = $( document );

		$document.on( 'widget-updated', MCWidgetLabels.appendTitle );

		// loop through sidebars and set title
		$('div.widgets-sortables').children('.widget').each( function() {
			var $this = $(this);

			MCWidgetLabels.appendTitle( {}, this );
		});
	};

	MCWidgetLabels.appendTitle = function( e, widget ) {
		var title = $('input[id*="-mc_widget_label"]', widget).val() || '';

		// if title is empty, use WP's default
		if ( ! title ) {
			title = $('input[id*="-title"]', widget).val() || '';
		}

		if ( title ) {
			title = ': ' + title.replace(/<[^<>]+>/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		}

		$(widget).children('.widget-top').children('.widget-title').children()
				.children('.in-widget-title').html(title);
	};

	// fire when ready!
	$( MCWidgetLabels.init );
} )( jQuery );