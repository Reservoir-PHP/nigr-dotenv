<?php

namespace Nigr\Dotenv;

use RuntimeException;

class Dotenv
{
	public function parse(string $filePath = ".env"): void
	{
		if (!file_exists($filePath)) {
			throw new RuntimeException("Файл $filePath не найден");
		}

		$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		foreach ($lines as $line) {
			$line = trim($line);


			if (str_starts_with(trim($line), '#') || $line === "") {
				continue;
			}

			$keyValue = explode('=', $line, 2);

			if (count($keyValue) !== 2) {
				continue;
			}

			if ($keyValue[0] === '' || $keyValue[1] === '') {
				continue;
			}

			$key = trim($keyValue[0], " \"'");
			$value = trim($keyValue[1], " \"'");

			$_ENV[$key] = $value;
			$_SERVER[$key] = $value;
			putenv("$key=$value");
		}
	}
}
