<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vewe\ClassVariance\Cv;

final class CvClassNamesBooleanTest extends TestCase
{
    #[Test]
    public function class_prop_with_boolean_value_ignored(): void
    {
        $component = Cv::new(
            ['base' => ['component']],
            [
                'variants' => [
                    'variant' => [
                        'primary' => ['base' => 'bg-blue-500'],
                    ],
                ],
                'defaultVariants' => [
                    'variant' => 'primary',
                ],
            ],
        );

        // Boolean true in class should be ignored
        $this->assertSame(
            'component bg-blue-500',
            $component(['class' => true], 'base'),
        );

        // Boolean false in class should be ignored
        $this->assertSame(
            'component bg-blue-500',
            $component(['class' => false], 'base'),
        );

        // Boolean true in className should be ignored
        $this->assertSame(
            'component bg-blue-500',
            $component(['className' => true], 'base'),
        );

        // Boolean false in className should be ignored
        $this->assertSame(
            'component bg-blue-500',
            $component(['className' => false], 'base'),
        );
    }

    #[Test]
    public function class_prop_normal_strings_still_work(): void
    {
        $component = Cv::new(
            ['base' => ['component']],
            [
                'variants' => [
                    'variant' => [
                        'primary' => ['base' => 'bg-blue-500'],
                    ],
                ],
                'defaultVariants' => [
                    'variant' => 'primary',
                ],
            ],
        );

        // String class names work normally
        $this->assertSame(
            'component bg-blue-500 custom-class',
            $component(['class' => 'custom-class'], 'base'),
        );

        $this->assertSame(
            'component bg-blue-500 another-class',
            $component(['className' => 'another-class'], 'base'),
        );

        // Multiple classes
        $this->assertSame(
            'component bg-blue-500 custom-1 custom-2',
            $component(['class' => 'custom-1 custom-2'], 'base'),
        );
    }

    #[Test]
    public function slot_based_class_with_boolean_in_array(): void
    {
        $component = Cv::new(
            [
                'base' => ['component-base'],
                'label' => ['component-label'],
            ],
            [
                'variants' => [
                    'variant' => [
                        'primary' => [
                            'base' => ['bg-blue-500'],
                            'label' => ['text-white'],
                        ],
                    ],
                ],
                'defaultVariants' => [
                    'variant' => 'primary',
                ],
            ],
        );

        // Boolean in slotted class array should be ignored
        $this->assertSame(
            'component-base bg-blue-500',
            $component(['class' => ['base' => true, 'label' => 'extra']], 'base'),
        );

        $this->assertSame(
            'component-label text-white extra',
            $component(['class' => ['base' => true, 'label' => 'extra']], 'label'),
        );
    }

    #[Test]
    public function empty_string_and_whitespace_still_filtered(): void
    {
        $component = Cv::new(
            ['base' => ['component']],
            [],
        );

        // Empty string
        $this->assertSame(
            'component',
            $component(['class' => ''], 'base'),
        );

        // Whitespace only
        $this->assertSame(
            'component',
            $component(['class' => '   '], 'base'),
        );

        // Boolean false (edge case similar to empty)
        $this->assertSame(
            'component',
            $component(['class' => false], 'base'),
        );
    }
}
