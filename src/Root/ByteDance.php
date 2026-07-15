<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\ByteDance\ByteDanceProvider;
use AiSdk\Contracts\Model;

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

    public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}
