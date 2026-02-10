<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vewe\ClassVariance\Cv;

final class CvWithSlotTest extends TestCase
{
    #[Test]
    public function cv_with_slot_test(): void
    {
        $button = Cv::new(
            [
                'base' => ['font-semibold', 'border', 'rounded'],
                'label' => [''],
            ],
            [
                'variants' => [
                    'color' => [
                        'primary' => [
                            'base' => ['bg-blue-500', 'border-transparent', 'hover:bg-blue-600'],
                            'label' => ['text-white'],
                        ],
                        'secondary' => [
                            'base' => ['bg-white', 'border-gray-400', 'hover:bg-gray-100'],
                            'label' => ['text-black'],
                        ],
                    ],
                    'size' => [
                        'small' => [
                            'base' => ['py-1', 'px-2'],
                            'label' => ['text-sm'],
                        ],
                        'medium' => [
                            'base' => ['py-2', 'px-4'],
                            'label' => ['text-base'],
                        ],
                    ],
                ],
                'compoundVariants' => [
                    [
                        'color' => 'primary',
                        'size' => 'medium',
                        'class' => [
                            'label' => 'uppercase',
                        ],
                    ],
                ],
                'defaultVariants' => [
                    'color' => 'primary',
                    'size' => 'medium',
                ],
            ],
        );

        $this->assertSame(
            'font-semibold border rounded bg-blue-500 border-transparent hover:bg-blue-600 py-2 px-4',
            $button(slot: 'base'),
        );

        $this->assertSame(
            'text-white text-base uppercase',
            $button(slot: 'label'),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white border-gray-400 hover:bg-gray-100 py-1 px-2',
            $button(props: ['color' => 'secondary', 'size' => 'small'], slot: 'base'),
        );

        $this->assertSame(
            'text-black text-sm',
            $button(props: ['color' => 'secondary', 'size' => 'small'], slot: 'label'),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white border-gray-400 hover:bg-gray-100 py-1 px-2 focus:ring-2',
            $button(props: ['class' => 'focus:ring-2', 'className' => 'focus:ring-4', 'color' => 'secondary', 'size' => 'small'], slot: 'base'),
        );

        $this->assertSame(
            'text-black text-sm',
            $button(props: ['color' => 'secondary', 'size' => 'small'], slot: 'label'),
        );

        $this->assertSame(
            'font-semibold border rounded bg-white border-gray-400 hover:bg-gray-100 py-1 px-2 focus:ring-2',
            $button(props: ['className' => 'focus:ring-2', 'color' => 'secondary', 'size' => 'small'], slot: 'base'),
        );

        $this->assertSame(
            'text-black text-sm',
            $button(props: ['color' => 'secondary', 'size' => 'small'], slot: 'label'),
        );

        $this->assertSame(
            '',
            $button(slot: 'fooBarBaz'),
        );
    }
}
