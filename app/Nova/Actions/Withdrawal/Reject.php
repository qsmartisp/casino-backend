<?php

namespace App\Nova\Actions\Withdrawal;

use App\Enums\Withdrawal\Status;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Reject extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Reject';

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
            $model->status = Status::Rejected;
            $model->save();
        }
    }
}
