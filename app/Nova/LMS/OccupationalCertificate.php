<?php

namespace App\Nova\LMS;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use App\Nova\Actions\DownloadExcelTemplate;
use App\Nova\Actions\DownloadPDFCertificate;
use App\Nova\Actions\ImportOccupationalCertificate;
use App\Nova\Actions\ImportUser;
use App\Nova\Filters\CertificateEndTimeFilter;
use App\Nova\Filters\CertificateExpirationDateFilter;
use App\Nova\Filters\CertificateIssueDateFilter;
use App\Nova\Filters\CertificateStartTimeFilter;
use App\Nova\Filters\DepartmentCertificateFilter;
use App\Nova\Filters\GroupUserCertificateFilter;
use App\Nova\Filters\PositionCertificateFilter;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Carbon\Carbon;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
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
        return $query->with('user')->where('type', CertificateConstant::OCCUPATIONAL_SAFETY);
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
            BelongsTo::make(__('Users'), 'user', User::class),
            Date::make(__('Training start date'), 'complete_from')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Training end date'), 'complete_to')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Issue date'), 'released_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Expiration date'), 'effective_to')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
        ];
    }

    public function fieldsForUpdate()
    {
        return [
//            Number::make(__('Card number'), 'card_id')->required(),
            Textarea::make(__('Training course name'), 'card_info->description')->required(),
            Date::make(__('Training start date'), 'complete_from')->required(),
            Date::make(__('Training end date'), 'complete_to')->required(),
            Date::make(__('Issue date'), 'released_at')->required(),
            Date::make(__('Expiration date'), 'effective_to')->required(),
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
            MediaHubField::make(__('Avatar'), 'user->avatar')
                ->defaultCollection('users'),
            Text::make(__('Certificate ID'), 'certificate_id'),
            BelongsTo::make(__('Users'), 'user', User::class),
            Text::make(__('Position '), 'user->position'),
            Text::make(__('Department'), 'user->department'),
            Textarea::make(__('Training course name'), 'card_info->description'),
            Date::make(__('Training start date'), 'complete_from')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Training end date'), 'complete_to')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Issue date'), 'released_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
            Date::make(__('Expiration date'), 'effective_to')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
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
            (new CertificateIssueDateFilter()),
            (new CertificateExpirationDateFilter()),
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
        return [
            (new DownloadExcelTemplate())->standalone()
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->onlyOnIndex()
                ->confirmText(__('Are you sure you want to download'))
                ->setType('occupational-certificate'),
            (new ImportOccupationalCertificate(CertificateConstant::OCCUPATIONAL_SAFETY))->standalone()
                ->canSee(fn ($request) => $request->user()->can('viewAny', \App\Models\Certificate::class))
                ->canRun(fn ($request) => $request->user()->can('create', \App\Models\Certificate::class)),
            new DownloadPDFCertificate()
        ];
    }
}
