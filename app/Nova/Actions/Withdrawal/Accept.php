<?php

namespace App\Nova\Actions\Withdrawal;

use App\Enums\Withdrawal\Status;
use App\Jobs\MakeWithdrawal;
use App\Models\Withdrawal;
use App\Services\Local\Helpers\QueueNameHelper;
use App\Services\Local\Repositories\WithdrawalRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Accept extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Accept';

    public function __construct(
        protected WithdrawalRepository $withdrawalRepository,
    ) {
    }

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection|Withdrawal[] $models
     *
     * @return void
     */
    public function handle(ActionFields $fields, Collection $models): void
    {
        foreach ($models as $model) {
            $this->withdrawalRepository->accept($model);

            dispatch(new MakeWithdrawal($model))
                ->onQueue(QueueNameHelper::job(MakeWithdrawal::class));
        }
    }
}
