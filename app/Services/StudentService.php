<?php

namespace App\Services;

use App\Events\StudentCreated;
use App\Events\TimelineChanged;
use App\Http\Resources\StudentResource;
use App\Models\Lead;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Http\Request;

class StudentService{

    public function store(array $inputData)
    {
        $lead = Lead::where('id', $inputData['lead_id'])->whereNull('student_id')->first();
        if(!$lead)
            return response()->json(['message' => 'Invalid Request'], 400);

        DB::beginTransaction();
        try {
            $assign_to_office_id = $lead->assign_to_office_id;
            $assigned_to_counsellor_id = $lead->assigned_to_counsellor_id;
            $assign_to_application_manager_id = $lead->assign_to_application_manager_id;

            $user = $this->saveUser($inputData, new User());
            $inputData['user_id'] = $user->id;
            $student = $this->saveStudent($inputData, new Student());
            $lead->student_id = $student->id;
            $lead->verification_status = "Yes";
            // if($assign_to_office_id != $inputData['assign_to_office_id'])
            //     $lead->assign_to_office_id = $inputData['assign_to_office_id'];

            // if($assigned_to_counsellor_id != $inputData['assigned_to_counsellor_id'])
            //     $lead->assigned_to_counsellor_id = $inputData['assigned_to_counsellor_id'];

            // if($assign_to_application_manager_id != $inputData['assign_to_application_manager_id'])
            //     $lead->assign_to_application_manager_id = $inputData['assign_to_application_manager_id'];

            $lead->save();
            DB::commit();
            $lead->student = $lead->name;
            $this->createTimeline('student_created', $lead);

            if($assigned_to_counsellor_id != $lead->assigned_to_counsellor_id)
                $this->createTimeline('counsellor_assigned', $lead);

            if($assign_to_application_manager_id != $lead->assign_to_application_manager_id)
                $this->createTimeline('application_manager_assigned', $lead);

            StudentCreated::dispatch($user);
            return new StudentResource($student);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    protected function saveUser($inputData, $userObj){
        if(!$userObj->id){
            $userObj->user_type = 'student';
            $userObj->email = $inputData['email'];
            $userObj->phone_number = $inputData['phone_number'];
            $password = $this->generateRandomPassword(6);
            $userObj->password = Hash::make($password);
            $userObj->created_by = auth()->user()->id;
        }
        $userObj->updated_by = auth()->user()->id;
        $userObj->title = $inputData['title'];
        $userObj->name = $inputData['name'];
        $userObj->save();
        if(!empty($password))
            $userObj->plain_password = $password;
        return $userObj;
    }

    protected function saveStudent($inputData, $studentObj){
        if(!$studentObj->id){
            $studentObj->user_id = $inputData['user_id'];
            $studentObj->created_by = auth()->user()->id;
        }
        $studentObj->intake_id = $inputData['intake_id'];
        $studentObj->updated_by = auth()->user()->id;
        $studentObj->date_of_birth = date('Y-m-d', strtotime($inputData['date_of_birth']));
        $studentObj->address = $inputData['address'];
        //$studentObj->zipcode = $inputData['zipcode'];
        //$studentObj->state = $inputData['state'];
        $studentObj->country_of_birth_id = $inputData['country_of_birth_id'];
        $studentObj->country_of_residence_id = $inputData['country_of_residence_id'];
        $studentObj->alternate_phone_number = $inputData['alternate_phone_number'];
        $studentObj->whatsapp_number = $inputData['whatsapp_number'];
        $studentObj->preferred_course = (!empty($inputData['preferred_course']))?$inputData['preferred_course']:null;
        $studentObj->preferred_countries = (!empty($inputData['preferred_countries']))?$inputData['preferred_countries']:null;
        //$studentObj->course_level_id = $inputData['course_level_id'];
        $studentObj->save();
        return $studentObj;
    }

    protected function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function update(array $studentData)
    {
        $id = $studentData['id'];
        if($student = Student::find($id)){
            DB::beginTransaction();
            try {
                $user = $this->saveUser($studentData, $student->user);
                $student = $this->saveStudent($studentData, $student);
                DB::commit();
                return new StudentResource($student);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Error'], 500);
            }
        }   
        return response()->json(['message' => 'Error'], 500);
    }

    public function close(Request $request)
    {
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Student::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->closed = 1;
        if($item->save())
        {
            return response()->json(['message' => 'Student application successfully closed.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead){
        $description = null;
        switch ($type) {
            case 'student_created':
                $description = "{$lead->student} has been successfully created";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
    }
}