<?php

return array(

	'url_prefix' => '/sw-admin',
	'admin_middleware' => 'auth:admin',

	/********* create an create_admin_menu function in model and add that model in this array to reflect in admin menu ***********/
	'menu' => [
		'items'=>[
			'pages'=>[
				'title' => 'Pages',
				'identifier' => 'pages',
				'model' => 'App\Models\Page',
			],
			'frontend_pages'=>[
				'title' => 'Internal Pages',
				'identifier' => 'frontend_pages',
				'model' => 'App\Models\FrontendPage',
			],
			'services'=>[
				'title' => 'Services',
				'identifier' => 'services',
				'model' => 'App\Models\Service',
			]
		],
		'positions'=>['Main Menu', 'Footer Menu'],
	],

	'category_types' => ['Blog', 'Event'],

	'services' => [
		'sections' => true,
	],

	'fields' => [
		'pages' => ['title', 'parent_id', 'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 
		'og_title', 'og_description', 'extra_js', 'faq', 'featured_image_id', 'banner_image_id', 'og_image_id', 'seo', 'extra_data'],

		'team' => ['department_id', 'title', 'designation', 'short_description', 'content', 'seo', 'extra_data', 'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 
		'og_title', 'og_description', 'extra_js', 'priority', 'is_featured', 'featured_image_id', 'banner_image_id', 'og_image_id', 'facebook_link', 'twitter_link', 
		'linkedin_link', 'instagram_link', 'youtube_link', 'priority', 'social_media_links'],

		'services' => ['title', 'content', 'parent_id', 'featured_image_id', 'banner_image_id', 'browser_title', 
		'meta_description', 'meta_keywords', 'top_description', 'bottom_description', 'og_title', 
		'og_description', 'og_image_id', 'extra_css', 'extra_js', 'priority', 'is_featured', 'faq', 'seo', 'extra_data'],

		'jobs' => ['departments_id', 'title', 'job_location', 'short_description', 'responsibilities', 'eligibility', 'skills', 'how_to_aply', 
		'last_application_date', 'featured_image_id', 'banner_image_id', 'browser_title', 'meta_description', 'meta_keywords', 
		'og_title', 'og_description', 'og_image_id', 'extra_css', 'top_description', 'bottom_description', 'extra_js', 
		'priority', 'is_featured', 'seo', 'extra_data', 'content'],

		'events' => ['title', 'start_time', 'end_time', 'location', 'fees', 'short_description', 'content', 
		'featured_image_id', 'banner_image_id', 'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 
		'og_title', 'og_description', 'og_image_id', 'extra_js', 'category_id', 'is_featured', 'faq', 'priority', 'seo', 'extra_data', 'media'],
		
		'partners' => ['title', 'short_description', 'description', 'featured_image_id', 'banner_image_id', 'browser_title', 
		'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image_id', 'bottom_description', 
		'extra_js', 'status', 'priority', 'is_featured', 'seo', 'extra_data'],

		'testimonials' => ['is_featured', 'has_video_testimonials'],

		'categories' => ['category_type', 'short_description', 'description', 'title', 'parent_id', 'banner_image_id', 'featured_image_id', 
        'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 'og_title', 'og_description', 'og_image_id', 'extra_js', 'status', 
		'priority', 'is_featured', 'seo', 'extra_data', 'faq'],

		'tags' => ['title', 'description', 'featured_image_id', 'banner_image_id', 'browser_title', 
		'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image_id', 'bottom_description', 
		'extra_js', 'status', 'seo', 'extra_data'],

		'blogs' => ['title', 'short_description', 'content', 'parent_id', 'featured_image_id', 'banner_image_id', 
		'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 'og_title', 'og_description', 'og_image_id', 
		'extra_js', 'category_id', 'is_featured', 'status', 'priority', 'published_on', 'published_by', 'seo', 'extra_data', 'faq', 'tags'],

		'galleries' => ['title', 'short_description', 'content', 
		'featured_image_id', 'banner_image_id', 'browser_title', 'meta_description', 'meta_keywords', 'bottom_description', 
		'og_title', 'og_description', 'og_image_id', 'extra_js', 'category_id', 'is_featured', 'faq', 'priority', 'seo', 'extra_data', 'media'],
	]

);
