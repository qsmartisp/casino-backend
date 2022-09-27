<?php

namespace App\Nova\Actions\VerificationRequest;

use App\Enums\Verification\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Unverify extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Unverify';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $user = $model->user;
            $user->verification_status = Type::Unverified->value;
            $user->save();

            $model->status = Type::Unverified->value;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
