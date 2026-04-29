<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Angela Lacman',
            'email'    => 'superadmin@gapply.com',
            'password' => Hash::make('admin123'),
            'role'     => 'superadmin',
            'status'   => 'active',
        ]);

        $employer = User::create([
            'name'     => 'Krone Lacman',
            'email'    => 'employer@gapply.com',
            'password' => Hash::make('password'),
            'role'     => 'employer',
            'status'   => 'active',
        ]);

        User::create([
            'name'     => 'Zi Provida',
            'email'    => 'applicant@gapply.com',
            'password' => Hash::make('password'),
            'role'     => 'applicant',
            'status'   => 'active',
        ]);

        $jobs = [
            ['title' => 'Frontend Developer',  'location' => 'Remote',      'employment_type' => 'Full-time',   'description' => 'We are looking for a skilled Frontend Developer with React and Tailwind CSS experience. You will work closely with our design and product teams to build modern web interfaces.', 'status' => 'open'],
            ['title' => 'UI/UX Designer',       'location' => 'Manila, PH',  'employment_type' => 'Part-time',   'description' => 'Join our creative team as a UI/UX Designer. You will create wireframes, prototypes, and high-fidelity designs for our digital products.', 'status' => 'open'],
            ['title' => 'Backend Engineer',     'location' => 'Cebu, PH',    'employment_type' => 'Full-time',   'description' => 'We need a Backend Engineer proficient in Laravel and MySQL. You will design APIs, manage databases, and ensure application performance.', 'status' => 'open'],
            ['title' => 'Project Manager',      'location' => 'Remote',      'employment_type' => 'Full-time',   'description' => 'Lead cross-functional teams to deliver software projects on time. You will coordinate sprints, manage stakeholders, and track milestones.', 'status' => 'open'],
            ['title' => 'QA Engineer',          'location' => 'Davao, PH',   'employment_type' => 'Contract',    'description' => 'Ensure software quality by designing test cases, performing manual and automated testing, and reporting bugs to the development team.', 'status' => 'open'],
            ['title' => 'Marketing Intern',     'location' => 'Remote',      'employment_type' => 'Internship',  'description' => 'Support our marketing team with content creation, social media management, and campaign analytics. Great opportunity for fresh graduates.', 'status' => 'closed'],
        ];

        foreach ($jobs as $job) {
            Job::create(array_merge($job, ['employer_id' => $employer->id]));
        }
    }
}
