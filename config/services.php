<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Mailgun, Postmark, AWS and more. This file provides the de facto
	| location for this type of information, allowing packages to have
	| a conventional file to locate the various service credentials.
	|
	*/

	'postmark' => [
		'token' => env('POSTMARK_TOKEN'),
	],

	'ses' => [
		'key' => env('AWS_ACCESS_KEY_ID'),
		'secret' => env('AWS_SECRET_ACCESS_KEY'),
		'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	],

	'resend' => [
		'key' => env('RESEND_KEY'),
	],

	'slack' => [
		'notifications' => [
			'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
			'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
		],
	],

	'yandex' => [
		'oauth_token' => env('YANDEX_OAUTH_TOKEN'),
		'org_id' => env('YANDEX_ORG_ID')
	],

	'telegram' => [
		'bot_token' => env('TELEGRAM_BOT_TOKEN'),
		'admin_chat_id' => env('TELEGRAM_ADMIN_CHAT_ID'),
		'webhook_url' => '/telegram/webhook',
		'webhook_secret_token' => env('TELEGRAM_WEBHOOK_SECRET_TOKEN')
	],

	'telegram-bot-api' => [
		'token' => env('TELEGRAM_BOT_TOKEN')
	]
];
