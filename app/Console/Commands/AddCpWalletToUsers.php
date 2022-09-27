<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Local\Repositories\WalletRepository;
use Illuminate\Console\Command;
use JsonException;
use RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;

class AddCpWalletToUsers extends Command
{
    protected $signature = 'users:add:cp';

    protected $description = 'Add CP wallet for users';

    protected static ProgressBar $bar;

    public function __construct(
        protected WalletRepository $walletRepository,
    ) {
        parent::__construct();
    }

    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function handle(): int
    {
        $bar = $this->getOutput()->createProgressBar();

        $users = User::all();

        if ($users->count() > 0) {
            $this->getOutput()->title("Add CP wallet to users [" . $users->count() . "]");
        }

        $bar->setMaxSteps($users->count());

        foreach ($users as $user) {
            if (!$this->walletRepository->isSlugExist($user, 'cp')) {
                $this->walletRepository->createWalletCp($user);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info('Completed!');

        return 0;
    }

}
