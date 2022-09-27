<?php

namespace App\Nova;

use App\Enums\Verification\Type;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Verificated extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\VerificationRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['user', 'files'];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Waiting verification users';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Waiting verification user';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('status', Type::Waiting->value);
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Email', 'user.email'),

            Stack::make('Files', [
                Text::make('')->resolveUsing(function () {
                    $str = '';
                    foreach ($this->files as $file) {
                        $str .= '<strong>' . mb_strtoupper(\App\Enums\File\Type::from($file->type)->value) .':</strong> ';
                        $str .= '<a href="/storage/'.$file->path.'" target="_blank">'.$file->original_name.'</a></BR>';
                    }
                    return $str;
                })->asHtml(),
            ]),

            DateTime::make('Created At')
                ->onlyOnIndex(),

            Badge::make('Verification', 'status')->map([
                Type::Unverified->value => 'warning',
                Type::Waiting->value => 'danger',
                Type::Verified->value => 'success',
            ]),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\VerificationRequest\Verify::make()
                ->canSee(function () {
                    return true;
                })->canRun(function () {
                    return true;
                })
                ->showOnTableRow(),

            Actions\VerificationRequest\Unverify::make()
                ->canSee(function () {
                    return true;
                })->canRun(function () {
                    return true;
                })
                ->showOnTableRow(),
        ];
    }

}
