<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePhoneNumber;
use App\Models\EmployeeAddress;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Seed default user
        $this->call(UserSeeder::class);
        // Create 5 departments, each with 10 employees, each employee with phone numbers & addresses
        Department::factory(5)->create()->each(function ($department) {
            $employees = Employee::factory(10)->create(['department_id' => $department->id]);

            $employees->each(function ($employee) {
                // 1-3 phone numbers per employee
                EmployeePhoneNumber::factory(rand(1, 3))->create([
                    'employee_id' => $employee->id
                ]);

                // 1-2 addresses per employee
                EmployeeAddress::factory(rand(1, 2))->create([
                    'employee_id' => $employee->id
                ]);
            });
        });
    }
}
