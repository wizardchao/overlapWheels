<?php

function dd($arr) {
	if (!is_array($arr) || empty($arr)) {
		echo $arr;exit;
	}

	echo '<pre>';
	print_r($arr);
	echo '</pre>';
	exit;
}

function showJson() {
	$args = func_get_args();
	if (is_array($args[0])) {
		$response = $args[0];
	} else {
		$code = isset($args[0]) ? $args[0] : 200;
		$message = '';
		$data = array();

		if ($code !== 0) {
			$message = isset($args[1]) ? $args[1] : $message;
		} else {
			if (isset($args[1]) && is_array($args[1])) {
				$data = $args[1] ?: $data;
				$message = $args[2] ?: $message;
			} elseif (isset($args[2]) && is_array($args[2])) {
				$data = $args[2] ?: $data;
				$message = $args[1] ?: $message;
			} else {
				$message = isset($args[1]) ? $args[1] : $data;
				$data = isset($args[2]) ? $args[2] : $message;
			}
		}

		$response = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);
	}

	if (isset($_GET['callback']) && !empty($_GET['callback'])) {
		echo htmlspecialchars($_GET['callback']) . '(' . json_encode($response) . ')';
	} else {
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	exit;
}

//模拟发送请求
function getUrlData($url, $data = NULl) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return json_decode($output, true);
}