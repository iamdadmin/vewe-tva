<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Processors;

use Vewe\ClassVariance\Parsers\ClassNamesParser;

final readonly class CompoundVariantsProcessor
{
    /**
     * @param array<array-key, array<array-key, string|bool|array<array-key, string>>> $processorCollection
     */
    private function __construct(
        private array $processorCollection,
    ) {}

    /**
     * @param array<array-key, array<array-key, string|bool|array<array-key, string>>> $processorCollection
     */
    public static function of(array $processorCollection): self
    {
        return new self($processorCollection);
    }

    /**
     * @param array<array-key, string|bool> $props
     */
    public function resolve(array $props, string $slot = ''): ClassNamesParser
    {
        $classNames = ClassNamesParser::empty();
        foreach ($this->processorCollection as $compoundVariant) {
            if (! $this->matches($props, $compoundVariant)) {
                continue;
            }

            $classNames = $classNames->concat(ClassNamesParser::of(
                $compoundVariant['class'] ?? $compoundVariant['className'] ?? '',
                $slot,
            ));
        }

        return $classNames;
    }

    /**
     * @param array<array-key, string|bool> $props
     * @param array<array-key, string|bool|array<array-key, string>> $compoundVariant
     * @return bool
     */
    private function matches(array $props, array $compoundVariant): bool
    {
        foreach ($compoundVariant as $key => $value) {
            // Skip class/className as they're not matching conditions
            if ($key === 'class' || $key === 'className') {
                continue;
            }

            // If value is an array, check if prop value is in the array
            if (is_array($value)) {
                if (! isset($props[$key]) || ! in_array($props[$key], $value, true)) {
                    return false;
                }
            }
            // For scalar values (string, bool, etc.), check for exact match
            else {
                if (! isset($props[$key]) || $props[$key] !== $value) {
                    return false;
                }
            }
        }

        return true;
    }
}
