<?php

namespace app\Cache;
use Predis\Client;

class RedisCache {
	public static function getConfig() {
		return new Client(require BASE_PATH . '/config/redis.php');
	}

	public static function set($key, $value, $time = '') {
		$redis = self::getConfig();
		return $redis->set($key, $valu, $time);
	}

	public static function get($key) {
		$redis = self::getConfig();
		return $redis->get($key);
	}
}