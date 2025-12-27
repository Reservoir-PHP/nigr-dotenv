<?php


use Nigr\Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;


class DotenvTest extends TestCase
{
	private string $testDir;

	protected function setUp(): void
	{
		$this->testDir = __DIR__ . '\env\\';
		$this->envFile = '.envTest';
		if (!file_exists($this->testDir)) {
			mkdir($this->testDir, 0777, true); // создаем директорию для тестового env файла
		}
	}

	protected function tearDown(): void
	{
		foreach ($_ENV as $key => $value) { // Очищаем переменные окружения
			if (str_starts_with($key, 'TEST_')) {
				unset($_ENV[$key]);
				unset($_SERVER[$key]);
				putenv("$key");
			}
		}

		if (file_exists($this->testDir . $this->envFile)) {
			unlink($this->testDir . ".envTest"); // удаляем файл .envTest
		}
	}

	public function preTest($content): void
	{
		$envFile = $this->testDir . $this->envFile;

		file_put_contents($envFile, $content);
		$dotenv = new Dotenv();
		$dotenv->parse($envFile);
	}

	public function testValidEnvFile(): void
	{
		$test1 = "TEST_DB_HOST=localhost";
		$test2 = "TEST_DB_PORT=3306";
		$test3 = "TEST_DEBUG=true";
		$test4 = "TEST_HOST=https://example.com/path";
		$test5 = "TEST_LONG_STRING=long string";
		$envContent = "$test1\n$test2\n$test3\n$test4\n$test5";

		$this->preTest($envContent);

		$this->assertEquals('3306', $_ENV['TEST_DB_PORT']);
		$this->assertEquals('true', $_ENV['TEST_DEBUG']);
		$this->assertEquals('https://example.com/path', $_ENV['TEST_HOST']);
		$this->assertEquals('long string', $_ENV['TEST_LONG_STRING']);
		$this->assertEquals('localhost', $_ENV['TEST_DB_HOST']);
		$this->assertEquals('localhost', $_SERVER['TEST_DB_HOST']);
		$this->assertEquals('localhost', getenv('TEST_DB_HOST'));
		$this->assertEquals('long string', getenv('TEST_LONG_STRING'));
	}

	public function testHandlesQuotesAndSpaces(): void
	{
		$test1 = "TEST_ANOTHER=simple";
		$test2 = " \"TEST_KEY \" = \" value with spaces \" ";
		$envContent = "$test1\n$test2";

		$this->preTest($envContent);

		$this->assertEquals('value with spaces', $_ENV['TEST_KEY']);
		$this->assertEquals('simple', $_ENV['TEST_ANOTHER']);
	}

	public function testIgnoresCommentsAndInvalidLines(): void
	{
		$test1 = "TEST_OTHER=";
		$test2 = "=value";
		$test3 = "# This comment";
		$test4 = "\n";
		$test5 = "invalid line";
		$envContent = "$test1\n$test2\n$test3\n$test4\n$test5\n";

		$this->preTest($envContent);

		$this->assertArrayNotHasKey('TEST_OTHER', $_ENV);
		$this->assertArrayNotHasKey('', $_ENV);
		$this->assertArrayNotHasKey('#', $_ENV);
		$this->assertArrayNotHasKey('# This', $_ENV);
		$this->assertArrayNotHasKey('# This comment', $_ENV);
		$this->assertArrayNotHasKey("\n", $_ENV);
		$this->assertArrayNotHasKey('invalid', $_ENV);
		$this->assertArrayNotHasKey('invalid line', $_ENV);
	}

	public function testThrowsExceptionOnMissingFile(): void
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Файл non_existent.env не найден');

		$dotenv = new Dotenv();
		$dotenv->parse('non_existent.env');
	}
}
