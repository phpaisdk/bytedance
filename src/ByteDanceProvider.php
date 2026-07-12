<?php

declare(strict_types=1);

namespace AiSdk\ByteDance;

use AiSdk\ByteDance\Models\ByteDanceEmbeddingModel;
use AiSdk\ByteDance\Models\ByteDanceImageModel;
use AiSdk\ByteDance\Models\ByteDanceTextModel;
use AiSdk\ByteDance\Models\ByteDanceVideoModel;
use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\EmbeddingModelInterface;
use AiSdk\Contracts\EmbeddingProviderInterface;
use AiSdk\Contracts\ImageModelInterface;
use AiSdk\Contracts\ImageProviderInterface;
use AiSdk\Contracts\TextModelInterface;
use AiSdk\Contracts\TextProviderInterface;
use AiSdk\Contracts\VideoModelInterface;
use AiSdk\Contracts\VideoProviderInterface;

final class ByteDanceProvider extends BaseProvider implements EmbeddingProviderInterface, ImageProviderInterface, TextProviderInterface, VideoProviderInterface
{
    public function __construct(public readonly ByteDanceOptions $options) {}

    public function name(): string
    {
        return ByteDanceOptions::PROVIDER_NAME;
    }

    public function videoModel(string $modelId): VideoModelInterface
    {
        return new ByteDanceVideoModel($modelId, $this->options);
    }

    public function textModel(string $modelId): TextModelInterface
    {
        return new ByteDanceTextModel($modelId, $this->options);
    }

    public function imageModel(string $modelId): ImageModelInterface
    {
        return new ByteDanceImageModel($modelId, $this->options);
    }

    public function embeddingModel(string $modelId): EmbeddingModelInterface
    {
        return new ByteDanceEmbeddingModel($modelId, $this->options);
    }
}
