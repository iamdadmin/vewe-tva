# Vewe ClassVariance

## About

Aiming to be an all-PHP combined implementation of Class Variance Authority, twMerge, and Tailwind-Variants.

Supporting the current version of PHP, less one, as a minimum requirement, to keep the package freshly maintained and utilising the latest features of PHP.

## Version Strategy

Based on semantic versioning, with the following constraints

- 1.x.x releases support PHP8.4, PHP8.5
- 2.x.x releases support PHP8.5, PHP8.6
- 3.x.x releases support PHP8.6, PHP-Next (tbc)

Major point releases may introduce breaking changes, which will be in the release notes.

Minor point releases should be non-breaking changes and fixes.

## Installation

You can install the package via composer:

```bash
composer require vewe/classvariance
```

## Usage

### `Cv`, with slots, akin to Tailwind-Variants

You can declare your classes as a single string, space delimited, or you can provided it as an array of strings, or a mix of the two methods, as suits your needs.

When using slots, *always declare a 'base' slot consistently* in the `Cv::new` definition. Unlike Tailwind-Variants, `Cv` *does not* assume which classes are meant for which slot, you must be declarative.

This is a conscious design decision, as your definition should have an immutable source of truth, instead of relying on script defaults which could change over time.

```php
use Vewe\ClassVariance\Cv;

 $button = Cv::new(
    [
        'base' => ['font-semibold', 'border', 'rounded'],
        'label' => [''],
    ],
    [
        'variants' => [
            'color' => [
                'primary' => [
                    'base' => [
                        'bg-blue-500',
                        'border-transparent',
                        'hover:bg-blue-600'
                    ],
                    'label' => ['text-white'],
                ],
                'secondary' => [
                    'base' => [
                        'bg-white',
                        'border-gray-400',
                        'hover:bg-gray-100'
                    ],
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
```

When you don't specify a slot, it defaults to returning the 'base' slot
```html
<button class="<?= $button(); ?>">Button, assumes default slot of 'base'</button>
```
You can use named properties or you can pass an empty array for $props.
```html
<button class="<?= $button(slot: 'base'); ?>">Button, specifies slot 'base'</button>

<button class="<?= $button([], 'base'); ?>">Button, specifies slot 'base'</button>
```
You can also take advantage of Tailwind-Variants style declarations, for your component to have multiple sub-components.
```html
<button class="<?= $button(props: ['color' => 'secondary', 'size' => 'small']); ?>">
    <span class="<?= $button(
        props: ['color' => 'secondary', 'size' => 'small'],
        slot: 'label'); ?>">
    Submit
    </span>
</button>
```

> [!TIP]
> Looking for a comprehensive set of components using `Cv`?
> Take a look at [vewe/ui](https://github.com/iamdadmin/vewe)!
> Ready for use with TempestPHP or adapt to anything you like.

### `Cv`, without slots

When not using slots, simply omit them from your definitions entirely.

```php
use Vewe\ClassVariance\Cv;

$button = Cv::new(
    ['font-semibold', 'border', 'rounded'],
    [
        'variants' => [
            'color' => [
                'primary' => [
                    'bg-blue-500',
                    'text-white',
                    'border-transparent',
                    'hover:bg-blue-600'
                ],
                'secondary' => 'bg-white text-gray-800 border-gray-400 hover:bg-gray-100',
            ],
            'size' => [
                'small' => ['text-sm', 'py-1', 'px-2'],
                'medium' => 'text-base py-2 px-4',
            ],
        ],
        'compoundVariants' => [
            [
                'color' => 'primary',
                'size' => 'medium',
                'class' => 'uppercase',
            ],
        ],
        'defaultVariants' => [
            'color' => 'primary',
            'size' => 'medium',
        ],
    ],
);
```
Equally, do not pass the slot property at all.
```html
<button class="<?= $button(); ?>">The</button>

<button class="<?= $button(['color' => 'primary', 'size' => 'medium']); ?>">Same</button>

<button class="<?= $button(props: ['color' => 'primary', 'size' => 'medium']); ?>">Button</button>
```

### Merging additional class data

Whether or not you implement slots configuration, if you wish to pass additional classes to be merged one-off into a specific button, you can pass it in either `class` or `className` within `$props`.

```html
<button
    class="<?= $button(props: ['color' => 'secondary', 'size' => 'small'], slot: 'base'); ?>">
    <span
        class="<?= $button(
            props: ['color' => 'secondary', 'size' => 'small'],
            slot: 'label'); ?>">
    Submit
    </span>
</button>

<button class="<?= $button(props: ['color' => 'secondary', 'size' => 'small']); ?>">Submit</button>

<button
    class="<?= $button(
        props: ['class' => 'border-red-600', 'color' => 'secondary', 'size' => 'small'],
        slot: 'base'); ?>">
    <span
        class="<?= $button(
            props: ['class' => 'text-italic', 'color' => 'secondary', 'size' => 'small'],
            slot: 'label'); ?>">
    Red-border Italic Submit
    </span>
</button>

<button
    class="<?= $button(
        props: ['class' => 'border-red-600 text-italic', 'color' => 'secondary', 'size' => 'small']); ?>">
Red-border Italic Submit
</button>
```

## Testing

```bash
composer phpunit
```

## Credits and Acknowledgements

A special thank you to the following, without whom this package would not exist.

- `Cv` began as a heavily-modified fork of [featureninja/cva](https://github.com/feature-ninja/cva) by [Roj Vroemen](https://github.com/rojtjo) and contributors
- In turn this was based on [Class Variance Authority](https://github.com/joe-bell/cva) by [Joe Bell](https://github.com/joe-bell)

Also a thank you to the following projects, whose own ideas helped shape `Cv`.

- [Tailwind-Variants](https://github.com/heroui-inc/tailwind-variants) by [Junior Garcia @jrgarciadev](https://github.com/jrgaciadev), [Tianen Pang @tianenpang](https://github.com/tianenpang) and contributors
- [Tailwind-merge](https://github.com/tales-from-a-dev/tailwind-merge-php) by [Romain Monteil](https://github.com/ker0x) which is based on [Tailwind-merge-php](https://github.com/gehrisandro/tailwind-merge-php) by [Sandro Gehri](https://github.com/gehrisandro) and contributors

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.