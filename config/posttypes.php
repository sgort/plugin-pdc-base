<?php

return [

	/**
	 * Examples of registering post types: http://johnbillion.com/extended-cpts/
	 */
	'pdc-item'        => [
		'args'  => [

			# Add the post type to the site's main RSS feed:
			'show_in_feed'          => false,

			# Show all posts on the post type archive:
			'archive'               => [
				'nopaging' => true
			],
			'supports'              => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
			'show_in_rest'          => true,
			'rest_base'             => 'pdc-item',
			'rest_controller_class' => '\\OWC_PDC_Base\\Core\\RestApi\\RestPdcItemPostsController',
		],
		'names' => [

			# Override the base names used for labels:
			'singular' => _x('PDC item', 'Posttype definitie', 'pdc-base'),
			'plural'   => _x('PDC items', 'Posttype definitie', 'pdc-base'),
			'slug'     => 'pdc-item'
		]
	],
	'pdc-category'    => [
		'args'  => [

			# Add the post type to the site's main RSS feed:
			'show_in_feed' => false,

			# Show all posts on the post type archive:
			'archive'      => [
				'nopaging' => true
			],
			'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail'],
			'show_in_rest' => true,
			'rest_base'    => 'pdc-thema',
		],
		'names' => [

			# Override the base names used for labels:
			'singular' => _x('PDC thema', 'Posttype definitie', 'pdc-base'),
			'plural'   => _x('PDC themas', 'Posttype definitie', 'pdc-base'),
			'slug'     => 'pdc-thema'
		]
	],
	'pdc-subcategory' => [
		'args'  => [
			# Add the post type to the site's main RSS feed:
			'show_in_feed' => false,

			# Show all posts on the post type archive:
			'archive'      => [
				'nopaging' => true
			],
			'supports' => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'],
			'show_in_rest' => true,
			'hierarchical' => true,
			'rest_base'    => 'pdc-subthema',
		],
		'names' => [

			# Override the base names used for labels:
			'singular' => _x('PDC subthema', 'Posttype definitie', 'pdc-base'),
			'plural'   => _x('PDC subthemas', 'Posttype definitie', 'pdc-base'),
			'slug'     => 'pdc-subthema'

		]
	]
];