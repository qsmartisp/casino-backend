<?php

namespace App\Nova;

use App\Enums\Verification\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'email',
    ];

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToView(Request $request)
    {
        return true;
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
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                //->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                //->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            Badge::make('Verification', 'verification_status')->map([
                Type::Unverified->value => 'warning',
                Type::Waiting->value => 'danger',
                Type::Verified->value => 'success',
            ]),

            Date::make('Self Exclusion', 'self_exclusion_until')
                ->readonly(),

            Boolean::make('Active', 'is_disabled')
                ->trueValue('0')
                ->falseValue('1')
                ->fillUsing(function ($request, $model) {
                    $model->disabled_at = $this->is_disabled ? null : Carbon::now();
                }),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            //new Lenses\WaitingVerificationUsers,
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\User\Disable::make()
                ->canSee(function ($request) {
                    return true;
                })->canRun(function ($request, $user) {
                    return true;
                })
                ->showOnTableRow(),

            Actions\User\Enable::make()
                ->canSee(function ($request) {
                    return true;
                })->canRun(function ($request, $user) {
                    return true;
                })
                ->showOnTableRow(),

            Actions\User\RemoveSelfExclusion::make()
                ->canSee(function ($request) {
                    return true;
                })->canRun(function ($request, $user) {
                    return true;
                })
                ->showOnTableRow(),

            Actions\User\SendEmailToResetPassword::make()
                ->canSee(function ($request) {
                    return true;
                })->canRun(function ($request, $user) {
                    return true;
                })
                ->showOnTableRow(),
        ];
    }
}
