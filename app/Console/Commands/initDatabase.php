<?php

namespace App\Console\Commands;

use App\Models\HomepageContent;
use App\Models\Skill;
use App\Models\WorkExperience;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Spatie\Permission\PermissionRegistrar;

class initDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup initial database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->insertShieldPermissions();
        $this->insertHomepageContent();
        $this->insertWorkExperiences();
        $this->insertSkills();
    }

    protected function insertShieldPermissions(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Skill","View:Skill","Create:Skill","Update:Skill","Delete:Skill","Restore:Skill","ForceDelete:Skill","ForceDeleteAny:Skill","RestoreAny:Skill","Replicate:Skill","Reorder:Skill","ViewAny:WorkExperience","View:WorkExperience","Create:WorkExperience","Update:WorkExperience","Delete:WorkExperience","Restore:WorkExperience","ForceDelete:WorkExperience","ForceDeleteAny:WorkExperience","RestoreAny:WorkExperience","Replicate:WorkExperience","Reorder:WorkExperience","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","View:WebsiteCluster","View:HomepageContentPage","access_panel:_admin"]},{"name":"User","guard_name":"web","permissions":["ViewAny:Skill","View:Skill","Create:Skill","Update:Skill","Delete:Skill","Restore:Skill","ForceDelete:Skill","ForceDeleteAny:Skill","RestoreAny:Skill","Replicate:Skill","Reorder:Skill","ViewAny:WorkExperience","View:WorkExperience","Create:WorkExperience","Update:WorkExperience","Delete:WorkExperience","Restore:WorkExperience","ForceDelete:WorkExperience","ForceDeleteAny:WorkExperience","RestoreAny:WorkExperience","Replicate:WorkExperience","Reorder:WorkExperience","View:HomepageContentPage","access_panel:_admin"]}]';
        $directPermissions = '{"22":{"name":"ViewAny:FeatureSegment","guard_name":"web"},"23":{"name":"View:FeatureSegment","guard_name":"web"},"24":{"name":"Create:FeatureSegment","guard_name":"web"},"25":{"name":"Update:FeatureSegment","guard_name":"web"},"26":{"name":"Delete:FeatureSegment","guard_name":"web"},"27":{"name":"Restore:FeatureSegment","guard_name":"web"},"28":{"name":"ForceDelete:FeatureSegment","guard_name":"web"},"29":{"name":"ForceDeleteAny:FeatureSegment","guard_name":"web"},"30":{"name":"RestoreAny:FeatureSegment","guard_name":"web"},"31":{"name":"Replicate:FeatureSegment","guard_name":"web"},"32":{"name":"Reorder:FeatureSegment","guard_name":"web"},"33":{"name":"ViewAny:Author","guard_name":"web"},"34":{"name":"View:Author","guard_name":"web"},"35":{"name":"Create:Author","guard_name":"web"},"36":{"name":"Update:Author","guard_name":"web"},"37":{"name":"Delete:Author","guard_name":"web"},"38":{"name":"Restore:Author","guard_name":"web"},"39":{"name":"ForceDelete:Author","guard_name":"web"},"40":{"name":"ForceDeleteAny:Author","guard_name":"web"},"41":{"name":"RestoreAny:Author","guard_name":"web"},"42":{"name":"Replicate:Author","guard_name":"web"},"43":{"name":"Reorder:Author","guard_name":"web"},"44":{"name":"ViewAny:Category","guard_name":"web"},"45":{"name":"View:Category","guard_name":"web"},"46":{"name":"Create:Category","guard_name":"web"},"47":{"name":"Update:Category","guard_name":"web"},"48":{"name":"Delete:Category","guard_name":"web"},"49":{"name":"Restore:Category","guard_name":"web"},"50":{"name":"ForceDelete:Category","guard_name":"web"},"51":{"name":"ForceDeleteAny:Category","guard_name":"web"},"52":{"name":"RestoreAny:Category","guard_name":"web"},"53":{"name":"Replicate:Category","guard_name":"web"},"54":{"name":"Reorder:Category","guard_name":"web"},"55":{"name":"ViewAny:Post","guard_name":"web"},"56":{"name":"View:Post","guard_name":"web"},"57":{"name":"Create:Post","guard_name":"web"},"58":{"name":"Update:Post","guard_name":"web"},"59":{"name":"Delete:Post","guard_name":"web"},"60":{"name":"Restore:Post","guard_name":"web"},"61":{"name":"ForceDelete:Post","guard_name":"web"},"62":{"name":"ForceDeleteAny:Post","guard_name":"web"},"63":{"name":"RestoreAny:Post","guard_name":"web"},"64":{"name":"Replicate:Post","guard_name":"web"},"65":{"name":"Reorder:Post","guard_name":"web"},"90":{"name":"View:EditProfilePage","guard_name":"web"}}';

        $this->makeRolesWithPermissions($rolesWithPermissions);
        $this->makeDirectPermissions($directPermissions);

        $this->info('Shield inserted successfully.');
    }

    private function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    private function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }

    protected function insertHomepageContent(): void {
        HomepageContent::firstOrCreate([
            'hero_title'          => 'Hi, I’m Steve — Full-Stack Developer',
            'hero_subtitle'       => 'Passionate about building responsive and efficient web applications using modern technologies.',
            'hero_cta_experience' => 'My experience',
            'hero_cta_contact'    => 'Contact me',
            'sytatsu_text'        => 'My personal 3D-print studio & webshop, creating innovative and functional 3D models. Explore my work and collaborate with me.',
            'about_title'         => 'About me',
            'about_paragraph'     => "I'm a dedicated full-stack developer with experience in creating scalable web solutions. My approach combines technical excellence with clean, user-focused design. With a strong foundation in both front-end and back-end development, I specialize in building modern applications using Laravel, Vue.js and various other technologies.",
            'skills_title'        => 'Skills',
            'experience_title'    => 'Work experience',
            'contact_title'       => 'Let’s build something Amazing',
            'footer_text'         => 'Made with ❤️ by StPronk',
        ]);

        $this->info('Homepage Content inserted successfully.');
    }

    protected function insertWorkExperiences(): void
    {
        WorkExperience::query()->upsert([
            [
                'job_title'            => 'Full-Stack Developer',
                'organisation'         => 'Competa IT',
                'organisation_website' => 'https://competa.com/',
                'start_date'           => '2019-09-01 00:00:00',
                'end_date'             => null,
                'description'          => "IT agency where I have worked for multiple client ranging from all kind of projects and technologies. This includes some other little projects that aren't mentioned here, like the company website and wordpress websites for other clients.",
                'sort_order'           => 1,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Full-Stack Developer & Lead',
                'organisation'         => 'Competa IT — On project',
                'organisation_website' => null,
                'start_date'           => '2025-08-14 00:00:00',
                'end_date'             => null,
                'description'          => "Unfortunately I cannot disclose the name of the company, but I am working on a project that is made on NextCloud, Php.",
                'sort_order'           => 2,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Full-Stack Developer',
                'organisation'         => 'Competa IT — DICTU',
                'organisation_website' => null,
                'start_date'           => '2024-07-01 00:00:00',
                'end_date'             => '2025-06-01 00:00:00',
                'description'          => "I contributed to the ongoing development and maintenance of Achilles, a PHP/Symfony-based application supporting international economic affairs and the Netherlands Enterprise Agency (RVO). I improved performance, reliability, and architecture in close collaboration with colleagues and stakeholders.",
                'sort_order'           => 3,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Full-Stack Developer',
                'organisation'         => 'Competa IT — VWS',
                'organisation_website' => null,
                'start_date'           => '2021-06-01 00:00:00',
                'end_date'             => '2024-05-31 00:00:00',
                'description'          => "While working at Ministerie van Volksgezondheid, Welzijn en Sport, I contributed to the development of the GGD's Source and Contact Tracing platform, which was initially created for COVID-19 contact tracing and later scaled up for broader public health applications. I worked in Agile teams using Laravel, with a strong focus on test coverage, maintainability, and performance.",
                'sort_order'           => 4,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Dev-ops & Full-Stack Developer',
                'organisation'         => 'StPronk — Sytatsu',
                'organisation_website' => 'https://www.sytatsu.nl/',
                'start_date'           => '2024-06-01 00:00:00',
                'end_date'             => null,
                'description'          => "Proud owner of Sytatsu, a website that is made on laravel in combination with LunarPHP, and there are still ongoing development for the website. I also manage the server that is running on ubuntu and do the required upgrades and maintenance when needed.",
                'sort_order'           => 5,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Dev-ops & Full-Stack Developer',
                'organisation'         => 'StPronk — Antquipment & Productions',
                'organisation_website' => 'https://www.antquipment.nl/',
                'start_date'           => '2022-06-01 00:00:00',
                'end_date'             => null,
                'description'          => "Management server & development website/webshop which is made on Laravel in combination with LunarPHP, and there are still ongoing development for the website. I also manage the server that is running on ubuntu and do the required upgrades and maintenance when needed.",
                'sort_order'           => 6,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Dev-ops & Support',
                'organisation'         => 'StPronk — Bagus stories',
                'organisation_website' => 'https://www.bagusstories.nl/',
                'start_date'           => '2021-01-01 00:00:00',
                'end_date'             => null,
                'description'          => "Management server & support for a wordpress websites, where uptime is the most important. When needed, I upgrade the Ubuntu server that is running behind it and all required dependencies. Support for different type of changes is also included within this project.",
                'sort_order'           => 7,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Back-End Developer',
                'organisation'         => 'POS4',
                'organisation_website' => null,
                'start_date'           => '2018-09-01 00:00:00',
                'end_date'             => '2019-08-31 00:00:00',
                'description'          => "Modular cash register software with webshops for restaurant chains, primary focussed on the webshops with Legacy Laravel. It was also my first job experience with Laravel and VueJS in which I learned a lot of the back-end of Laravel as we needed to manual upgrade the Laravel framework.",
                'sort_order'           => 8,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ], 'id');

        $this->info('Work Experiences inserted successfully.');
    }

    protected function insertSkills(): void
    {
        Skill::query()->upsert([
            ['name' => 'HTML5', 'sort_order' => 1],
            ['name' => 'CSS', 'sort_order' => 2],
            ['name' => 'JavaScript', 'sort_order' => 3],
            ['name' => 'TailwindCSS', 'sort_order' => 4],
            ['name' => 'Vue.js', 'sort_order' => 5],
            ['name' => 'React.js', 'sort_order' => 6],
            ['name' => 'TypeScript', 'sort_order' => 7],
            ['name' => 'Php', 'sort_order' => 8],
            ['name' => 'Symfony', 'sort_order' => 9],
            ['name' => 'Laravel', 'sort_order' => 10],
            ['name' => 'Livewire', 'sort_order' => 11],
            ['name' => 'FilamentPHP', 'sort_order' => 12],
            ['name' => 'LunarPHP', 'sort_order' => 13],
            ['name' => 'Wordpress', 'sort_order' => 14],
            ['name' => 'MySQL', 'sort_order' => 15],
            ['name' => 'Postgres', 'sort_order' => 16],
            ['name' => 'RabbitMQ', 'sort_order' => 17],
            ['name' => 'Docker', 'sort_order' => 18],
            ['name' => 'Python', 'sort_order' => 19],
            ['name' => 'AI', 'sort_order' => 20],
        ], 'id');

        $this->info('Skills inserted successfully.');
    }
}
