<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadExcelTemplate extends Action
{
    use InteractsWithQueue, Queueable;

    public $type;

    public function name(): string
    {
        return __('Download File Template');
    }

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($this->type === 'user') {
            return Action::download('/storage/file_example_user.xlsx', 'file_mau_NLD.xlsx');
        }

        if ($this->type === 'question') {
            return Action::download('/storage/file_example_question.xlsx', 'file_mau_cau_hoi.xlsx');
        }

        if ($this->type === 'occupational-certificate') {
            return Action::download('/storage/the_atld.xlsx', 'the_atld.xlsx');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }

    public function setType(mixed $type)
    {
       $this->type = $type;

       return $this;
    }
}
