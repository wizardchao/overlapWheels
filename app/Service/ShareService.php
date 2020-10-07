<?php

namespace app\Service;
use app\Cache\RedisCache;

class ShareService {
	const KEY_ACCESSTOKEN = 'rongchuang:access_token';
	const KEY_JSTICKECT = 'rongchuang:js_tickect';
	const EXPIRE_TIME = 3600;
	/**
	 * 初始化
	 * @Author   Chaos
	 * @DateTime 2020-10-07T13:51:24+0800
	 * @return   [type]                   [description]
	 */
	public static function init($key, $link) {
		if (empty($key) || empty($link)) {
			showJson(-1, '参数不全！');
		}

		$accessToken = self::getAccessToken();
		if (empty($accessToken)) {
			showJson(-1, '获取结果失败');
		}

		$jsTicket = self::getJsTicket($accessToken);
		$curTime = time();
		$signature = 'jsapi_ticket=' . $jsTicket . '&noncestr=' . $key . '&timestamp=' . $curTime . '&url=' . $link;
		$data = array(
			'appid' => WECHAT_APPID,
			'noncestr' => $key,
			'signature' => sha1($signature),
			'timestamp' => $curTime,
		);
		showJson(0, 'Success!', $data);
	}

/**
 * 获取accessToken
 * @Author   Chaos
 * @DateTime 2020-10-07T13:51:34+0800
 * @return   [type]                   [description]
 */
	public static function getAccessToken() {
		$accessToken = RedisCache::get(KEY_ACCESSTOKEN);
		if ($accessToken) {
			return $accessToken;
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . WECHAT_APPID . "&secret=" . WECHAT_APPSECRET;
		$re = getUrlData($url);
		if (!$re || !$re['access_token']) {
			showJson(-10, '获取accessToken失败！');
		}

		RedisCache::set(KEY_ACCESSTOKEN, $re['access_token'], EXPIRE_TIME);
		return $re['access_token'];
	}

/**
 * 获取jsTicket
 * @Author   Chaos
 * @DateTime 2020-10-07T14:36:48+0800
 * @return   [type]                   [description]
 */
	public static function getJsTicket($accessToken) {

		$jsTicket = RedisCache::get(KEY_JSTICKECT);
		if ($jsTicket) {
			return $jsTicket;
		}

		$ticket_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $accessToken . "&type=jsapi";
		$re = getUrlData($url);
		if (!$re || !$re['ticket']) {
			showJson(-11, '获取ticket失败！');
		}
		RedisCache::set(KEY_JSTICKECT, $re['ticket'], EXPIRE_TIME);
		return $re['ticket'];
	}
}