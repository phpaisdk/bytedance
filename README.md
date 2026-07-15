# ByteDance provider

ByteDance ModelArk support for text generation, streaming, tools, structured output, multimodal input, image generation, embeddings, and Seedance video generation.

Configuration uses `ARK_API_KEY`; `baseUrl` can select the BytePlus or Volcengine region.

```php
$video = Generate::video('A cinematic sunrise')
    ->model(ByteDance::model('doubao-seedance-1-0-pro-250528'))
    ->aspectRatio('16:9')
    ->duration(5)
    ->run(timeout: 600);
```
