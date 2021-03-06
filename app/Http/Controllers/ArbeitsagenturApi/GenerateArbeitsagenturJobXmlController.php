<?php

namespace App\Http\Controllers\ArbeitsagenturApi;

use App\Http\Controllers\Controller;
use App\Models\ArbeitsagenturXmlFile;
use App\Models\PostedArbeitsagenturJob;
use App\Models\RegionVamCode;
use Carbon\Carbon;
use FluidXml\FluidXml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use kzorluoglu\Arbeitsagentur\XMLJob;
use stdClass;

class GenerateArbeitsagenturJobXmlController extends Controller
{

    public function __invoke(Request $request)
    {
        $now = Carbon::now()->toRfc3339String(true);
        $limit = $request->limit; // job limiter per day
        $date_now = date('Y-m-d');

        $query = "SELECT `rj`.*
        FROM `received_jobs` as rj
        LEFT JOIN `posted_arbeitsagentur_jobs` AS paj ON `paj`.`received_job_id` = `rj`.`id`
        WHERE `paj`.`received_job_id` IS NULL
        AND `rj`.`date_posted` <= ?
        AND LENGTH(`rj`.`description`) <= 3970
        AND LENGTH(`rj`.`street_address`) <= 30
        AND LENGTH(`rj`.`company`) <= 30
        AND LENGTH(`rj`.`title`) <= 60
        AND `rj`.`valid_through` >= ?
        AND `rj`.`postal_code_address` != ''
        AND `rj`.`postal_code_address` IS NOT NULL
        AND `rj`.`region_address` != ''
        AND `rj`.`region_address` IS NOT NULL
        AND `rj`.`employment_type` IS NOT NULL
        AND `rj`.`ldjson` IS NOT NULL
        ORDER BY `rj`.`date_posted` DESC
        LIMIT ?";
        $jobs_to_send = DB::select($query,[$date_now,$date_now,$limit]);

        // \Log::info($jobs_to_send);
        // return 'false';

        $JobPositionPosting = [];
        $job_id_array = [];

        $regionVamCode = new RegionVamCode();

        foreach($jobs_to_send as $key => $job){
            $ldjson = json_decode($job->ldjson, true);

            $region = $job->region_address == 'NRW' ? 'nw' : strtolower($job->region_address); // NRW = nordrhein-westfalen = NW
            $region = $region == 'nds' ? 'ni' : $region; // NDS = niedersachsen = NI
            $region = $region == 'sa' ? 'st' : $region; // SA = sachsen-anhalt = ST
            $region = $region == 'berlin' ? 'be' : $region; // if the region is Berlin, change it to be
            $vam_code_result = $regionVamCode->where('iso_31662_code',$region)->first();
            $vam_code = $vam_code_result ? $vam_code_result->vam_code : '00';
            // $vam_code = $region;

            $JobPositionPosting[] = [
                'JobPositionPosting' => [
                    'JobPositionPostingId' => env('ARBEITSAGENTUR_ALLIANCE_NUMBER') . '-JID' . $job->id . '-S',
                    'HiringOrg' => [
                        'HiringOrgName' => htmlspecialchars($job->company, ENT_XML1, 'UTF-8'), // max-length = 30
                        // 'HiringOrgId' => '',
                        'ProfileWebSite' => $ldjson['hiringOrganization']['sameAs'],
                        // 'HiringOrgSize' => '',
                        // 'Industry' => [
                        //     'NAICS' => '',
                        // ],
                        'Contact' => [
                            // 'Salutation' => 1, // 1 = Herr, 2 = Frau
                            // 'GivenName' => 'NA', // Vorname, name :optional
                            'FamilyName' => 'NA', // (:required) The information may only contain letters. At least one character is required. Three or more identical letters at the beginning are not permitted. In addition, up to two digits can be used after a space at the end of the name. There must be no space between the digits. A hyphen between name parts may only be used without spaces.
                            // 'NamePrefix' => '',
                            // 'Title' => '', // e.g. Dr. Prof.
                            // 'PositionTitle' => '',
                            // 'EMail' => '',
                            // 'GeneralWebSite' => '',
                        ],
                    ],
                    'PostDetail' => [
                        'StartDate' => "{$job->date_posted}", // StartDate is optional and determine when the vacancy application process can start
                        'EndDate' => "{$job->valid_through}",
                        'LastModificationDate' => $now, // LastModificationDate is required and should provide an date time object like the given example
                        'Status' => 1, // Status is required here
                        'Action' => 1, // Action is required here. 1 = create, 2 = update, 3 = delete
                        'SupplierId' => env('ARBEITSAGENTUR_SUPPLIER_ID'), // SupplierId  is required here
                        'SupplierName' => 'Jobtensor', // SupplierName is required
                        'PostedBy' => [
                            'Contact' => [
                                'Company' => htmlspecialchars($job->company, ENT_XML1, 'UTF-8'),
                                // 'Salutation' => '',
                                // 'Title' => '',
                                // 'GivenName' => '',
                                // 'NamePrefix' => '',
                                // 'FamilyName' => '',
                                // 'PositionTitle' => '',
                                'PostalAddress' => [
                                    'CountryCode' => "{$job->country_address}", // iso code of specific country
                                    'PostalCode' => "{$job->postal_code_address}", // zip code of specific city
                                    'Region' => $vam_code, // Region is the Region VAM-Code provided by the federal employment agency
                                    'Municipality' => "{$job->local_address}", // Municipality city name
                                    'StreetName' => preg_replace("/[-|:\[\]]+/","",$job->street_address), // maximum character length of street name is 30
                                ],
                                // 'VoiceNumber' => [
                                //     'IntlCode' => '', // IntlCode is required if VoiceNumber is provided
                                //     'AreaCode' => '', // AreaCode is optional, can be used in contact data in your application return channe if you set <userContactData>1</useContactData> in config.xml
                                //     'TelNumber' => '', // TelNumber is required if VoiceNumber is provided
                                // ],
                                // 'EMail' => '', // EMail is optional, can be used in contact data in your application return channel if you set <useContactData>1</useContactData> in config.xml
                                'JobContactWebSite' => $job->url, // JobContactWebSite is optional and can be used to provide your application form or redirect to your online vacancy
                            ]
                        ],
                        // 'BASupervision' => 0,
                        // 'SupervisionDesired' => 0,
                    ],
                    'JobPositionInformation' => [
                        'JobPositionTitle' => [
                            // TitleCode is an ID of the desired profession for your vacancy
                            'TitleCode' => '1017', // 1017 = work, 9065 = training
                            // 'Degree' => 15
                        ],
                        // JobPositionTitleDescription maximum characters is 60
                        'JobPositionTitleDescription' => htmlspecialchars($job->title, ENT_XML1, 'UTF-8'), // JobPositionTitleDescription this is the main title of your vacancy
                        'JobOfferType' => 1, // 1 = employee, 2 = freelancer, 4 = apprentice, 34 = Trainee, 36 = skilled employee, 37 = executives, 38 = temporary help, 40 = artist
                        'SocialInsurance' => 1, // SocialInsurance describes if your vacancy is subject to social insurance contributions
                        // 'EducationType' => 1, // A type of training (Education Type) may only be transmitted for job offers of the type "Training / Dual Studies" (JobOfferType = 4).
                        // 'DegreeType' => 1, // A degree type may only be submitted for job offers of the type "Training / Dual Studies" (JobOfferType = 4) with the type of training "Dual Studies" (EducationType = 1).
                        // Objective maximum characters is 4000
                        // Objective describes details about your vacancy
                        // 'Objective' => '<![CDATA['.$ldjson['description'].']]>',
                        'Objective' => htmlspecialchars(strip_tags($job->description), ENT_XML1, 'UTF-8'),
                        'EducationAuthorisation' => 0, // EducationAuthorisation is required
                        // JobPositionDescription  Gives Details about location
                        'JobPositionDescription' => [
                            'JobPositionLocation' => [
                                'Location' => [
                                    'CountryCode' => $job->country_address, // iso code of specific country
                                    'PostalCode' => $job->postal_code_address, // zip code of specific city
                                    'Region' => $vam_code, // Region is the Region VAM-Code provided by the federal employment agency
                                    'Municipality' => $job->local_address, // Municipality city name
                                    'StreetName' => preg_replace("/[-|:\[\]]+/","",$job->street_address),
                                ],
                            ],
                            // 'Application' => [
                                // 'KindOfApplication' => '',
                                // 'ApplicationStartDate' => $date,
                                // 'ApplicationEndDate' => $now,
                                // 'EnclosuresRequired' => '',
                            // ],
                            // 'MiniJob' => '', // MiniJob Describes if vacancy is a minijob this is optional, social insurance should not given if minijob is set
                            'Classification' => [
                                'Schedule' => [
                                    'WorkingPlan' => 1, // 1 = fulltime, 3 = part-time
                                    // 'HoursPerWeek' => 40,
                                ],
                                // 'Duration' => [
                                //     'TermLength' => '',
                                //     'TermDate' => "{$now}"
                                // ]
                            ]
                        ],
                        'JobPositionRequirements' => [
                            'QualificationsRequired' => [
                                'EducationQualifs' => [
                                    // '1' =>  no education
                                    // '2' =>  Lower Secondary Education - "Hauptschule"
                                    // '3' =>  Secondary Education - "Mittlere Reife"
                                    // '4' =>  college qualification - "Fachhochschulreife"
                                    // '5' =>  vocational baccalaureate diploma -  "Fachabitur"
                                    // '6' =>  Higher School Certificate - "Abitur"
                                    // '7' =>  college degree - "Abschluss Fachhochschule"
                                    // '9' => university degree - "Wissenschaftliche Hochschule / Universit??t"
                                    // '11' => special school degree - "Abschluss der F??rderschule"
                                    // '12' => Secondary school certificate- "Qualifizierender Hauptschulabschluss"
                                    // '13' => high school dropouts with secondary education Abg??nger Klasse 11-13
                                    // '14' => university without degree - Hochschule ohne Abschluss
                                    // '15' => null // not relevant
                                    'EduDegree' => 15,
                                    // 'EduDegreeRequired' => ''
                                ],
                                // 'Language' => [
                                //     'LanguageName' => '',
                                //     'LanguageLevel' => ''
                                // ],
                                // 'SkillQualifs' => [
                                //     'HardSkill' => [
                                //         'SkillName' => '',
                                //         'SkillLevel' => ''
                                //     ]
                                // ],
                                // 'Mobility' => [
                                //     'DrivingLicence' => [
                                //         'DrivingLicenceName' => '',
                                //         'DrivingLicenceRequired' => ''
                                //     ],
                                // ]
                            ],
                            // 'TravelRequired' => '', //  Values: not required (1), temporary (2), without restriction(3)
                            // 'Handicap' => ''
                        ],
                        'NumberToFill' => 1,
                        'AssignmentStartDate' => date("Y-m-d"), // AssignmentStartDate is the date when your vacancy will be available
                        // 'AssignmentEndDate' => "{$now}"
                        // 'EmploymentType' => 2, // EmploymentType allowed values: no temporary employment/employment exchange (0), personnel services/temporary employment (1),  recruitment agency/employment exchange (2)
                    ]
                ]
            ];

            $job_id_array[] = [
                'received_job_id'=>$job->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];
        }

        // \Log::info($JobPositionPosting);
        // return 'false';

        $job = new FluidXml(null,['root' => 'HRBAXMLJobPositionPosting']);
        $job->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $job->add([
            'Header'  => [
                'SupplierId' => env('ARBEITSAGENTUR_SUPPLIER_ID'),
                'Timestamp' => $now,
                'Amount' => 1,
                'TypeOfLoad' => "F",
                // 'PdfPreviewOnly' => 0,
            ],
            'Data' => $JobPositionPosting
        ]);

        /**
         * create storage path if not exist
         */
        Storage::disk('local')->makeDirectory('public/arbeitsagentur/xml');
        /**
         * save job to storage folder
         * DSP000517500_2022-01-06_08-40-00
         */
        $filename = "DS".env("ARBEITSAGENTUR_SUPPLIER_ID")."_".date('Y-m-d_H-i-s').".xml";
        // save job to the storage folder
        $job->save(storage_path("app/public/arbeitsagentur/xml/{$filename}"));
        // get job directory
        $file = storage_path("app/public/arbeitsagentur/xml/{$filename}");

        $key = storage_path("key/Zertifikat-268fb5.pem");
        $password = "";
        if($request->pass == 'true'){
            $password = ":".env('ARBEITSAGENTUR_PEM_PASS');
        }

        /**
         * send job (xml file) to arbeitsagentur using curl with certificate and certificate password
         * +------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
         * | >_ curl --cert certificate-<id>.pem:pem_password -F upload=@<path_of_the_xml_file>/DS<partner_number>_<time_stamp>.XML https://hrbaxml.arbeitsagentur.de/in/upload.php |
         * +------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
         */
        $curl_response = "";
        $curl_response = shell_exec("curl --cert {$key}{$password} -F upload=@{$file} https://hrbaxml.arbeitsagentur.de/in/upload.php");

        $curl_response = strip_tags(str_replace(["</TITLE>","</title>"],": ",$curl_response));

        sendResponseToSlack("HRBRXML RESPONSE: `{$curl_response}`");

        PostedArbeitsagenturJob::insert($job_id_array);

        ArbeitsagenturXmlFile::create([
            'file' => $file,
            'response' => $curl_response
        ]);
    }
}
