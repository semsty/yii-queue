<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Queue\Tests\Unit\Middleware\Push;

use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Yii\Queue\Middleware\InvalidMiddlewareDefinitionException;
use Yiisoft\Yii\Queue\Tests\Unit\Middleware\Push\Support\TestCallableMiddleware;

final class InvalidMiddlewareDefinitionExceptionTest extends TestCase
{
    public function dataBase(): array
    {
        return [
            [
                'test',
                '"test"',
            ],
            [
                new TestCallableMiddleware(),
                'an instance of "Yiisoft\Yii\Queue\Tests\Unit\Middleware\Push\Support\TestCallableMiddleware"',
            ],
            [
                [TestCallableMiddleware::class, 'notExistsAction'],
                '["Yiisoft\Yii\Queue\Tests\Unit\Middleware\Push\Support\TestCallableMiddleware", "notExistsAction"]',
            ],
            [
                ['class' => TestCallableMiddleware::class, 'index'],
                '["class" => "Yiisoft\Yii\Queue\Tests\Unit\Middleware\Push\Support\TestCallableMiddleware", "index"]',
            ],
        ];
    }

    /**
     * @dataProvider dataBase
     *
     * @param mixed $definition
     * @param string $expected
     */
    public function testBase(mixed $definition, string $expected): void
    {
        $exception = new InvalidMiddlewareDefinitionException($definition);
        self::assertStringEndsWith('. Got ' . $expected . '.', $exception->getMessage());
    }

    public function dataUnknownDefinition(): array
    {
        return [
            [42],
            [[new stdClass()]],
        ];
    }

    /**
     * @dataProvider dataUnknownDefinition
     *
     * @param mixed $definition
     */
    public function testUnknownDefinition(mixed $definition): void
    {
        $exception = new InvalidMiddlewareDefinitionException($definition);
        self::assertSame(
            'Parameter should be either PSR middleware class name or a callable.',
            $exception->getMessage()
        );
    }
}
