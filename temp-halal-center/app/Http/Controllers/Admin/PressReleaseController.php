<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PressReleaseRequest;
use App\Models\PressRelease;

class PressReleaseController extends BaseCrudController
{
    protected string $modelClass = PressRelease::class;
    protected string $pageTitle = 'Siaran Pers';
    protected string $routePrefix = 'admin.press-releases';
    protected string $requestClass = PressReleaseRequest::class;
    protected ?string $publicIndexRoute = 'siaran-pers';
    
    protected array $searchColumns = ['title'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'video_url', 'label' => 'URL Video'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'is_featured', 'label' => 'Unggulan'],
    ];

    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'placeholder' => 'Kosongkan untuk ambil otomatis dari YouTube', 'required' => false],
        ['name' => 'video_url', 'label' => 'YouTube URL', 'type' => 'text', 'placeholder' => 'Contoh: https://www.youtube.com/live/... atau https://youtu.be/...', 'required' => true],
        ['name' => 'status', 'label' => 'Tipe / Status', 'type' => 'select', 'options' => ['archived' => 'Playlist / Arsip', 'streaming' => 'Live Streaming (Player Utama)'], 'required' => true],
        ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'placeholder' => 'Kosongkan untuk ambil otomatis dari YouTube'],
        ['name' => 'is_featured', 'label' => 'Jadikan Unggulan', 'type' => 'checkbox'],
    ];

    public function store(): \Illuminate\Http\RedirectResponse
    {
        $data = $this->validatedData();
        
        if (empty($data['title']) || empty($data['description'])) {
            $metadata = $this->fetchYoutubeMetadata($data['video_url']);
            $data['title'] = $data['title'] ?: ($metadata['title'] ?? 'Video YouTube');
            $data['description'] = $data['description'] ?: ($metadata['description'] ?? null);
        }

        if ($data['status'] === 'streaming') {
            PressRelease::where('status', 'streaming')->update(['status' => 'archived']);
        }

        if (!empty($data['is_featured'])) {
            PressRelease::where('is_featured', true)->update(['is_featured' => false]);
        }

        $model = new $this->modelClass();
        $model->fill($data);
        $model->save();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "{$this->pageTitle} berhasil ditambahkan.");
    }

    public function update(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $data = $this->validatedData();
        $model = $this->findModel($id);

        // If URL changed or title/desc is empty, refetch
        if ($model->video_url !== $data['video_url'] || empty($data['title']) || empty($data['description'])) {
            $metadata = $this->fetchYoutubeMetadata($data['video_url']);
            $data['title'] = $data['title'] ?: ($metadata['title'] ?? $model->title);
            $data['description'] = $data['description'] ?: ($metadata['description'] ?? $model->description);
        }

        if ($data['status'] === 'streaming') {
            PressRelease::where('status', 'streaming')->where('id', '!=', $id)->update(['status' => 'archived']);
        }

        if (!empty($data['is_featured'])) {
            PressRelease::where('is_featured', true)->where('id', '!=', $id)->update(['is_featured' => false]);
        }

        $model->fill($data);
        $model->save();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "{$this->pageTitle} berhasil diperbarui.");
    }

    protected function fetchYoutubeMetadata(string $url): array
    {
        $metadata = ['title' => null, 'description' => null];

        // Normalizing URL for oEmbed (some formats like /live/ might need standard watch?v= or just work)
        // YouTube oEmbed usually handles /live/ fine if you pass the full URL.
        
        try {
            // 1. Get Title from oEmbed (Reliable)
            $oembedUrl = "https://www.youtube.com/oembed?url=" . urlencode($url) . "&format=json";
            
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);

            $response = @file_get_contents($oembedUrl, false, $ctx);
            if ($response) {
                $json = json_decode($response, true);
                $metadata['title'] = $json['title'] ?? null;
            }

            // 2. Get Description from Meta Tags (Heuristic)
            $html = @file_get_contents($url, false, $ctx);
            if ($html) {
                // Try several meta tags for description
                if (preg_match('/<meta name="description" content="([^"]+)"/i', $html, $matches)) {
                    $metadata['description'] = html_entity_decode($matches[1]);
                } elseif (preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $matches)) {
                    $metadata['description'] = html_entity_decode($matches[1]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $metadata;
    }

    public function finishStream(string $id): \Illuminate\Http\RedirectResponse
    {
        $release = PressRelease::findOrFail($id);
        $release->update(['status' => 'archived']);
        return redirect()->back()->with('status', 'Streaming telah diselesaikan dan dipindahkan ke playlist.');
    }
}
