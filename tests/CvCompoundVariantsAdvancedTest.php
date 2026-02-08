<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vewe\ClassVariance\Cv;

final class CvCompoundVariantsAdvancedTest extends TestCase
{
    #[Test]
    public function compound_variant_with_array_conditions(): void
    {
        $component = Cv::new(
            [
                'base' => ['component'],
                'content' => ['content-base'],
                'leading' => ['leading-base'],
            ],
            [
                'variants' => [
                    'variant' => [
                        'solid' => [
                            'base' => ['bg-solid'],
                        ],
                        'outline' => [
                            'base' => ['border-outline'],
                        ],
                        'soft' => [
                            'base' => ['bg-soft'],
                        ],
                        'subtle' => [
                            'base' => ['bg-subtle'],
                        ],
                    ],
                    'compact' => [
                        'true' => [
                            'content' => ['p-2'],
                        ],
                        'false' => [
                            'content' => ['p-4'],
                        ],
                    ],
                ],
                'compoundVariants' => [
                    // Matches when variant is one of: solid, outline, soft, subtle
                    // AND compact is false
                    [
                        'variant' => ['solid', 'outline', 'soft', 'subtle'],
                        'compact' => 'false',
                        'class' => [
                            'content' => 'px-4 py-3 rounded-lg min-h-12',
                            'leading' => 'mt-2',
                        ],
                    ],
                ],
                'defaultVariants' => [
                    'variant' => 'solid',
                    'compact' => 'false',
                ],
            ],
        );

        // Test default: variant=solid, compact=false
        // Should match the compound variant
        $this->assertSame(
            'content-base p-4 px-4 py-3 rounded-lg min-h-12',
            $component(slot: 'content'),
        );

        $this->assertSame(
            'leading-base mt-2',
            $component(slot: 'leading'),
        );

        // Test with variant=outline, compact=false
        // Should still match the compound variant
        $this->assertSame(
            'content-base p-4 px-4 py-3 rounded-lg min-h-12',
            $component(['variant' => 'outline', 'compact' => 'false'], 'content'),
        );

        // Test with variant=solid, compact=true
        // Should NOT match the compound variant
        $this->assertSame(
            'content-base p-2',
            $component(['variant' => 'solid', 'compact' => 'true'], 'content'),
        );
    }

    #[Test]
    public function compound_variant_with_multiple_array_conditions(): void
    {
        $component = Cv::new(
            ['base' => ['component']],
            [
                'variants' => [
                    'color' => [
                        'neutral' => ['base' => 'text-neutral'],
                        'primary' => ['base' => 'text-primary'],
                    ],
                    'variant' => [
                        'outline' => ['base' => 'border'],
                        'subtle' => ['base' => 'bg-subtle'],
                    ],
                    'multiple' => [
                        'true' => ['base' => 'multiple'],
                        'false' => ['base' => 'single'],
                    ],
                ],
                'compoundVariants' => [
                    // Matches when color=neutral AND multiple=true
                    // AND variant is either outline or subtle
                    [
                        'color' => 'neutral',
                        'multiple' => 'true',
                        'variant' => ['outline', 'subtle'],
                        'class' => [
                            'base' => 'has-focus-visible:ring-2 has-focus-visible:ring-inverted',
                        ],
                    ],
                ],
                'defaultVariants' => [
                    'color' => 'neutral',
                    'variant' => 'outline',
                    'multiple' => 'false',
                ],
            ],
        );

        // Default: should NOT match (multiple=false)
        $this->assertSame(
            'component text-neutral border single',
            $component(slot: 'base'),
        );

        // Should match: color=neutral, variant=outline, multiple=true
        $this->assertSame(
            'component text-neutral border multiple has-focus-visible:ring-2 has-focus-visible:ring-inverted',
            $component(['color' => 'neutral', 'variant' => 'outline', 'multiple' => 'true'], 'base'),
        );

        // Should match: color=neutral, variant=subtle, multiple=true
        $this->assertSame(
            'component text-neutral bg-subtle multiple has-focus-visible:ring-2 has-focus-visible:ring-inverted',
            $component(['color' => 'neutral', 'variant' => 'subtle', 'multiple' => 'true'], 'base'),
        );

        // Should NOT match: color=primary (wrong color)
        $this->assertSame(
            'component text-primary border multiple',
            $component(['color' => 'primary', 'variant' => 'outline', 'multiple' => 'true'], 'base'),
        );
    }
}
