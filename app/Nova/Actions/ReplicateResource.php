<?php

namespace App\Nova\Actions;

use App\Models\Section;
use App\Models\Setting;
use App\Nova\PageStatic;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReplicateResource extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $showOnDetail = true;

    public $showOnIndex = true;

    public $showInline = false;

    public $name = 'Replicate For Other Language';

    public $confirmButtonText = 'Create';

    public $cancelButtonText = 'Cancel';

    public $confirmText = 'Are you sure you want to replicate this resource?';

    public $withoutActionEvents = true;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() !== 1) {
            return Action::danger('Cannot replicate multiple models simultaneously.');
        }

        $model = $models->first();
        $newModel = $model->replicate();

        DB::transaction(function () use ($fields, $model, $newModel) {
            $newModel->language = $fields->language;
            $newModel->push();

            // load the relations
            $model->load('sections');
            foreach ($model->getRelations() as $relation => $items) {
                // works for hasMany section
                foreach ($items as $item) {
                    $section = null;
                    // check header and footer exists with selected language
                    if ($item->layout == 'header' && $fields->header != 'create_new') {
                        $section = Section::find($fields->header);
                    }

                    if ($item->layout == 'footer' && $fields->footer != 'create_new') {
                        $section = Section::find($fields->footer);
                    }

                    // other sections auto create
                    if (! $section) {
                        $title = Arr::get($item->structure, 'attributes');
                        $name_section = $title ? Arr::get($title, 'name') : null;

                        // check name unique
                        $suitable_name = null;
                        if (Section::where('name', $name_section)->exists()) {
                            $index = 1;
                            do {
                                if (! Section::where('name', $name_section."($index)")->exists()) {
                                    $suitable_name = $name_section."($index)";
                                }
                                $index++;
                            } while (! $suitable_name);
                        }

                        $new_structure = $item->structure;
                        if ($suitable_name) {
                            $new_structure['attributes']['name'] = $suitable_name;
                        }

                        $section = Section::create([
                            'layout' => $item->layout,
                            'name' => $suitable_name ?? $name_section,
                            'structure' => $new_structure,
                        ]);
                    }

                    $newModel->sections()->attach($section->id, [
                        'order' => $item->pivot->order,
                        'key' => $item->pivot->key,
                    ]);
                }
            }

            $newModel->save();
        });

        $uriKey = PageStatic::uriKey();
        $id = $newModel->id;

        return Action::redirect("/nova/resources/$uriKey/$id");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $resource_id = $request->resourceId ?? $request->resources;
        if ($request->resource != 'page-statics' || empty($resource_id)) {
            return [];
        }
        $fields = collect([
            Select::make('Language', 'language')
                ->options(fn () => Setting::get('languages')->mapWithKeys(fn ($item, $key) => [$item['key'] => $item['value']]))
                ->displayUsingLabels()
                ->rules(['required']),
        ]);

        $sections = \App\Models\PageStatic::find($resource_id)->sections->pluck('layout');
        if ($sections->contains('header')) {
            $fields->push(
                Select::make('Header', 'header')
                    ->options(fn () => Section::where('layout', 'header')->get()->pluck('name', 'id')->prepend('Create New', 'create_new'))
                    ->rules(['required'])->default('create_new')
            );
        }

        if ($sections->contains('footer')) {
            $fields->push(
                Select::make('Footer', 'footer')
                    ->options(fn () => Section::where('layout', 'footer')->get()->pluck('name', 'id')->prepend('Create New', 'create_new'))
                    ->rules(['required'])->default('create_new')
            );
        }

        return $fields->toArray();
    }
}
