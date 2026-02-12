<?php

declare(strict_types=1);

namespace Vewe\ClassVariance;

use InvalidArgumentException;
use TalesFromADev\TailwindMerge\TailwindMerge;
use Vewe\ClassVariance\Collections\ProcessorCollection;
use Vewe\ClassVariance\Parsers\ClassNamesParser;

final readonly class Cv
{
    /**
     * @param array<array-key, array<array-key, string>|string>|string $base
     */
    public function __construct(
        public array|string $base,
        public ProcessorCollection $processorCollection,
        public string $slot = '',
    ) {}

    /**
     * @param string|array<array-key, string|array<array-key, string>> $base
     * @param array{
     *     variants?: array<array-key, array<array-key, string|array<array-key, string>|array<array-key, string|array<array-key, string>>>>,
     *     compoundVariants?: array<array-key, array<array-key, string|bool|array<array-key, string>>>,
     *     defaultVariants?: array<array-key, string|bool>
     * } $processorCollection
     */
    public static function new(string|array $base, array $processorCollection): self
    {
        return new self(
            $base,
            ProcessorCollection::of($processorCollection),
        );
    }

    /**
     * @param array<array-key, string|bool> $props
     * @param string $slot
     */
    public function __invoke(array $props = [], string $slot = ''): string
    {
        $props = $this->processorCollection->defaultVariants->merge($props);

        // If $base has a single slot defined, but $slot is not provided, populate it from the $base definition
        if ($slot === '' && is_array($this->base) && count($this->base) === 1 && ! isset($this->base[0])) {
            $slot = (string) array_key_first($this->base);
        }

        // If $base has multiple slots defined, and $slot is not provided, throw exception
        if ($slot === '' && is_array($this->base) && count($this->base) > 1 && ! isset($this->base[0])) {
            throw new InvalidArgumentException(
                'Multiple slots detected in base array but no $slot parameter provided. Please specify a slot parameter when calling the component. ' . 'Available slots: '
                    . implode(', ', array_keys($this->base)),
            );
        }

        $additionalClassNames = ClassNamesParser::of($props['class'] ?? $props['className'] ?? '', '');

        $baseClassNames = ClassNamesParser::of($this->base, $slot);

        $tw = new TailwindMerge();

        return $tw->merge(
            $baseClassNames
                ->concat($this->processorCollection->variants->resolve($props, $slot))
                ->concat($this->processorCollection->compoundVariants->resolve($props, $slot))
                ->concat($additionalClassNames)
                ->toString(),
            $additionalClassNames->toString(),
        );
    }
}
