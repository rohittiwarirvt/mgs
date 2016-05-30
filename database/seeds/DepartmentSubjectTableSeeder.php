<?php

use Illuminate\Database\Seeder;

class DepartmentSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insert Department
        DB::table('department')->truncate();
        $departments = array(
            ['title' => 'Call Center', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>1],
            ['title' => 'Assignment', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>2],
        	['title' => 'Follow up', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>3],
            ['title' => 'Complaint', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>4],
            ['title' => 'Quotation And Invoice', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>5],
            ['title' => 'Accounts', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>6],
            ['title' => 'Utility Services', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>7],
            ['title' => 'Home Based Services', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>8],
            ['title' => 'Beautician', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>9],
            ['title' => 'Home Beautification', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>10],
            ['title' => 'Appliance Repairs', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>11],
            ['title' => 'Home Shifting Solutions', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'id'=>12]

        );

      	foreach ($departments as  $department) {
            DB::table('department')->insert([
                'id' => $department['id'],
                'title' => $department['title'],
                'is_active' =>  $department['is_active'],
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
                'created_by' => $department['created_by'],
                'updated_by' => $department['updated_by']
            ]);
        }

        //Insert subject
        DB::table('subject')->truncate();
        $subjects = array(
            ['title' => 'Customer Support', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'1', 'id'=>1],
            ['title' => 'No Response', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'3', 'id'=>2],
            ['title' => 'Need Callback', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'3', 'id'=>3],
            ['title' => 'Need More Details', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'3', 'id'=>4],
            ['title' => 'Quotation Required', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'3', 'id'=>5],
            ['title' => 'Quotation Sent', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'2', 'id'=>6],
            ['title' => 'Inspection Required', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'2', 'id'=>7],
            ['title' => 'Job Completed', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'2', 'id'=>8],
            ['title' => 'Payment', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'2', 'id'=>9],
            ['title' => 'Rejection', 'is_active' => 1, 'created_by' => 2, 'updated_by' => 2, 'display'=>'3', 'id'=>10]

        );

        foreach ($subjects as  $subject) {
            DB::table('subject')->insert([
                'id' => $subject['id'],
                'title' => $subject['title'],
                'is_active' =>  $subject['is_active'],
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
                'created_by' => $subject['created_by'],
                'updated_by' => $subject['updated_by'],
                'display' => $subject['display']
            ]);
        }
    }
}
