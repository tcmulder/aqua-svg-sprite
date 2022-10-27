/**
 * SVG Button Gutenberg Block
 */
( function( blocks, element, components, editor, apiFetch, i18n ) {

	// "destructure" the variables
	var el = element.createElement,
	useState = element.useState,
	registerBlockType = blocks.registerBlockType,
	ServerSideRender = components.ServerSideRender,
	InspectorControls = editor.InspectorControls,
	PanelBody = components.PanelBody,
	SelectControl = components.SelectControl,
	TextControl = components.TextControl,
	__ = i18n.__;

	// create a custom icon
	var icon = el( 'svg', { width: 20, height: 20, viewBox: '-3 58 968 884' },
		el( 'g', { fill: 'currentColor', fillRule: 'nonzero' },
			[
				el( 'path', { d: 'M549.3 938.6c-53.8 5.3-99 2.8-146.5-8-37.6-8.7-72.7-21.6-107.2-40-57.4-30.3-108.2-73.3-147.2-125-11.5-15.2-28.4-41.4-34-52.7l-4-7.7c-18-33-33.3-76-41.5-116l-1.6-7 4-4.2 5.6-5c6.8-7 17.2-17 17.6-17 .3.2 2 8.3 4 18 7 41 19.6 77.6 39 115.2 16.3 31.8 38.3 63 62.7 89.5 36 38 79.4 70 126.5 92 61 28.7 127.2 41.5 193.7 38 55-3 107.3-16 156.5-39.2 84.6-39.7 154.8-111 194.8-197.4 7-15 11.7-27 17.2-43.7 7.3-22 11.6-39.6 15-60.6 7-41.7 7.7-80.7 2-121.7-1.3-9.6-2-17.8-2-18 .5-.3 13.4 6 22 10.7 1 .3 4.2 2 7 3.2l5.2 2.5 1 7.2c4.4 40.8 3.2 86-3.6 123l-1.4 8.4c-2 12.6-10 43-16.2 61-9.4 27-21.5 53.3-36.2 78.5-26.3 45-61.2 86.2-101.4 119.3-38 31.3-81.6 56-127.8 72.8-16.3 5.6-26.3 8.8-46 14-9 2.3-48 9-56.7 10l-.5-.2-.2.2z' } ),
				el( 'path', { d: 'M560.2 893.6c-2.5-3.4-5.6-8.8-14.3-25-7.8-14-13.6-21.7-22-29.6-21.3-20-56-30-107-31-18.6-.5-28-.8-38-2-44.7-4.6-80.8-17.3-109-38.4-13.4-10.3-23.7-20.5-32-32.7-14.5-20.6-22.3-41-25.3-66.6-2.4-20-.7-42 4.7-62 1-4 1.6-7.2 1.4-7.3-.2-.3-1.7-.7-3.3-1-4.8-1-16.6-4-17.5-5-.8-.4-.3-2 2.5-8l21.4-47.4c2.2-5.4 7-16 10.4-23.5l6.2-14 18.7-13c12.4-9 18.7-13 19-12.7.4 1 6 19.5 9.7 33.5l2.6 9-.5 7-5 55.7-2.6 27c-.2 3.2-.6 6-1 6.6-.5 1-1 1-12.5-2l-9.6-2.5-.7 2c-1.3 5.7-3.3 17.4-4 25.8-2 23 1.7 43.7 10.4 59 7.7 14 18.8 25.2 32 32.7 30.3 17.5 71.3 24 130 21 15.5-1 28.5-6 39-15 3.2-2.8 9-11.3 11.4-16.2 4.6-10.4 7.5-23.3 8.5-39.8l.4-7.8-15-95.8c-9.5-59.8-15.7-99.4-17-104.8-3.2-13.3-5.4-17-11.7-19.3-2.5-1-3-1-16 1.6-26.4 4.6-56.6 9-63.4 9.4-34.7 1.2-61.7-3-90.3-13.6-18-6.7-39.4-18.6-56.7-31.4l-6.5-5-2 2.3c-1 1-4 5-6.7 8-14.2 17.8-37.5 43.5-51 56L128 495.5s-3.4 2.6-7 5.7c-8.4 7-20.8 16.2-39 29.4-7.7 5.4-15.7 11.3-17.6 12.8L61 546l-4-3c-5.3-4-7.6-6.3-13.2-12.7-4.7-5.3-14.5-19-19.4-27C14 486.8-3 453.6-2.3 452.3c.4-.3 3.3-.7 6.6-.8 3.3-.2 11.3-1.2 18-2.2 42-6.6 87.3-26.6 130.5-57.5l12.6-9.5c9-7.2 12-9.4 17.6-14.3 3.6-3 9.8-8.6 13.7-12.3 4-3.6 7.3-6.6 7.5-6.6.2 0 2 1.5 3.6 3.5 15.4 15.8 36 31.4 55.2 41.5 12 6.5 22.6 10.6 36 15 14.8 4.5 27.6 7 41.8 8.2 13.5 1 22.4.8 37.3-1 23-3 40.5-9.5 56.6-20.7l6.4-4.5-2.7-14.5-2.6-14.6-8 .7-46 4.2c-20.5 2-44.5 4-53 5l-16.7 1c-1-.3-8.6-48.3-7.7-48.8.7-.3 36.6-8.6 90-20.5l27.8-6.3 3.6-1-4.3-26.8-4.2-2c-9-4-19.3-11.7-26.2-19.3-8-8.6-12.7-16.2-16.5-26.4-5-13-6.5-24-5.3-38 2.2-26.5 18.2-51.4 41.7-65 9.2-5.2 16-7.5 28.3-9.5 12.4-2 19.4-1.8 29.8.4 26.6 5.5 49.5 24.2 59.8 48.7 5.3 12.7 7.3 23.7 6.6 37.5-.5 11-2.6 19.6-7.3 30.3-4.4 9.4-12 20-19 26.5l-3.6 3.3 4.2 27 3.6-.3c2-.3 14.8-1.3 28.5-2.5 54.3-5.2 91-8.3 91.8-8.5.6.2 1.7 5.8 4.7 24.4 3 18.8 3.6 24.2 3 24.5l-16 3.6c-8.3 1.7-32 7-52.2 11.7l-44.7 10-7.8 1.8 2 14.7 2.3 14.6 7.5 2.3c18.6 5.7 37.2 6.3 60 2 14.5-2.5 23-5 35.7-10.4 24.2-10.3 47.4-26.2 67-46 15-15.5 29.8-36.8 39.5-57 1-2 2.2-4 2.4-4l9 4 16.7 7.6c7 2.6 10.3 4 21.2 8l15 5c50.4 16 99.7 21 141.7 14.3 6.6-1 14.5-2.5 17.6-3.3 3.3-1 6.2-1.6 6.5-1.4 1 1-4.7 38-9.2 57.4-2.2 9-7.4 25-10.2 31.6-3.3 7.8-5 10.6-8.6 16l-3 4.3-4.3-1.4c-2.2-1-11.5-4.3-20.7-7-21.3-6.8-36-12-46-16l-8.6-3.3c-1-.4-17.2-7.5-24.5-11-17-7.7-47-25-66-37-3.5-2.6-7.6-5-9-5.8l-3.3-1-5 7c-12.4 17.3-28.8 35.2-43.8 47-24 19-48.5 31.2-81.8 40.7-6.8 2-36.7 6.7-63 10.4-13.3 1.7-14 2-16 3.6-5.6 4-6.5 8.3-5.3 21.8.4 5.6 6.5 45.3 16 105l15.6 95.8 3 7.2c8.3 21 17 34 28 42.3 10.5 7.8 22.7 10.7 37.3 9.4 13.6-1 49-12.4 72.3-23 40.5-18 66-41.7 75-69 5.7-18.6 5.6-34.2-.8-56.7-2.5-9.3-11.2-28.3-16.8-37l-1.3-1.8-8 5.3c-10 6.4-10.5 6.7-11.4 5.8-.5-.3-1.7-3-3-5.7-1-3-5.8-14-10.6-25l-22.3-51.7-2.6-6.5-.4-9.5c-.6-14.6-1-34-1-35 .2-.5 7.4 1.5 22 6.2l21.8 6.8 10.2 11.3 17.2 19 34.8 38.5c4.6 5 5.5 6.3 5 7.2-.7 1-11 7.6-15.3 10-1.2.7-2.4 1.4-2.4 2-.2 0 1.5 3 3.5 6.4 11.7 18 20 38 24 58 5 25 4 47-3.2 70.7-9.4 30.5-31.7 60-62.2 82.5-9.8 7.3-24 16-35.7 22-14.5 7-27 12.2-50.5 20.2-48 16.7-78 37-92 62.6-5.6 10.2-8.7 19.6-11.8 35-4.3 23.8-6.3 32-7.7 32.3-.2 0-1.7-1.4-3-3.4l.2.5zm-91.6-660.4c8.8-3.4 18-11.4 22.7-19.6 16.3-28.5-1-64.4-33.3-69.4-5-.8-6.6-.7-14 .4-7.2 1.2-8.7 1.6-13 3.8-21.5 10.5-31 35-23 57.5 3.7 10 12 20 21 24.5 5.4 2.8 13.7 5.4 19 5.5 5.4.5 15.4-1 20.5-3v.2z' } ),
				el( 'path', { d: 'M389 612.3c-.5-.6-.4-6 .4-30.2.2-11.3.8-27 1.3-52.2l.8-26.8.4-16.7v-3l4.3-1.3c2.6-.6 11.8-2.2 20.7-3.7l16.4-2.6.8 1.3c.5.7 2.6 10 5 20.4 2.2 10.8 8 38 13 60.7 5 23 9 42 8.8 42.4-.8 1-71.3 12.8-72 12zm199.6-31.5c-10.3 1.6-19.3 2.6-19.6 2.3-.3 0-2.4-19.2-4.8-42.6-2.2-23-5-50.8-6.2-61.8-1-10.6-2-20-1.6-21l.2-1.4 16-2.5c9-1.4 18.4-2.6 21-2.8l4.7-.4 1 3 5.4 15.6 9 25.4 17.7 49.4c8.2 22.5 10 27.8 9.6 28.6 0 .4-6.3 1.6-16.7 3l-35.6 5.5v-.2zm-230.3 33c-21 .4-54.5 1.3-58.3 1.2-1.5.3-3.2.3-3.6.2-.8-.2.5-7.4 8.7-54l4-22.6 3.8-21.8c2-9.5 3.8-21 4.7-26 .8-4.8 1.6-9 1.8-9.3.2 0 7 0 15.2.4l20.7.6c3.2.2 6 .6 6.2 1 .2.4 3 24 6.2 52L375 600c.8 7.8 1.2 12.6.8 13-.4.4-7.7.8-17.6 1v-.2zm295.3-45c-.4-.4-1.7-6-3-13l-12.7-63c-5.8-27.8-10.3-51-10.4-51.4.2-.5 2.8-1.7 5.6-3l19.2-7c7.8-3 14.3-5.3 14.6-5 .3.2 2.4 4 4.6 8.4l12.4 23 10.2 19.8c1.4 2.3 6.2 11.5 11 20.5 19.8 37 25.5 48 25 48.6 0 0-1.8.7-4 1.2l-21 6.4-33.2 10c-8.7 2.4-16.2 4.8-16.7 5-.6 0-1.3.2-1.6-.2v-.2zm-484.2 11.6c-6-3-15.8-8.7-22-12.2L128 557.5c-10-5.4-20.2-11.5-20.3-12 0-.2 3.4-4 7.4-8.5l26.4-28.2 9-10c13-14.2 39.3-42.6 41.2-44.3 3-3 5.6-6 11.2-12.3l5.2-5.6 1.7 1c1 .7 8 6.3 15.5 12.5l14 11.4-2 4-13 28-16.3 35-16 34.2c-5.8 12.7-11 23.3-11 23.4-.6 0-5.6-2.5-11.6-6v.4zm660-98.5l-16.2-17.7-24.4-26.2-27.7-29.6-21-22.7-3-3.3 10-15 11-16.5 1-1.8 6.7 3.8 14.4 8.2c2.6 1.2 36.3 20 53 29.5l12 6.5c2.4 1.3 19.2 10.7 33.6 19 5.3 2.7 9.6 5.5 9.7 5.7 0 .2-8 9-16 17.5l-15 16c-18.2 20-25.5 27.7-26 27.7-.2 0-1-.5-2-1.4h.2zM68 414c-.5-1 6.6-29.5 11.7-45.5C87 345 92 332.7 104.5 306c2-4.6 11.6-22.6 14.3-26.8 20.8-34.7 42.6-63.3 67-88.2 3.7-3.6 3.5-3.6 10.8-10.4 53.7-51.6 120-88.6 191.2-107 65-16.7 131.6-19.2 195.3-7 37 7 73.7 19 107.8 35.6l13.4 6.6c31 16.2 60.6 36.7 91 63.2 3.7 3.5 18.5 17.6 22 21.3 28.7 31 45.8 53.2 65.8 87.3 1.4 2.2 2 4 1.7 4.3-.8 1-22.3-1.8-34.2-5l-5.8-1.3-3-4.5c-27.5-41.3-62-77-101.4-105.7-17-12.2-32-21.7-48.2-30l-10.7-5.7c-1.3-1-6.3-3-11.3-5.4-61.8-28-127-40-195.6-35.8-16 1-59 7.8-74.7 11.8-48 12.6-90 31.6-129.7 58.6-18.7 12.7-43 32.4-55 44.6-1 1.2-4.8 4.8-8.3 8.6-45.4 46-80 105.6-98.6 168.8-2.2 8-3.6 12-4.5 12.7-3.4 2.6-14 8.3-22.8 12.7C71 414 69 414.8 68 414z' } )
			]
		)
	);

	// register the svg-use block
	registerBlockType( 'aqua-svg-sprite/svg-use', {
		// set standard properties
		title: __( 'SVG Sprite', 'aqua-svg-sprite' ),
		icon: icon,
		category: 'common',
		attributes: {
			// slug identifying svg slug id and sprite name (comma separated)
			slug: {
				type: 'string',
				default: '',
			},
			// svg properties to use
			properties: {
				type: 'string',
				default: 'width=50,height=50',
			},
		},
		// dynamic: return null on save
		save: function() {
			return null
		},
		// for editing
		edit: function( props ) {

			// function to change the slug
			function onChangeSelectField( newSlug ) {
				props.setAttributes( { slug: newSlug } );
			}
			// function to change properties
			function onChangeTextField( newProperties ) {
				props.setAttributes( { properties: newProperties } );
			}
			// create local state to house svg options
			var listStateVariable = useState( null );
			var list = listStateVariable[ 0 ];
			var setList = listStateVariable[ 1 ];
			// get all the svg options
			function getList() {
				apiFetch( { path: 'aqua-svg-sprite/v1/svg' } )
					.then( function( data ) {
						// create an array containing values (comma separated slug/sprite slugs) and title as display
						var posts = data.map( function ( post ) {
							return { value: post.slug + ',' + post.sprite, label: post.title + ' (' + post.sprite + ' sprite)' }
						} );
						// sort alphabetically
						posts.sort( function ( a, b ) { ( a.label > b.label ) ? 1 : ( ( b.label > a.label ) ? -1 : 0 ) } );
						// set list of svg options in local state
						setList( posts );
						// pick the first svg if nothing has been chosen yet
						if ( '' === props.attributes.slug ) {
							props.setAttributes( { slug: posts[ 0 ].value } );
						}
					} );
			}
			// get the svg options list if we don't have them already
			if ( ! list ) {
				getList();
			}

			return (
				// output the controls (select box) for choosing the svg sprite element to use
				[ el(
					InspectorControls,
					null,
					[
						el(
							PanelBody,
							null,
							[
								el(
									SelectControl,
									{
										label: __( 'Select Sprite Image', 'aqua-svg-sprite' ),
										value: props.attributes.slug,
										options: list,
										onChange: onChangeSelectField
									}
								),
								el(
									TextControl,
									{
										label: __( 'Advanced Properties', 'aqua-svg-sprite' ),
										value: props.attributes.properties,
										onChange: onChangeTextField,
										help: __( 'Add SVG properties in the format "width=50,height=50"', 'aqua-svg-sprite' ),
									}
								)
							]
						)
					]
				),
				// output the server-side rendered icon
				el(ServerSideRender, {
					block: 'aqua-svg-sprite/svg-use',
					attributes: props.attributes
				} ) ]
			);
		},
	} );
// establish dependencies
}(
	window.wp.blocks,
	window.wp.element,
	window.wp.components,
	window.wp.editor,
	window.wp.apiFetch,
	window.wp.i18n,
) );
