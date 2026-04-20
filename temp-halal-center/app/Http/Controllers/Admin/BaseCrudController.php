<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

abstract class BaseCrudController extends Controller
{
    public function __construct(protected MediaStorageService $mediaStorageService)
    {
    }

    protected string $modelClass;
    protected string $pageTitle;
    protected string $routePrefix;
    protected string $requestClass;
    protected array $tableColumns = [];
    protected array $searchColumns = [];
    protected array $formFields = [];
    protected array $publicFileFields = [];
    protected array $privateFileFields = [];
    protected ?string $publicIndexRoute = null;
    protected ?string $publicShowRoute = null;
    protected ?string $publicShowRouteKey = 'slug';

    public function index(Request $request): View
    {
        $query = $this->indexQuery();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search): void {
                foreach ($this->searchColumns as $column) {
                    $builder->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $items = $query->latest()->paginate(12)->withQueryString();

        return view('admin.crud.index', [
            'pageTitle' => $this->pageTitle,
            'routePrefix' => $this->routePrefix,
            'items' => $items,
            'tableColumns' => $this->tableColumns,
            'publicIndexRoute' => $this->publicIndexRoute,
            'publicShowRoute' => $this->publicShowRoute,
            'publicShowRouteKey' => $this->publicShowRouteKey,
        ]);
    }

    public function create(): View
    {
        return $this->formView(new $this->modelClass(), 'create');
    }

    public function store(): RedirectResponse
    {
        $model = new $this->modelClass();
        $model->fill($this->validatedData());
        $this->syncFiles($model);
        $model->save();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "{$this->pageTitle} berhasil ditambahkan.");
    }

    public function edit(string $id): View
    {
        return $this->formView($this->findModel($id), 'edit');
    }

    public function update(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $model = $this->findModel($id);
        $model->fill($this->validatedData($model));
        $this->syncFiles($model);
        $model->save();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "{$this->pageTitle} berhasil diperbarui.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $model = $this->findModel($id);

        foreach ($this->publicFileFields as $field) {
            $this->mediaStorageService->delete($model->{$field}, 'public');
        }

        foreach ($this->privateFileFields as $field) {
            $this->mediaStorageService->delete($model->{$field}, 'private');
        }

        $model->delete();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "{$this->pageTitle} berhasil dihapus.");
    }

    protected function formView(Model $item, string $mode): View
    {
        return view('admin.crud.form', [
            'pageTitle' => $this->pageTitle,
            'routePrefix' => $this->routePrefix,
            'item' => $item,
            'mode' => $mode,
            'formFields' => $this->resolvedFields(),
            'publicFileFields' => $this->publicFileFields,
            'privateFileFields' => $this->privateFileFields,
            'publicShowRoute' => $this->publicShowRoute,
            'publicShowRouteKey' => $this->publicShowRouteKey,
        ]);
    }

    protected function indexQuery()
    {
        return $this->modelClass::query();
    }

    protected function resolvedFields(): array
    {
        return $this->formFields;
    }

    protected function validatedData(?Model $model = null): array
    {
        /** @var FormRequest $request */
        $request = app($this->requestClass);
        $request->setContainer(app())->setRedirector(app('redirect'));

        if (! $request->authorize()) {
            abort(403);
        }

        $requestData = request()->all();

        if ($model) {
            $requestData['current_model_id'] = $model->getKey();
        }

        $validated = Validator::make(
            $requestData,
            $request->rules(),
            $request->messages(),
            $request->attributes()
        )->validate();

        foreach ($this->resolvedFields() as $field) {
            if (($field['type'] ?? null) === 'checkbox') {
                $validated[$field['name']] = request()->boolean($field['name']);
            }
        }

        return $validated;
    }

    protected function storageDirectory(): string
    {
        return str_replace('.', '/', $this->routePrefix);
    }

    protected function syncFiles(Model $model): void
    {
        foreach ($this->publicFileFields as $field) {
            if (request()->hasFile($field)) {
                $model->{$field} = $this->mediaStorageService->replace(
                    $model->{$field},
                    request()->file($field),
                    'public',
                    $this->storageDirectory()
                );
            }
        }

        foreach ($this->privateFileFields as $field) {
            if (request()->hasFile($field)) {
                $model->{$field} = $this->mediaStorageService->replace(
                    $model->{$field},
                    request()->file($field),
                    'private',
                    $this->storageDirectory()
                );
            }
        }
    }

    protected function findModel(string $id): Model
    {
        return $this->modelClass::query()->findOrFail($id);
    }
}
