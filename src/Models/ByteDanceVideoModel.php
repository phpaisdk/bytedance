<?php

declare(strict_types=1);

namespace AiSdk\ByteDance\Models;

use AiSdk\ByteDance\ByteDanceOptions;
use AiSdk\Content;
use AiSdk\ContentSource;
use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\VideoModelInterface;
use AiSdk\Exceptions\InvalidResponseException;
use AiSdk\Requests\VideoRequest;
use AiSdk\Responses\VideoJob;
use AiSdk\Responses\VideoJobStatus;
use AiSdk\Results\VideoData;
use AiSdk\Support\Usage;
use AiSdk\Utils\Support\Url;

final class ByteDanceVideoModel extends BaseModel implements VideoModelInterface
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

    public function generate(VideoRequest $request): VideoJob
    {
        $o = $request->providerOptionsFor($this->provider());
        $content = [['type' => 'text', 'text' => $request->prompt]];
        if ($request->image) {
            $content[] = ['type' => 'image_url', 'image_url' => ['url' => $this->media($request->image)]];
        }if ($request->video) {
            $content[] = ['type' => 'video_url', 'video_url' => ['url' => $this->media($request->video)]];
        }foreach (['lastFrameImage' => 'last_frame', 'referenceImages' => 'reference_image', 'referenceVideos' => 'reference_video', 'referenceAudio' => 'reference_audio'] as $key => $role) {
            foreach ((array) ($o[$key] ?? []) as $url) {
                $content[] = ['type' => str_contains($role, 'video') ? 'video_url' : (str_contains($role, 'audio') ? 'audio_url' : 'image_url'), str_contains($role, 'video') ? 'video_url' : (str_contains($role, 'audio') ? 'audio_url' : 'image_url') => ['url' => $url], 'role' => $role];
            }
        }$body = array_filter(['model' => $this->modelId, 'content' => $content, 'ratio' => $o['ratio'] ?? $request->output?->aspectRatio, 'duration' => $request->output?->duration, 'seed' => $o['seed'] ?? $request->output?->seed, 'resolution' => $this->resolution($request->output?->resolution)], fn($v) => $v !== null);
        $reserved = array_flip(['pollIntervalMs', 'pollTimeoutMs', 'ratio', 'seed', 'lastFrameImage', 'referenceImages', 'referenceVideos', 'referenceAudio']);
        $body = array_replace($body, array_diff_key($o, $reserved));
        $p = $this->runner($this->options->sdk)->postJson(Url::joinPath($this->options->baseUrl, '/contents/generations/tasks'), $body, $this->options->authHeaders(), $this->provider());
        $id = $p['id'] ?? null;
        if (! is_string($id) || $id === '') {
            throw InvalidResponseException::forProvider($this->provider(), 'ByteDance returned no video task id.', ['body' => $p]);
        }

        return new VideoJob($id, $this->provider(), $this->modelId, rawResponse: $p, providerMetadata: [$this->provider() => ['taskId' => $id, 'pollIntervalMs' => (int) ($o['pollIntervalMs'] ?? 3000), 'pollTimeoutMs' => (int) ($o['pollTimeoutMs'] ?? 300000)]]);
    }

    public function poll(VideoJob $job): VideoJob
    {
        $p = $this->runner($this->options->sdk)->getJson(Url::joinPath($this->options->baseUrl, '/contents/generations/tasks/' . rawurlencode($job->id)), $this->options->authHeaders(), $this->provider());
        $s = strtolower((string) ($p['status'] ?? ''));
        if ($s === 'succeeded') {
            $url = $p['content']['video_url'] ?? null;

            return is_string($url) && $url !== '' ? new VideoJob($job->id, $job->provider, $job->modelId, VideoJobStatus::Succeeded, new VideoData(url: $url), usage: Usage::empty(), rawResponse: $p, providerMetadata: $job->providerMetadata) : new VideoJob($job->id, $job->provider, $job->modelId, VideoJobStatus::Failed, errorMessage: 'ByteDance completed without a video URL.', rawResponse: $p, providerMetadata: $job->providerMetadata);
        }

        return new VideoJob($job->id, $job->provider, $job->modelId, $s === 'failed' ? VideoJobStatus::Failed : VideoJobStatus::Running, errorMessage: $s === 'failed' ? (string) ($p['error']['message'] ?? 'ByteDance video generation failed.') : null, rawResponse: $p, providerMetadata: $job->providerMetadata);
    }

    private function media(Content $c): string
    {
        return $c->source() === ContentSource::Url ? (string) $c->url() : 'data:' . $c->mimeType() . ';base64,' . $c->base64Data();
    }

    private function resolution(?string $r): ?string
    {
        return match ($r) {
            '1280x720','720x1280','960x960' => '720p','1920x1080','1080x1920','1440x1440' => '1080p',default => $r,
        };
    }
}
