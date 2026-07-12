<?php

declare(strict_types=1);

namespace AiSdk\ByteDance\Models;

use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\ImageModelInterface;
use AiSdk\OpenAICompatible\ImageRequestBuilder;
use AiSdk\OpenAICompatible\ImageResponseParser;
use AiSdk\Requests\ImageRequest;
use AiSdk\Responses\ImageResponse;
use AiSdk\Utils\Support\Url;

final class ByteDanceImageModel extends BaseModel implements ImageModelInterface
{
    public function __construct(private readonly string $modelId, private readonly ByteDanceOptions $options) {}

    public function provider(): string
    {
        return ByteDanceOptions::PROVIDER_NAME;
    }

    public function modelId(): string
    {
        return $this->modelId;
    }

    public function generate(ImageRequest $r): ImageResponse
    {
        $body = ImageRequestBuilder::build($this->modelId, $this->provider(), $r);
        $p = $this->runner($this->options->sdk)->postJson(Url::joinPath($this->options->baseUrl, '/images/generations'), $body, $this->options->authHeaders(), $this->provider());

        return ImageResponseParser::parse($p, $this->provider());
    }
}
