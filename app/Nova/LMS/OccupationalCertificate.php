<?php

namespace App\Nova\LMS;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use App\Nova\Filters\CertificateEndTimeFilter;
use App\Nova\Filters\CertificateStartTimeFilter;
use App\Nova\Filters\DepartmentCertificateFilter;
use App\Nova\Filters\GroupUserCertificateFilter;
use App\Nova\Filters\PositionCertificateFilter;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Carbon\Carbon;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class OccupationalCertificate extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = Certificate::class;
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'card_info->name',
        'user.employee_code',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', CertificateConstant::OCCUPATIONAL_SAFETY);
    }

    public static function label(): string
    {
        return __('Occupational Certificate');
    }

    public function title()
    {
        return __('Occupational Certificate');
    }

    public function fieldsForIndex()
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Certificate ID'), 'certificate_id'),
            Text::make(__('Name User'), 'card_info->name', function () {
                return '<a class="link-default" href="' .
                    sprintf('%s/resources/%s/%d', config('nova.path'), User::uriKey(), $this->user_id) . '">' .
                    $this->card_info['name'] . ' </a>';
            })->asHtml()->sortable(),
            DateTime::make(__('Date Of Birth'), 'card_info->dob')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Text::make(__('Employee Code'), 'user.employee_code')->sortable(),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @throws HelperNotSupported
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            MediaHubField::make(__('Avatar'), 'card_info->avatar_id')
                ->defaultCollection('users'),
            Text::make(__('Certificate ID'), 'certificate_id'),
            Text::make(__('Name User'), 'card_info->name', function () {
                return '<a class="link-default" href="' .
                    sprintf('%s/resources/%s/%d', config('nova.path'), User::uriKey(), $this->user_id) . '">' .
                    $this->card_info['name'] . ' </a>';
            })->asHtml()->sortable(),
            DateTime::make(__('Date Of Birth'), 'card_info->dob')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Text::make(__('Employee Code'), 'user.employee_code')->sortable(),
            Text::make(__('Position '), 'card_info->position'),
            Text::make(__('Department'), 'card_info->department'),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [
            (new CertificateStartTimeFilter()),
            (new CertificateEndTimeFilter()),
            (new GroupUserCertificateFilter())->singleSelect(),
            (new DepartmentCertificateFilter())->singleSelect(),
            (new PositionCertificateFilter())->singleSelect(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
