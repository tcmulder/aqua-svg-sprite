/**
 * SVG Button Shortcode tinymce Plugin
 */
(function () {
	tinymce.PluginManager.add('aqua_svg_sprite_button', function(editor, url) {
		var svgNamesGroups = function() {
			var spriteArray = [];
			var arrayLength = aquaSVGSpriteShortcode.length;
			for ( var i = 0; i < arrayLength; i++ ) {
				var svgName 	= aquaSVGSpriteShortcode[i]['svg']['name'];
				var spriteName 	= aquaSVGSpriteShortcode[i]['sprite']['name']
				var svgSlug 	= aquaSVGSpriteShortcode[i]['svg']['slug']
				var spriteSlug 	= aquaSVGSpriteShortcode[i]['sprite']['slug']
				spriteArray.push( {
					text: 	svgName + ' (' + spriteName + ')',
					value: 	svgSlug + ',' + spriteSlug,
				} );
			}
			spriteArray.sort(function(a,b) {return (a.text > b.text) ? 1 : ((b.text > a.text) ? -1 : 0);} );
			return spriteArray;
		}
		editor.addButton('aqua_svg_sprite_button', {
			icon: false,
			text: '[SVG]',
			onclick: function (e) {
				editor.windowManager.open({
					title: 'Add SVG Image',
					body: [
						{
							type: 'listbox',
							name: 'svg',
							values: svgNamesGroups(),
							minHeight: 25,
							minWidth: 300,
						},
						{
							type: 'textbox',
							name: 'width',
							placeholder: 'Width (optional)',
							multiline: false,
							minHeight: 25,
							minWidth: 300,
						},
						{
							type: 'textbox',
							name: 'height',
							placeholder: 'Height (optional)',
							multiline: false,
							minHeight: 25,
							minWidth: 300,
						},
						{
							type: 'textbox',
							name: 'attr',
							placeholder: 'HTML Attributes (advanced, optional)',
							multiline: false,
							multiline: false,
							minHeight: 25,
							minWidth: 300,
						},
					],
					onsubmit: function(e) {
						var slug = e.data.svg.split(',')[0] ;
						var sprite = e.data.svg.split(',')[1];
						var attr = '';
						attr += ( e.data.width 	? 'width=' + e.data.width + ',' : '' );
						attr += ( e.data.height ? 'height=' + e.data.height + ',' : '' );
						attr += ( e.data.attr 	? e.data.attr : '' );
						attr = attr.replace(/,$/g, '');
						editor.insertContent(
							'[aqua-svg'
								+ ' slug="' + slug + '"'
								+ ' sprite="' + sprite + '"'
								+ ( attr ? ' attr="' + attr + '"' : '' )
							+ ']'
						);
					}
				});
			}
		});
	});
})();
