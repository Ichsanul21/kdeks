<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\HalalLocation;
use App\Models\HalalProduct;
use App\Models\KnowledgeResource;
use App\Models\Regulation;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function articlesIndex(Request $request): View
    {
        $articles = Article::query()
            ->where('status', 'published')
            ->when($request->filled('type'), fn (Builder $query) => $query->where('type', $request->string('type')->toString()))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.articles.index', [
            'articles' => $articles,
            'selectedType' => $request->string('type')->toString(),
        ]);
    }

    public function articleShow(string $slug): View
    {
        $article = Article::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.articles.show', [
            'article' => $article,
            'relatedArticles' => Article::query()
                ->where('status', 'published')
                ->whereKeyNot($article->id)
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }

    public function galleryIndex(): View
    {
        return view('public.gallery.index', [
            'galleryItems' => GalleryItem::query()->latest('recorded_at')->paginate(12),
        ]);
    }

    public function productsIndex(Request $request): View
    {
        $products = HalalProduct::query()
            ->with('region')
            ->where('status', 'published')
            ->when($request->filled('keyword'), function (Builder $query) use ($request): void {
                $keyword = $request->string('keyword')->toString();
                $query->where(function (Builder $builder) use ($keyword): void {
                    $builder
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('brand_name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.products.index', [
            'products' => $products,
            'keyword' => $request->string('keyword')->toString(),
        ]);
    }

    public function productShow(string $slug): View
    {
        $product = HalalProduct::query()
            ->with('region')
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.products.show', [
            'product' => $product,
            'relatedProducts' => HalalProduct::query()
                ->where('status', 'published')
                ->whereKeyNot($product->id)
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }

    public function locationShow(string $slug): View
    {
        $location = HalalLocation::query()
            ->with(['region', 'lphPartner'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.locations.show', [
            'location' => $location,
            'nearbyLocations' => HalalLocation::query()
                ->with(['region', 'lphPartner'])
                ->where('status', 'published')
                ->whereKeyNot($location->id)
                ->limit(4)
                ->get(),
        ]);
    }

    public function resourcesIndex(): View
    {
        return view('public.resources.index', [
            'resources' => KnowledgeResource::query()->latest('published_at')->paginate(12),
        ]);
    }

    public function resourceShow(string $slug): View
    {
        $resource = KnowledgeResource::query()->where('slug', $slug)->firstOrFail();

        return view('public.resources.show', [
            'resource' => $resource,
        ]);
    }

    public function regulationsIndex(): View
    {
        return view('public.regulations.index', [
            'regulations' => Regulation::query()->latest('issued_at')->paginate(12),
        ]);
    }

    public function regulationShow(string $slug): View
    {
        $regulation = Regulation::query()->where('slug', $slug)->firstOrFail();

        return view('public.regulations.show', [
            'regulation' => $regulation,
        ]);
    }

    public function eventsIndex(): View
    {
        return view('public.events.index', [
            'events' => Event::query()->where('status', 'published')->orderBy('starts_at')->paginate(12),
        ]);
    }

    public function eventShow(string $slug): View
    {
        $event = Event::query()->where('status', 'published')->where('slug', $slug)->firstOrFail();

        return view('public.events.show', [
            'event' => $event,
        ]);
    }
}
