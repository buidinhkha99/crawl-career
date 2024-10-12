<?php

namespace App\Nova\LMS\Certificates;

use App\Enums\CertificateConstant;
use App\Enums\UserGender;
use App\Models\Certificate;
use App\Nova\Actions\DownloadExcelTemplate;
use App\Nova\Actions\DownloadPDFElectricCertificate;
use App\Nova\Actions\DownloadPDFPaperCertificate;
use App\Nova\Actions\ImportCertificate;
use App\Nova\Filters\CertificateEndTimeFilter;
use App\Nova\Filters\CertificateIssueDateFromFilter;
use App\Nova\Filters\CertificateIssueDateToFilter;
use App\Nova\Filters\CertificateStartTimeFilter;
use App\Nova\Filters\DepartmentCertificateFilter;
use App\Nova\Filters\GroupUserCertificateFilter;
use App\Nova\Filters\LevelCertificateFilter;
use App\Nova\Filters\PositionCertificateFilter;
use App\Nova\Filters\ResultCertificateFilter;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Carbon\Carbon;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaMediaHub\Models\Media;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class PaperCertificate extends Resource
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
        return $query->with('user')->where('type', CertificateConstant::PAPER_SAFETY);
    }

    public static function label(): string
    {
        return __('Paper Certificate');
    }

    public function title()
    {
        return __('Paper Certificate');
    }

    public function fieldsForIndex()
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Certificate ID'), 'certificate_id'),
            BelongsTo::make(__('Users'), 'user', User::class),
            Text::make(__('Result training'), 'result'),
            Date::make(__('Issue date'), 'released_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            Number::make(__('Card number'), 'card_id')->rules('required', function($attribute, $value, $fail)  use ($request){
                $year = Carbon::parse($request->released_at)->year;
                if (Certificate::where('type', CertificateConstant::PAPER_SAFETY)
                    ->where('user_id', $this->user_id)
                    ->where('card_id', $value)
                    ->where('id', '!=', $this->id)
                    ->whereYear('released_at', $year)
                    ->exists()
                ) {
                    return $fail(__('Card number used in year :year', [
                        'year' => $year
                    ]));
                }
            }),
            Multiselect::make(__('Gender'), 'gender')
                ->options(['Nam' => 'Nam', 'Nữ' => 'Nữ', 'Khác' => 'Khác'])
                ->singleSelect()
                ->rules('required'),
            Date::make(__('Date Of Birth'), 'dob')->required(),
            Text::make(__('Nationality'), 'nationality')->required(),
            Text::make(__('CCCD/CMND'), 'cccd')->required(),
            Text::make(__('Group User'), 'group')->required(),
            Multiselect::make(__('Result training'), 'result')
                ->options(['Giỏi' => 'Giỏi', 'Khá' => 'Khá', 'Trung bình' => 'Trung bình'])
                ->singleSelect()
                ->rules('required'),
            Date::make(__('Training start date'), 'complete_from')->required(),
            Date::make(__('Training end date'), 'complete_to')->required(),
            Date::make(__('Expiration from'), 'effective_from')->required(),
            Date::make(__('Expiration to'), 'effective_to')->required(),
            Date::make(__('Issue date'), 'released_at')->required(),

            Panel::make(__('Setting Generate Certificate'), [
                Text::make(__('Work unit'), 'work_unit_printed')->default(fn () => __('Lào Cai'))->rules('required'),
                Text::make(__('Place'), 'place_printed')->default(fn () => __('Lào Cai'))->rules('required'),
                Text::make(__('Director Name'), 'director_name_printed')->rules('required'),
                MediaHubField::make(__('Signature Image'), 'signature_photo_printed')->required()
                    ->defaultCollection('setting-certificate')
                    ->rules(fn ($request) => [
                        function ($attribute, $value, $fail) {
                            $mime_types = collect(['image/jpeg', 'image/png']);
                            $media = Media::select('id', 'mime_type')->find($value);
                            if ($media && ! $mime_types->contains($media?->mime_type)) {
                                return $fail(__('The :attribute does not match the format :format.', [
                                    'attribute' => $attribute,
                                    'format' => $mime_types->join(', '),
                                ]));
                            }
                        },
                    ]),
            ]),
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
            Multiselect::make(__('Gender'), 'gender')
                ->options(['Nam' => 'Nam', 'Nữ' => 'Nữ', 'Khác' => 'Khác'])
                ->singleSelect()
                ->rules('required'),
            Date::make(__('Date Of Birth'), 'dob'),
            Text::make(__('Nationality'), 'nationality'),
            Text::make(__('CCCD/CMND'), 'cccd'),
            Text::make(__('Group User'), 'group'),
            Multiselect::make(__('Result training'), 'result')
                ->options(['Giỏi' => 'Giỏi', 'Khá' => 'Khá', 'Trung bình' => 'Trung bình'])
                ->singleSelect()
                ->rules('required'),
            Date::make(__('Training start date'), 'complete_from'),
            Date::make(__('Training end date'), 'complete_to'),
            Date::make(__('Expiration from'), 'effective_from'),
            Date::make(__('Expiration to'), 'effective_to'),
            Date::make(__('Issue date'), 'released_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('d/m/Y') : null),

            Text::make(__('Image back'), 'image_back_url')
                ->resolveUsing(function ($value) {
                    return '<img src="' . $value . '" style="max-width: 100%; height: auto;" alt="Hình ảnh" />';
                })
                ->asHtml(),
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
            (new CertificateIssueDateFromFilter()),
            (new CertificateIssueDateToFilter()),
            (new ResultCertificateFilter()),
//            (new CertificateStartTimeFilter()),
//            (new CertificateEndTimeFilter()),
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
                ->setType('paper-certificate'),
            (new ImportCertificate(CertificateConstant::PAPER_SAFETY))->standalone()
                ->canSee(fn ($request) => $request->user()->can('viewAny', \App\Models\Certificate::class))
                ->canRun(fn ($request) => $request->user()->can('create', \App\Models\Certificate::class))
                ->withName(__('Add paper certificate by excel file')),
            (new DownloadPDFPaperCertificate())->exceptOnDetail()
        ];
    }
}
