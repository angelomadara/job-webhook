<?php

use App\Models\RegionVamCode;
use Illuminate\Database\Seeder;

class GreatBritainVamCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
        * Großbritannien (GB)
        * Avon, Gloucestershire, Wiltshire	85
		* Bedfordshire, Hartfordshire	86
		* Berkshire, Buckinghamshire, Oxfordshire	87
		* Borders, Central, Fife, Lothian, Tayside (Schottland)	88
		* Cheshire	89
		* Cleveland, Durham	90
		* Cornwall, Devon	92
		* Clwyd, Dyfed, Gwynedd, Powys (Wales)	91
		* Cumbria	93
		* Derbyshire, Nottinghamshire	94
		* Dorset, Somerset	95
		* Dumfries-Galloway, Strathclyde (Schottland)	96
		* East Anglia (Cambridgeshire, Norfolk, Suffolk)	97
		* Essex	98
		* Grampian (Schottland)	99
		* Greater London	100
		* Greater Manchester	101
		* Gwent, Mid-South-West-Glamorgan (Wales)	102
		* Hampshire, Isle of Wight	103
		* Humberside	104
		* Hereford-Worcester, Warwickshire	105
		* Highlands, Islands (Scotland)	106
		* Kent	107
		* Lancashire	108
		* Leicestershire, Northamptonshire	109
		* Lincolnshire	110
		* Merseyside	111
		* North Yorkshire	112
		* Northern Ireland	113
		* Northumberland, Tyne and Wear	114
		* Shropshire, Staffordshire	115
		* South Yorkshire	116
		* Surrey, East-West Sussex	117
		* West Midlands (County)	118
		* West Yorkshire	119
        */

        RegionVamCode::insert([
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'avon, gloucestershire, wiltshire',
                'vam_code'=> '85'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'bedfordshire, hartfordshire',
                'vam_code'=> '86'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'berkshire, buckinghamshire, oxfordshire',
                'vam_code'=> '87'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'borders, central, fife, lothian, tayside (scotland)',
                'vam_code'=> '88'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'cheshire',
                'vam_code'=> '89'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'cleveland, durham',
                'vam_code'=> '90'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'cornwall, devon',
                'vam_code'=> '92'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'clwyd, dyfed, gwynedd, powys (wales)',
                'vam_code'=> '91'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'cumbria',
                'vam_code'=> '93'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'derbyshire, nottinghamshire',
                'vam_code'=> '94'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'dorset, somerset',
                'vam_code'=> '95'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'dumfries-galloway, strathclyde (schottland)',
                'vam_code'=> '96'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'east anglia (cambridgeshire, norfolk, suffolk)',
                'vam_code'=> '97'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'essex',
                'vam_code'=> '98'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'grampian (schottland)',
                'vam_code'=> '99'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'greater london',
                'vam_code'=> '100'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'greater manchester',
                'vam_code'=> '101'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'gwent, mid-south-west-glamorgan (wales)',
                'vam_code'=> '102'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'hampshire, isle of wight',
                'vam_code'=> '103'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'humberside',
                'vam_code'=> '104'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'hereford-worcester, warwickshire',
                'vam_code'=> '105'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'highlands, islands (schottland)',
                'vam_code'=> '106'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'kent',
                'vam_code'=> '107'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'lancashire',
                'vam_code'=> '108'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'leicestershire, northamptonshire',
                'vam_code'=> '109'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'lincolnshire',
                'vam_code'=> '110'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'merseyside',
                'vam_code'=> '111'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'north yorkshire',
                'vam_code'=> '112'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'north ireland',
                'vam_code'=> '113'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'northumberland, tyne and wear',
                'vam_code'=> '114'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'shropshire, staffordshire',
                'vam_code'=> '115'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'south yorkshire',
                'vam_code'=> '116'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'surrey, south west sussex',
                'vam_code'=> '117'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'west midlands (country)',
                'vam_code'=> '118'
            ],
            [
                'land'=> 'großbritannien',
                'iso_code'=> 'gb',
                'region'=> 'west yorkshire',
                'vam_code'=> '119'
            ]
        ]);
    }
}
