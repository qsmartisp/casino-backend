<?php

namespace Database\Seeders;

use App\Enums\File\Type;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'slug' => 'boominggames',
                'name' => 'booming',
                'extension' => 'png',
                'size' => '8785',
            ],
            [
                'slug' => 'amaticdirect',
                'name' => 'Amatic',
                'extension' => 'png',
                'size' => '4247',
            ],
            [
                'slug' => 'microgaming',
                'name' => 'microgaming',
                'extension' => 'png',
                'size' => '11459',
            ],
            [
                'slug' => 'playsondirect',
                'name' => 'Playson',
                'extension' => 'png',
                'size' => '16670',
            ],
            [
                'slug' => 'pragmaticplay',
                'name' => 'Pragmaticplay',
                'extension' => 'png',
                'size' => '11488',
            ],
            [
                'slug' => 'yggdrasil',
                'name' => 'ygg',
                'extension' => 'png',
                'size' => '17542',
            ],
            [
                'slug' => 'gameart',
                'name' => 'game_art',
                'extension' => 'png',
                'size' => '11459',
            ],
            [
                'slug' => 'elkstudios',
                'name' => 'Elk_studio',
                'extension' => 'png',
                'size' => '5900',
            ],
            [
                'slug' => 'isoftbet',
                'name' => 'ISoftBet',
                'extension' => 'png',
                'size' => '7613',
            ],
            [
                'slug' => 'pragmaticplaylive',
                'name' => 'Pragmaticplay',
                'extension' => 'png',
                'size' => '11488',
            ],
            [
                'slug' => 'playngo',
                'name' => 'playngo',
                'extension' => 'png',
                'size' => '16670',
            ],
            [
                'slug' => 'evolution',
                'name' => 'Evalution',
                'extension' => 'png',
                'size' => '13363',
            ],
            [
                'slug' => 'thunderkick',
                'name' => 'thunderkick',
                'extension' => 'png',
                'size' => '13864',
            ],
            [
                'slug' => 'ezugi',
                'name' => 'Ezugi_small',
                'extension' => 'png',
                'size' => '11720',
            ],
            [
                'slug' => 'playtechcasinogames',
                'name' => 'PlayTech_casino',
                'extension' => 'png',
                'size' => '11855',
            ],
            [
                'slug' => 'platipus',
                'name' => 'Platipus',
                'extension' => 'png',
                'size' => '5662',
            ],
            [
                'slug' => 'pushgaming',
                'name' => 'pushgaming',
                'extension' => 'png',
                'size' => '4581',
            ],
            [
                'slug' => 'blueprint',
                'name' => 'Blue_print',
                'extension' => 'png',
                'size' => '55180',
            ],
            [
                'slug' => 'quickspin',
                'name' => 'quick_spin',
                'extension' => 'png',
                'size' => '4043',
            ],
            [
                'slug' => 'bgaming',
                'name' => 'BGgaming',
                'extension' => 'png',
                'size' => '4153',
            ],

        ];


        DB::table('files')->where('type', Type::Provider->value)->delete();
        DB::table('file_identity')->where('identity_type', Provider::class)->delete();

        foreach ($data as $datum) {
            $provider = Provider::query()->where('slug', $datum['slug'])->first();
            if ($provider) {
                $id = DB::table('files')->insertGetId([
                    'type' => Type::Provider->value,
                    'name' => $datum['name'],
                    'original_name' => $datum['name'] . '.' . $datum['extension'],
                    'extension' => $datum['extension'],
                    'mime_type' => 'image/' . $datum['extension'],
                    'size' => $datum['size'],
                    'path' => 'providers/' . $datum['name'] . '.' . $datum['extension'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $provider->images()->attach($id, ['identity_type' => Provider::class]);
            }
        }

    }
}
