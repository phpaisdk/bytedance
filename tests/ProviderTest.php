<?php

declare(strict_types=1);

use AiSdk\ByteDance;
use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\ByteDance\ByteDanceProvider;
use AiSdk\Contracts\EmbeddingProviderInterface;
use AiSdk\Contracts\ImageProviderInterface;
use AiSdk\Contracts\TextProviderInterface;
use AiSdk\Contracts\VideoProviderInterface;
use AiSdk\Generate;
use AiSdk\Support\Sdk;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ByteDanceFakeClient implements ClientInterface
{
    public ?RequestInterface $request = null;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        return new Response(200, [], json_encode(['id' => 'job-1'], JSON_THROW_ON_ERROR));
    }
}
it('exposes every implemented ByteDance capability', function () {
    $p = new ByteDanceProvider(new ByteDanceOptions('key'));
    expect($p)->toBeInstanceOf(TextProviderInterface::class)->toBeInstanceOf(ImageProviderInterface::class)->toBeInstanceOf(EmbeddingProviderInterface::class)->toBeInstanceOf(VideoProviderInterface::class);
});
it('starts ByteDance video tasks', function () {
    $c = new ByteDanceFakeClient();
    $f = new Psr17Factory();
    ByteDance::create([
        'apiKey' => 'key',
        'sdk' => new Sdk($c, $f, $f),
    ]);

    $j = Generate::video('A scene')
        ->model(ByteDance::model('seedance'))
        ->job();

    expect($j->id)->toBe('job-1')->and($c->request?->getUri()->getPath())->toBe('/api/v3/contents/generations/tasks');
});
