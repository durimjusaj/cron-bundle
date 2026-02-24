<?php declare(strict_types=1);

namespace Cron\CronBundle\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class SymfonyWebTestCase extends WebTestCase
{
    private mixed $originalExceptionHandler;
    private mixed $originalErrorHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalExceptionHandler = self::peekExceptionHandler();
        $this->originalErrorHandler = self::peekErrorHandler();
    }

    protected function tearDown(): void
    {
        try {
            parent::tearDown();
        } finally {
            $this->restoreExceptionHandler($this->originalExceptionHandler);
            $this->restoreErrorHandler($this->originalErrorHandler);
        }
    }

    private static function peekExceptionHandler(): mixed
    {
        $previous = set_exception_handler(static function (\Throwable $e): void {});
        restore_exception_handler();

        return $previous;
    }

    private static function peekErrorHandler(): mixed
    {
        $previous = set_error_handler(static function (int $errno, string $errstr, string $errfile = '', int $errline = 0): bool {
            return false;
        });
        restore_error_handler();

        return $previous;
    }

    private function restoreExceptionHandler(mixed $target): void
    {
        for ($i = 0; $i < 10; $i++) {
            if (self::peekExceptionHandler() === $target) {
                return;
            }

            if (!restore_exception_handler()) {
                return;
            }
        }
    }

    private function restoreErrorHandler(mixed $target): void
    {
        for ($i = 0; $i < 10; $i++) {
            if (self::peekErrorHandler() === $target) {
                return;
            }

            if (!restore_error_handler()) {
                return;
            }
        }
    }
}

