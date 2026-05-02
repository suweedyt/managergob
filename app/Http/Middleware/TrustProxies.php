<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
	/**
	 * The trusted proxies for this application.
	 * Allow all proxies (use with caution) — in production you can narrow this.
	 *
	 * @var array|string|null
	 */
	protected $proxies = '*';

	/**
	 * The headers that should be used to detect proxies.
	 *
	 * @var int
	 */
	protected $headers =
		\Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR |
		\Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST |
		\Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PORT |
		\Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO;
}
