<?php

declare(strict_types=1);

namespace AiSdk\ByteDance\Models;

use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\EmbeddingModelInterface;
use AiSdk\OpenAICompatible\EmbeddingRequestBuilder;
use AiSdk\OpenAICompatible\EmbeddingResponseParser;
use AiSdk\Requests\EmbeddingRequest;
use AiSdk\Responses\EmbeddingResponse;
use AiSdk\Utils\Support\Url;

final class ByteDanceEmbeddingModel extends BaseModel implements EmbeddingModelInterface
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

    public function generate(EmbeddingRequest $r): EmbeddingResponse
    {
        $p = $this->runner($this->options->sdk)->postJson(Url::joinPath($this->options->baseUrl, '/embeddings'), EmbeddingRequestBuilder::build($this->modelId, $this->provider(), $r), $this->options->authHeaders(), $this->provider());

        return EmbeddingResponseParser::parse($p, $this->provider(), count($r->inputs));
    }
}
