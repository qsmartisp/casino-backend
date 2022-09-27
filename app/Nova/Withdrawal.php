<?php

namespace App\Nova;

use App\Enums\Transaction\System;
use App\Enums\Withdrawal\Status;
use App\Nova\Actions\Withdrawal\Accept;
use App\Nova\Actions\Withdrawal\Reject;
use App\Services\Local\Repositories\WithdrawalRepository;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Sixlive\TextCopy\TextCopy;

/**
 * @mixin \App\Models\Withdrawal
 */
class Withdrawal extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Withdrawal::class;

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
    public static $with = ['transaction'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('nova/resources.withdrawal.label');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('nova/resources.withdrawal.singularLabel');
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
            ID::make(__('ID'), 'id')->sortable(),

            MorphTo::make(__('nova/resources.withdrawal.fields.transaction.user'), 'payable'),

            Badge::make(__('nova/resources.withdrawal.fields.transaction.status'), 'status')->map([
                Status::Pending->value => 'info',
                Status::Accepted->value => 'warning',
                Status::Sending->value => 'warning',
                Status::Finished->value => 'success',
                Status::Rejected->value => 'danger',
                Status::Failed->value => 'danger',
                null => 'null',
            ])->addTypes(['null' => 'display: none']),

            TextCopy::make(__('nova/resources.withdrawal.fields.transaction.address'), 'address'),

            Text::make(__('nova/resources.withdrawal.fields.transaction.coin'), 'coin'),

            Text::make(__('nova/resources.withdrawal.fields.transaction.amount'), function () {
                $amount = number_format(abs($this->transaction->amountFloat), 2, '.', '');

                return "{$amount} {$this->transaction->wallet->slug}";
            }),

            TextCopy::make(__('nova/resources.withdrawal.fields.transaction.txId'), 'tx_id'),

            Badge::make(__('nova/resources.withdrawal.fields.transaction.system'), 'system')->map([
                System::Estchange->value => 'warning',
                System::Coinbase->value => 'danger',
                null => 'null',
            ])->addTypes(['null' => 'display: none']),
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
        return [];
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
            Reject::make()
                ->canSee(function ($request) {
                    return true;
                })->canRun(function ($request, $user) {
                    return true;
                })->showOnTableRow(),
            Accept::make(resolve(WithdrawalRepository::class)) // todo: обсудить
                ->canSee(function ($request) {
                    return $this->status !== Status::Accepted;
                })->canRun(function ($request, $user) {
                    return $this->status !== Status::Accepted;
                })->showOnTableRow(),
        ];
    }
}
