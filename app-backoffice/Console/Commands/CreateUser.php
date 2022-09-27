<?php

namespace AppBackoffice\Console\Commands;

use AppBackoffice\Services\Local\Repositories\BackofficeUserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'backoffice:user:create';

    protected $description = 'Create backoffice user';

    /**
     * @throws \JsonException
     */
    public function handle(BackofficeUserRepository $userRepository): int
    {
        $email = $this->ask('Please enter email');
        $password = $this->secret('Please enter password');

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => ['required', 'email', 'unique:backoffice_users,email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->alert('Backoffice user not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $userRepository->store([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info(PHP_EOL . 'Backoffice user was created.');

        return 0;
    }

}
