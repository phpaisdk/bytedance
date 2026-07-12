<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\ByteDance\ByteDanceProvider;
use AiSdk\Contracts\EmbeddingModelInterface;
use AiSdk\Contracts\ImageModelInterface;
use AiSdk\Contracts\TextModelInterface;
use AiSdk\Contracts\VideoModelInterface;

final class ByteDance
{
    private static ?ByteDanceProvider $default = null;

    /** @param array<string, mixed> $config */
    public static function create(array $config = []): ByteDanceProvider
    {
        return self::$default = new ByteDanceProvider(ByteDanceOptions::fromArray($config));
    }

    public static function default(): ByteDanceProvider
    {
        return self::$default ??= self::create();
    }

    public static function reset(): void
    {
        self::$default = null;
    }

    public static function video(string $modelId): VideoModelInterface
    {
        return self::default()->videoModel($modelId);
    }

    public static function model(string $modelId): TextModelInterface
    {
        return self::default()->textModel($modelId);
    }

    public static function image(string $modelId): ImageModelInterface
    {
        return self::default()->imageModel($modelId);
    }

    public static function embedding(string $modelId): EmbeddingModelInterface
    {
        return self::default()->embeddingModel($modelId);
    }
}
