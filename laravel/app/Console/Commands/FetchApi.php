<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Character;
use Illuminate\Support\Facades\Http;

class FetchApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commande de récupération des données depuis l\'API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Récupération des 100 premiers personnages depuis l\'API Rick et Morty...');

        $totalPagesNeeded = 5;

        $genderMapping = [
            'Male' => 'Masculin',
            'Female' => 'Féminin',
            'unknown' => 'Inconnu'
        ];

        $statusMapping =  [
            'Alive' => 'En vie',
            'Dead' => 'Décédé',
            'unknown' => 'Inconnu'
        ];

        for ($page = 1; $page <= $totalPagesNeeded; $page++) {
            $response = Http::get("https://rickandmortyapi.com/api/character/?page={$page}");

            if ($response->successful()) {
                $results = $response->json()['results'];

                foreach ($results as $character) {
                    $nameParts = explode(' ', $character['name'], 2);
                    if (count($nameParts) === 2) {
                        list($first_name, $last_name) = $nameParts;
                    } else {
                        $first_name = $nameParts[0];
                        $last_name = "";
                    }

                    $gender = $genderMapping[$character['gender']] ?? 'Inconnu';
                    $status = $statusMapping[$character['status']] ?? 'Inconnu';

                    Character::UpdateOrCreate(
                        ['id' => $character['id']],
                        [
                            'picture_url' => $character['image'],
                            'last_name' => $last_name,
                            'first_name' => $first_name,
                            'species' => $character['species'],
                            'gender' => $gender,
                            'status' => $status,
                            'origin' => $character['origin']['name'],
                            'episodes' => json_encode($character['episode']),
                        ]
                    );
                }
                $this->info("succes");
            } else {
                $this->error("echec de la recuperation des données");
            }
        }
    }
}
