<?php

spl_autoload_register(function ($class) {
	$spl_list = array('library', 'app');
	$i = 0;
	foreach ($spl_list as $el) {
		$prefix = $el . '\\';

		// does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) == 0) {
			$base_dir = __DIR__ . '/' . $el . '/';
			$i = 1;
			break;
		}
	}

	if ($i == 0) {
		return '';
	}

	$relative_class = substr($class, $len);

	// 兼容Linux文件找。Windows 下（/ 和 \）是通用的
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	if (file_exists($file)) {
		require $file;
	}
});
