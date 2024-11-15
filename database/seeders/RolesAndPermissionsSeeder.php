<?php

namespace Database\Seeders;

use App\Enums\SettingType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'User',
            'UserGroup',
            'Page',
            'Section',
            'Customization',
            'Post',
            'PostGroup',
            'Tag',
            'Form',
            'Role',
            'Permission',
            'Setting',
            'AdminPortal',
            'Notifications',
            'Manager',
            'Dashboard',
            'Quiz',
            'Topic',
            'Exam',
            'QuestionType',
            'Question',
            'Examination',
            'Lesson',
            'MockQuiz',
            'Classroom',
            'Attendance',
            'AttendanceClassroom',
            'QuizAttempt',
            'QuizGroup',
            'Certificate',
            'PdfCertificate',
            'ObjectGroup',
            'QuestionUser',
            'ExaminationMockQuiz',
            'LessonUser',
            'ExamLevel',
            'ExaminationCareerMockQuiz',
            'ExaminationCareer',
            'ExaminationLevelMockQuiz',
            'ExaminationLevel',
            'QuizCareer',
            'QuizLevel',
            'ExamCareer',
            'ExamOccupational',
            'QuizOccupational',
            'MockQuizOccupational',
            'MockQuizCareer',
            'MockQuizLevel'
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item) {
            if ($item === 'Role' && !Permission::where('group', $item)->where('name', 'attachUserIn'.$item)->exists()) {
                Permission::firstOrCreate(['group' => $item, 'name' => 'attachUserIn'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'detachUserIn'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'viewAny'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'view'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'update'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'delete'.$item]);
                Permission::firstOrCreate(['group' => $item, 'name' => 'create'.$item]);

                return;
            }

            if ($item === 'Manager') {
                $roles = Role::pluck('name');
                if ($roles->count() > 0) {
                    $roles->each(fn ($role) => Permission::firstOrCreate(['group' => $item, 'name' =>  $role]));

                    return;
                }

                Permission::firstOrCreate(['group' => $item, 'name' => 'Super Admin']);
            }

            if ($item === 'Dashboard') {
                Permission::where(['group' => $item, 'name' => 'view'.$item])->delete();
                Permission::firstOrCreate(['group' => $item, 'name' => 'viewDashboardExaminations']);
                Permission::firstOrCreate(['group' => $item, 'name' => 'viewDashboardReview']);

                return;
            }

            if ($item === 'AdminPortal') {
                Permission::firstOrCreate(['group' => $item, 'name' => 'view'.$item]);

                return;
            }

            if ($item === 'Notifications') {
                Permission::firstOrCreate(['group' => $item, 'name' => 'submitQuiz']);

                return;
            }
            if ($item === 'Setting') {
                collect(SettingType::getValues())
                    ->each(function ($type) use ($item) {
                        if ($type === SettingType::MediaHub) {
                            Permission::firstOrCreate(['group' => $item, 'name' => $type]);
                            return;
                        }

                        if ($type === SettingType::QuizRandom) {
                            Permission::firstOrCreate(['group' => $item, 'name' => 'viewAny'.$type]);
                        }

                        Permission::firstOrCreate(['group' => $item, 'name' => 'view'.$type]);
                        Permission::firstOrCreate(['group' => $item, 'name' => 'update'.$type]);
                    });

                return;
            }

            // create permissions for each collection item
            Permission::firstOrCreate(['group' => $item, 'name' => 'viewAny'.$item]);
            Permission::firstOrCreate(['group' => $item, 'name' => 'view'.$item]);

            if ($item === 'Customization') {
                Permission::firstOrCreate(['group' => $item, 'name' => 'update'.$item]);

                return;
            }

            Permission::firstOrCreate(['group' => $item, 'name' => 'delete'.$item]);

            if ($item === 'Examination') {
                return;
            }

            Permission::firstOrCreate(['group' => $item, 'name' => 'update'.$item]);
            Permission::firstOrCreate(['group' => $item, 'name' => 'create'.$item]);
        });

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::firstOrCreate(['name' => \App\Models\Role::SUPER_ADMIN]);
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        $user = \App\Models\User::first(); // Change this to your email.
        $user->hasRole('Super Admin') ?: $user->assignRole('Super Admin');
    }
}
