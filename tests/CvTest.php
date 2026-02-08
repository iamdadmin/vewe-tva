<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vewe\ClassVariance\Cv;

final class CvTest extends TestCase
{
    #[Test]
    public function cv_test(): void
    {
        $button = Cv::new(
            ['font-semibold', 'border', 'rounded'],
            [
                'variants' => [
                    'variant' => [
                        'primary' => ['bg-blue-500', 'text-white', 'border-transparent', 'hover:bg-blue-600'],
                        'secondary' => ['bg-white', 'text-gray-800', 'border-gray-400', 'hover:bg-gray-100'],
                    ],
                    'size' => [
                        'small' => ['text-sm', 'py-1', 'px-2'],
                        'medium' => ['text-base', 'py-2', 'px-4'],
                    ],
                ],
                'compoundVariants' => [
                    [
                        'variant' => 'primary',
                        'size' => 'medium',
                        'class' => 'uppercase',
                    ],
                ],
                'defaultVariants' => [
                    'variant' => 'primary',
                    'size' => 'medium',
                ],
            ],
        );

        $this->assertSame(
            'font-semibold border rounded bg-blue-500 text-white border-transparent hover:bg-blue-600 text-base py-2 px-4 uppercase',
            $button(),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white text-gray-800 border-gray-400 hover:bg-gray-100 text-sm py-1 px-2',
            $button(['variant' => 'secondary', 'size' => 'small']),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white text-gray-800 border-gray-400 hover:bg-gray-100 text-sm py-1 px-2 focus:ring-2',
            $button(['class' => 'focus:ring-2', 'className' => 'focus:ring-4', 'variant' => 'secondary', 'size' => 'small']),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white text-gray-800 border-gray-400 hover:bg-gray-100 text-sm py-1 px-2 focus:ring-2',
            $button(['className' => 'focus:ring-2', 'variant' => 'secondary', 'size' => 'small']),
        );
    }
}
