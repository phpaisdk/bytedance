# ByteDance provider

<a href="https://github.com/phpaisdk/bytedance/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/bytedance/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/bytedance"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/aisdk/bytedance"></a>
<a href="https://packagist.org/packages/aisdk/bytedance"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/bytedance"></a>
<a href="https://packagist.org/packages/aisdk/bytedance"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/bytedance"></a>
<a href="https://whyphp.dev"><img src="https://img.shields.io/badge/Why_PHP-in_2026-7A86E8?style=flat-square&labelColor=18181b" alt="Why PHP in 2026"></a>

------

ByteDance ModelArk support for text generation, streaming, tools, structured output, multimodal input, image generation, embeddings, and Seedance video generation.

Configuration uses `ARK_API_KEY`; `baseUrl` can select the BytePlus or Volcengine region.

```php
$video = Generate::video('A cinematic sunrise')
    ->model(ByteDance::model('doubao-seedance-1-0-pro-250528'))
    ->aspectRatio('16:9')
    ->duration(5)
    ->run(timeout: 600);
```

## Documentation

- [PHP AI SDK documentation](https://phpaisdk.com/docs)
- [ByteDance documentation](https://phpaisdk.com/docs/bytedance)

## Community

- [Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md)
- [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)
- For private security reports, email [security@phpaisdk.com](mailto:security@phpaisdk.com).
