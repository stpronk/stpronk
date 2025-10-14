<?php

namespace App\Console\Commands;

use App\Models\Skill;
use App\Models\WorkExperience;
use Illuminate\Console\Command;

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
        $this->insertWorkExperiences();
        $this->insertSkills();
    }

    private function insertWorkExperiences(): void
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
                'organisation'         => 'Competa IT - On project',
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
                'organisation'         => 'Competa IT - DICTU',
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
                'organisation'         => 'Competa IT — Ministerie van Volksgezondheid, Welzijn en Sport',
                'organisation_website' => null,
                'start_date'           => '2021-06-01 00:00:00',
                'end_date'             => '2024-05-31 00:00:00',
                'description'          => "I contributed to the development of the GGD's Source and Contact Tracing platform, which was initially created for COVID-19 contact tracing and later scaled up for broader public health applications. I worked in Agile teams using Laravel, with a strong focus on test coverage, maintainability, and performance.",
                'sort_order'           => 4,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'job_title'            => 'Dev-ops & Full-Stack Developer',
                'organisation'         => 'StPronk - Sytatsu',
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
    }

    private function insertSkills(): void
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
    }
}
