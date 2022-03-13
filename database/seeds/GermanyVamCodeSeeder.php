<?php

use App\Models\RegionVamCode;
use Illuminate\Database\Seeder;

class GermanyVamCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegionVamCode::insert([
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'baden-württemberg',
                'vam_code'=> '01',
                'iso_31662_code'=> 'bw',
                'capital'=> 'stuttgart',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'bayern',
                'vam_code'=> '02',
                'iso_31662_code'=> 'by',
                'capital'=> 'munich',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'berlin',
                'vam_code'=> '03',
                'iso_31662_code'=> 'be',
                'capital'=> '',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'brandenburg',
                'vam_code'=> '04',
                'iso_31662_code'=> 'bb',
                'capital'=> 'potsdam',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'bremen',
                'vam_code'=> '05',
                'iso_31662_code'=> 'hb',
                'capital'=> 'bremen',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'hamburg',
                'vam_code'=> '06',
                'iso_31662_code'=> 'hh',
                'capital'=> '',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'hessen',
                'vam_code'=> '07',
                'iso_31662_code'=> 'he',
                'capital'=> 'wiesbaden',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'mecklenburg-vorpommern',
                'vam_code'=> '08',
                'iso_31662_code'=> 'mv',
                'capital'=> 'schwerin',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'niedersachsen',
                'vam_code'=> '09',
                'iso_31662_code'=> 'ni',
                'capital'=> 'hanover',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'nordrhein-westfalen',
                'vam_code'=> '10',
                'iso_31662_code'=> 'nw',
                'capital'=> 'düsseldorf',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'rheinland-pfalz',
                'vam_code'=> '11',
                'iso_31662_code'=> 'rp',
                'capital'=> 'mainz',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'saarland',
                'vam_code'=> '12',
                'iso_31662_code'=> 'sl',
                'capital'=> 'saarbrücken',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'sachsen',
                'vam_code'=> '13',
                'iso_31662_code'=> 'sn',
                'capital'=> 'dresden',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'sachsen-anhalt',
                'vam_code'=> '14',
                'iso_31662_code'=> 'st',
                'capital'=> 'magdeburg',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'schleswig-holstein',
                'vam_code'=> '15',
                'iso_31662_code'=> 'sh',
                'capital'=> 'keil',
            ],
            [
                'land'=> 'deutschland',
                'iso_code'=> 'de',
                'region'=> 'thüringen',
                'vam_code'=> '16',
                'iso_31662_code'=> 'th',
                'capital'=> 'erfurt',
            ]
        ]);
    }
}
