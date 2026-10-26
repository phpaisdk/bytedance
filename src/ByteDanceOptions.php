<?php

declare(strict_types=1);

namespace AiSdk\ByteDance;

use AiSdk\Support\Sdk;
use AiSdk\Utils\Support\Env;
use AiSdk\Utils\Support\Url;

final class ByteDanceOptions
{
    public const string PROVIDER_NAME = 'bytedance';

    public const string DEFAULT_BASE_URL = 'https://ark.ap-southeast.bytepluses.com/api/v3';

    /** @param array<string, string> $headers */
    public function __construct(public readonly string $apiKey, public readonly string $baseUrl = self::DEFAULT_BASE_URL, public readonly array $headers = [], public readonly ?Sdk $sdk = null) {}

    /** @param array<string, mixed> $config */
    public static function fromArray(array $config = []): self
    {
        return new self(Env::loadApiKey(isset($config['apiKey']) ? (string) $config['apiKey'] : null, 'ARK_API_KEY', self::PROVIDER_NAME), Url::withoutTrailingSlash((string) ($config['baseUrl'] ?? $config['baseURL'] ?? self::DEFAULT_BASE_URL)), is_array($config['headers'] ?? null) ? $config['headers'] : [], ($config['sdk'] ?? null) instanceof Sdk ? $config['sdk'] : null);
    }

    /** @return array<string, string> */
    public function authHeaders(): array
    {
        return array_merge(['Authorization' => 'Bearer ' . $this->apiKey], $this->headers);
    }
}
