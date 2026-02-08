<?php

declare(strict_types=1);

namespace Vewe\ClassVariance\Processors;

final readonly class DefaultVariantsProcessor
{
    /**
     * @param array<array-key, string|bool> $processorCollection
     */
    private function __construct(
        private array $processorCollection,
    ) {}

    /**
     * @param array<array-key, string|bool> $processorCollection
     */
    public static function of(array $processorCollection): self
    {
        return new self($processorCollection);
    }

    /**
     * @param array<array-key, string|bool> $props
     * @return array<array-key, string|bool>
     */
    public function merge(array $props): array
    {
        foreach ($this->processorCollection as $key => $value) {
            $props[$key] ??= $value;
        }

        return $props;
    }
}
