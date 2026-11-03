# PHP AI SDK — ByteDance

Video generation with ByteDance for the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/bytedance
```

Set `ARK_API_KEY` in your environment.

## Usage

```php
use AiSdk\ByteDance;
use AiSdk\Generate;

$result = Generate::video('A paper boat drifting along a quiet river.')
    ->model(ByteDance::model('dreamina-seedance-2-5-260628'))
    ->aspectRatio('16:9')
    ->duration(5)
    ->run(timeout: 600);

$result->output->save(__DIR__.'/video.mp4');
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/bytedance) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.
