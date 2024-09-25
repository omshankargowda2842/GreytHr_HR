<?php

// File Name                       : AddEmployeeDetails.php
// Description                     : This file contains the implementation of the Adding employee details (onboarding process).....,
// Creator                         : Bandari Divya
// Email                           : bandaridivya1@gmail.com
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : EmployeeDetails,Company -->

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use App\Models\EmpParentDetails;
use App\Models\EmpSpouseDetails;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\EmpDepartment;
use App\Models\EmpSubDepartments;
use App\Models\EmpBankDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AddEmployeeDetails extends Component
{
    use WithFileUploads;

    public $emp_id = '';
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $gender = "Male";
    public $email;
    public $company_name = '';
    public $company_email;
    public $mobile_number;
    public $alternate_mobile_number;
    public $address;
    public $present_address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $hire_date;
    public $employee_type;
    public $departments;
    public $sub_departments = [];
    public $department_id = '';
    public $sub_department_id = '';
    public $job_title;
    public $job_mode;
    public $manager_id;
    public $report_to;

    public $emergency_contact;
    public $password;
    public $image;
    public $imageBinary = '';
    public $showImage;
    public $blood_groups = [
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-'
    ];
    public $blood_group = '';
    public $nationality = "Indian";
    public $religion;
    public $marital_status = 'married';
    public $spouse;
    public $physically_challenge;
    public $inter_emp;
    public $job_location = 'Hyderabad';
    public $education = [];
    public $newEducation = [
        ['level' => '', 'institution' => '', 'course_name' => '', 'year_of_passing' => '', 'percentage' => '']
    ];
    public $experience = [];
    public $newExperience = [
        ['company_name' => '',  'start_date' => '', 'end_date' => '', 'skills' => '', 'description' => '']
    ];
    public $pan_no;
    public $adhar_no;
    public $pf_no;
    public $passport_no;
    public $employee_status;

    public $nick_name;
    public $time_zone;
    public $biography;
    public $facebook;
    public $twitter;
    public $linked_in;
    public $company_id = '';
    public $is_starred;
    public $skill_set;
    public $savedImage;

    public $companies;
    public $hrDetails;
    public $managerIds = [];
    public $managerName;
    public $companyName;
    public $employeeId;
    public $imagePath;
    public $empId;
    public $reportTos, $selectedEmployee, $employee;
    public $selectedId;
    public $companieIds;
    public $referrer;
    public $probation_period = 30;
    public $probation_periods = [
        '30',
        '45',
        '60',
        '90',
        '180',
        'none'

    ];
    public $confirmation_date;
    public $notice_period;
    public $emp_domain;
    public $shift_type = 'GS';
    public $shift_start_time;
    public $shift_end_time;
    public $signature_image;
    public $father_first_name;
    public $father_last_name;
    public $mother_first_name;
    public $mother_last_name;
    public $father_dob;
    public $mother_dob;
    public $father_address;
    public $mother_address;
    public $father_city;
    public $mother_city;
    public $father_state;
    public $mother_state;
    public $father_country;
    public $mother_country;
    public $father_email;
    public $mother_email;
    public $father_phone;
    public $mother_phone;
    public $father_occupation;
    public $mother_occupation;
    public $father_image;
    public $father_image_binary = '';
    public $mother_image_binary = '';
    public $mother_image;
    public $father_blood_group = '';
    public $mother_blood_group = '';
    public $father_nationality;
    public $mother_nationality;
    public $father_religion;
    public $mother_religion;
    public $imageerror = 'Preview not available for this file type.';
    public $spouse_first_name;
    public $spouse_last_name;
    public $spouse_gender;
    public $spouse_qualification;
    public $spouse_profession;
    public $spouse_dob;
    public $spouse_nationality;
    public $spouse_bld_group = '';
    public $spouse_adhar_no;
    public $spouse_pan_no;
    public $spouse_religion;
    public $spouse_email;
    public $spouse_address;
    public $children = [];
    public $newChildren = [
        ['name' => '', 'gender' => '', 'dob']
    ];
    public $spouse_image;
    public $bank_name;
    public $bank_branch;
    public $account_number;
    public $ifsc_code;
    public $bank_address;

    public  $selectedEmployees;
    public $currentStep = 1;
    public $parentscurrentStep = 1;
    public $showAlert = false;
    public $successModal = false;
    public $action = 'add';
    public $email_domain = '';



    protected function validationRules()
    {
        return $this->rules[$this->currentStep];
    }
    public function updatedImage()
    {

        // Check if the file size exceeds 1MB
        if ($this->image->getSize() > 1048576) {
            // Display an error message or handle the validation
            // $this->addError('image', 'The selected image exceeds 1MB and cannot be uploaded.');
            $this->image = null; // Clear the file
        }
    }

    public function updated($propertyName)
    {
        if ($this->currentStep != 8 && $this->currentStep != 9) {

            $this->validateOnly($propertyName, $this->validationRules());
        }


        if (in_array($propertyName, ['last_name', 'mobile_number'])) {
            $firstName = explode(' ', trim($this->first_name))[0]; // Take only the first word from the first name
            $lastname = explode(' ', trim($this->last_name))[0];
            $mobile = substr($this->mobile_number, 0, 4);
            $company_email = strtolower($firstName . '.' . $lastname.'@'.$this->email_domain);
            $exists = EmployeeDetails::where('email', $company_email)->exists();
            if ($exists) {

                $this->company_email = strtolower($firstName . '.' . $lastname . $mobile.'@'.$this->email_domain );
                $this->validateOnly("company_email", $this->validationRules());
            } else {
                $this->company_email = strtolower($firstName . '.' . $lastname.'@'.$this->email_domain );
                $this->validateOnly("company_email", $this->validationRules());
            }
        }
    }
    public function closeModal()
    {
        $this->successModal = false;
    }

    public function nextPage()

    {

        if ($this->currentStep != 8 && $this->currentStep != 9) {

            $this->validate($this->validationRules());
        }


        if ($this->currentStep == 1) {
            $existingEmployee = EmployeeDetails::where('email', $this->company_email)
                ->where('emp_id', '!=', $this->emp_id)
                ->first();
            $existingPhoneNumber = EmployeeDetails::where('emergency_contact', $this->mobile_number)
                ->where('emp_id', '!=', $this->emp_id)
                ->first();

            // dd( $existingEmployee);
            if ($existingEmployee) {
                // Add custom error message for email
                $this->addError('company_email', ' Company email already used for another employee.');
                return;
            }
            if ($existingPhoneNumber) {
                // Add custom error message for email
                $this->addError('mobile_number', ' Mobile number already used for another employee.');
                return;
            }

            $this->currentStep++;
        } elseif ($this->currentStep == 2) {

            $this->registerEmployeeDetails();
        } elseif ($this->currentStep == 3) {
            $existingEmployee = EmpPersonalInfo::where('adhar_no', $this->adhar_no)
                ->where('emp_id', '!=', $this->emp_id)
                ->first();
            $existingpan = EmpPersonalInfo::where('pan_no', $this->pan_no)
                ->where('emp_id', '!=', $this->emp_id)
                ->first();

            // dd( $existingEmployee);
            if ($existingEmployee) {
                // Add custom error message for email
                $this->addError('adhar_no', ' Aadhar number already used of another employee.');
                return;
            }
            if ($existingpan) {
                // Add custom error message for email
                $this->addError('pan_no', ' PAN number already used of another employee.');
                return;
            }
            $this->currentStep++;
        } elseif ($this->currentStep == 4) {
            $this->registerEmployeeJobDetails();
        } elseif ($this->currentStep == 5) {

            $this->validate($this->validationRules());
            $this->parentscurrentStep++;
        } elseif ($this->currentStep == 6) {

            $this->registerEmployeeSpouseDetails();
        } elseif ($this->currentStep == 7) {

            $this->registerEmployeeBankDetails();
        } elseif ($this->currentStep == 8) {
            $this->addEducationDetails();
        } elseif ($this->currentStep == 9) {

            $this->addExperienceDetails();
        } else {
        }
    }

    public function skipPage()
    {

        $this->currentStep++;
    }

    public function hideAlert()
    {
        $this->showAlert = false;
    }


    public function previousPage()
    {
        $this->currentStep--;
    }
    public function parentsnextPage()
    {

        $this->validate($this->rules[11]);

        $this->registerEmployeeParentsDetails();
    }
    public function parentspreviousPage()
    {
        $this->parentscurrentStep--;
    }

    public function addChildren()
    {

        $this->validate([
            'newChildren.name' => 'required',
            'newChildren.gender' => 'required',
            'newChildren.dob' => 'required',
        ], [
            'newChildren.name.required' => 'Child name is required.',
            'newChildren.gender.required' => 'Child gender is required.',
            'newChildren.dob.required' => 'Date of birth is required.',
        ]);

        $this->children[] = $this->newChildren;

        // Clear the form
        $this->reset('newChildren');
    }
    public function editChildren($index)
    {
        $this->newChildren = $this->children[$index];
        $this->removeChildren($index);
    }

    public function removeChildren($index)
    {
        unset($this->children[$index]);
        $this->children = array_values($this->children); // Re-index the array
    }

    public function addEducation()
    {
        // dd($this->newEducation[0]['year_of_passing']);
        $this->validate([
            'newEducation.level' => 'required',
            'newEducation.institution' => 'required',
            'newEducation.course_name' => 'required',
            'newEducation.year_of_passing' => 'required',
            'newEducation.percentage' => 'required|numeric|max:100',
        ], [
            'newEducation.level.required' => 'Select the educational level.',
            'newEducation.institution.required' => 'Enter the name of the institution.',
            'newEducation.course_name.required' => 'Enter the course name.',
            'newEducation.year_of_passing.required' => 'Select the year of passing.',
            'newEducation.percentage.required' => ' Enter the percentage or CGPA.',
            'newEducation.percentage.numeric' => 'Percentage or CGPA must be a number.',
            'newEducation.percentage.max' => 'Percentage or CGPA must not exceed 100.',
        ]);

        $this->education[] = $this->newEducation;

        // Clear the form
        $this->reset('newEducation');
    }
    public function editEducation($index)
    {
        $this->newEducation = $this->education[$index];
        $this->removeEducation($index);
    }

    public function removeEducation($index)
    {
        unset($this->education[$index]);
        $this->education = array_values($this->education); // Re-index the array
    }
    public function addExperience()
    {
        // dd($this->newEducation[0]['year_of_passing']);
        $this->validate([
            'newExperience.company_name' => 'required',
            'newExperience.start_date' => 'required',
            'newExperience.end_date' => 'required',
            'newExperience.skills' => 'required',
            'newExperience.description' => 'required|max:1000',
        ], [
            'newExperience.company_name.required' => 'Company name is required.',
            'newExperience.start_date.required' => 'Start date is required.',
            'newExperience.end_date.required' => 'Last date is required.',
            'newExperience.skills.required' => 'Skills are required',
            'newExperience.description.required' => ' Job description is required.',
        ]);

        $this->experience[] = $this->newExperience;

        // Clear the form
        $this->reset('newExperience');
    }

    public function editExperience($index)
    {
        $this->newExperience = $this->experience[$index];
        $this->removeExperience($index);
    }

    public function removeExperience($index)
    {
        unset($this->experience[$index]);
        $this->experience = array_values($this->experience); // Re-index the array
    }

    protected $rules = [
        1 => [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
            'mobile_number' => 'required|string|size:10|different:alternate_mobile_number',
            'company_email' => 'required|email|different:email',
            'gender' => 'required|in:Male,Female,Others',
            'imageBinary' => 'required|max:1024',
            'company_id' => 'required|exists:companies,company_id',

        ],
        2 => [
            'hire_date' => 'required|date',
            'employee_type' => 'required|in:full-time,part-time,contract',
            'employee_status' => 'required|in:active,on-leave,terminated',
            'department_id' => 'required|string|max:255',
            'sub_department_id' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'emp_domain' => 'nullable|string',
            'referrer' => 'nullable|string',
            'job_location' => 'required|string|max:255',
            'manager_id' => 'required',
            'job_mode' => 'required|in:Office,Remote,Hybrid',
            'employee_type' => 'required|in:full-time,part-time,contract',
        ],
        3 => [
            'date_of_birth' => 'required|date',
            'blood_group' => 'required',
            'adhar_no' => 'required|size:12',
            'religion' => 'required|string',
            'nationality' => 'required|string',
            'marital_status' => 'required',
            'email' => 'required|email|different:company_email',
            'pf_no' => 'nullable|size:12',
            'pan_no' => 'required|size:10',
            'passport_no' => 'nullable|max:20',
            'alternate_mobile_number' => 'nullable|size:10|different:mobile_number',
        ],
        4 => [
            'address' => 'required|string|max:255',
            'present_address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'nullable|size:6',
            'country' => 'nullable|string|max:255',
        ],
        5 => [
            'father_first_name' => 'required|string|max:255',
            'father_last_name' => 'required|string|max:255',
            'father_blood_group' => 'required',
            'father_phone' => 'nullable|size:10',
            'mother_first_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_blood_group' => 'required',
            'mother_phone' => 'nullable|size:10',
            'father_image' => 'nullable|image|max:1024',
            'mother_image' => 'nullable|image|max:1024',
        ],
        11 => [
            'father_email' => 'nullable|email',
            'mother_email' => 'nullable|email',
            'father_religion' => 'nullable|string',
            'mother_religion' => 'nullable|string',
            'father_nationality' => 'nullable|string',
            'mother_nationality' => 'nullable|string',
            'father_occupation' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'father_image' => 'nullable|image|max:1024',
            'mother_image' => 'nullable|image|max:1024'

        ],
        6 => [
            'spouse_first_name' => 'nullable|string',
            'spouse_last_name' => 'nullable|string',
            'spouse_qualification' => 'nullable|string',
            'spouse_profession' => 'nullable|string',
            'spouse_nationality' => 'nullable|string',
            'spouse_adhar_no' => 'nullable|size:12',
            'spouse_pan_no' => 'nullable|size:10',
            'spouse_religion' => 'nullable|string',
            'spouse_email' => 'nullable|email',
            //    'children.*.dob'=>
            //    'children.*.gender'=>
        ],
        7 => [
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
            'account_number' => 'required|max:20',
            'ifsc_code' => 'required|max:12',
            'bank_address' => 'required',

        ],

    ];

    protected $messages = [
        'first_name.required' => ' First name is required.',
        'first_name.alpha' => 'First name must only contain letters.',
        'first_name.max' => 'First name must only contain 100 leters.',
        'last_name.required' => 'Last name is required.',
        'last_name.alpha' => 'Last name must only contain letters.',
        'last_name.max' => 'Last name must only contain 100 leters.',
        'mobile_number.required' => 'Mobile number is required.',
        'mobile_number.size' => 'Mobile number must be exactly 10 digits.',
        'mobile_number.different' => ' Mobile number must be different from the emergency mobile number.',
        'company_email.required' => 'Company email is required.',
        'company_email.email' => 'Company email must be a valid email address.',
        'company_email.different' => 'Company email must be different from the personal email.',
        'company_email.unique' => ' Company email  already used of another employee.',
        'gender.required' => 'Please select a gender.',
        'image.required' => 'Employee image is required.',
        'image.max' => ' Selected image exceeds 1MB and cannot be uploaded.',
        'gender.in' => 'Selected gender is invalid.',
        'hire_date.required' => 'Hire date is required.',
        'hire_date.date' => 'Hire date must be a valid date.',
        'employee_type.required' => 'Please select an employee type.',
        'employee_type.in' => 'Selected employee type is invalid.',
        'employee_status.required' => 'Please select an employee status.',
        'employee_status.in' => 'Selected employee status is invalid.',
        'department_id.required' => 'Department is required.',
        'department_id.string' => 'Department must be a valid string.',
        'sub_department_id.required' => 'Sub-department is required.',
        'sub_department_id.string' => 'Sub-department must be a valid string.',
        'emp_domain.alpha' => 'Employee domain field must only contain letters',
        'referrer.alpha' => 'Referrer field must only contain letters',
        'job_title.required' => 'Job title is required.',
        'job_title.string' => 'Job title must be a valid string.',
        'job_location.required' => 'Job location is required.',
        'job_location.alpha' => 'Job location field must only contain letters.',
        'company_id.required' => 'Please select the company name.',
        'company_id.exists' => 'Selected company name does not exist.',
        'manager_id.required' => 'Please select the manager ',
        'date_of_birth.required' => 'Date of birth is required.',
        'date_of_birth.date' => 'Date of birth must be a valid date.',
        'blood_group.required' => 'Blood group is required.',
        'adhar_no.required' => 'Aadhar number is required.',
        'adhar_no.unique' => 'This Aadhar number is already taken.',
        'adhar_no.size' => 'Aadhar number must be exactly 12 digits.',
        'religion.required' => 'Religion is required.',
        'religion.alpha' => 'Religion field must only contain letters.',
        'nationality.required' => 'Nationality is required.',
        'nationality.alpha' => 'Nationality field must only contain letters.',
        'marital_status.required' => 'Marital status is required.',
        'email.required' => 'Personal email is required.',
        'email.email' => ' Personal email must be a valid email.',
        'pf_no' => ' PF number must be 12 characters.',
        'pan_no' => ' PAN number must be 10 characters.',
        'pan_no.required' => ' PAN number is required.',
        'email.different' => 'Personal email must be different from the company email.',
        'alternate_mobile_number.different' => 'The Emergency mobile number must be different from the phone number.',
        'alternate_mobile_number.size' => 'Emergency mobile number must be 10 characters.',
        'address.required' => 'Permanent address is required.',
        'present_address.required' => 'Present address is required.',
        'state.required' => 'The state is required.',
        'postal_code.required' => 'Postal code is required.',
        'postal_code.size' => 'Postal code  must be 6 characters.',
        'country.required' => 'Country is required.',
        'father_first_name.required' => 'Father\'s first name is required.',
        'father_first_name.alpha' => 'Father\'s first  must only contain letters.',
        'father_last_name.required' => 'Father\'s last name is required.',
        'father_last_name.alpha' => 'Father\'s last  must only contain letters.',
        'father_blood_group.required' => 'Father\'s blood group is required.',
        'father_blood_group.required' => 'Father\'s blood group is required.',
        'father_phone' => 'Father\'s phone number must be 10 characters.',
        'mother_phone' => 'Mother\'s phone number must be 10 characters.',
        'mother_first_name.required' => 'Mother\'s first name is required.',
        'mother_first_name.alpha' => 'Mother\'s first  must only contain letters.',
        'mother_last_name.required' => 'Mother\'s last name is required.',
        'mother_last_name.alpha' => 'Mother\'s lastfield must only contain letters.',
        'mother_blood_group.required' => 'Mother\'s blood group is required.',
        'spouse_first_name.alpha' => 'Spouse first name must only contain letters.',
        'spouse_last_name.alpha' => 'Spouse last name must only contain letters.',
        'spouse_qualification.alpha' => 'Spouse qualification  must only contain letters.',
        'spouse_nationality.alpha' => 'Spouse nationality must only contain letters.',
        'spouse_religion.alpha' => 'Spouse religion must only contain letters.',
        'father_religion.alpha' => 'Father religion must only contain letters.',
        'mother_occupation.alpha' => 'Mother occupation must only contain letters.',
        'father_occupation.alpha' => 'Father occupation must only contain letters.',
        'mother_nationality.alpha' => 'Mother nationality must only contain letters.',
        'father_nationality.alpha' => 'Father nationality must only contain letters.',
        'mother_religion.alpha' => 'Mother religion must only contain letters.',
        'children.*.name.alpha' => 'Child name must only contain letters.',
        'spouse_pan_no.size' => 'Spouse PAN number must be 10 characters.',
        'spouse_adhar_no.size' => 'Spouse Aadhar number must be 12 characters.',
        'bank_name.required' => 'Bank name is required.',
        'bank_name.alpa' => 'Bank name must only contain letters.',
        'bank_branch.required' => 'Branch name is required.',
        'bank_branch.required' => 'Branch name is required.',
        'account_number.required' => 'Account number is required.',
        'account_number.max' => 'Account number field must not be greater than 20 characters.',
        'ifsc_code.required' => 'IFSC code is required.',
        'ifsc_code.size' => 'IFSC code must be 6 characters.',
        'bank_address.required' => 'Bank address is required.',
        'education.required' => 'Atleast add one Educational qualification',
        // 'experience.*.company_name.required' => 'Company name is required.',
        'experience.*.company_name.string' => 'Company name must be a string.',
        'experience.*.company_name.max' => 'Company name may not be greater than 255 characters.',
        // 'experience.*.skills.required' => 'Skills are required.',
        'experience.*.skills.string' => 'Skills must be a string.',
        'experience.*.skills.max' => 'Skills may not be greater than 500 characters.',
        // 'experience.*.start_date.required' => 'Start date is required.',
        'experience.*.start_date.date' => 'Start date must be a valid date.',
        'experience.*.start_date.before_or_equal' => 'Start date must be before or equal to today.',
        // 'experience.*.end_date.date' => 'End date must be a valid date.',
        'experience.*.end_date.after_or_equal' => 'End date must be after or equal to the start date.',
        'experience.*.description.string' => 'Description must be a string.',
        'experience.*.description.max' => 'Description may not be greater than 1000 characters.',

        'newEducation.*.level.required' => 'Please select an education level.',
        'newEducation.*.level.alpha' => 'Education level must only contain letters.',
        'newEducation.*.level.in' => 'Education level must be one of: Bachelors, Masters, or Intermediate.',

        'newEducation.*.institution.required' => 'Institution is required.',
        'newEducation.*.institution.string' => 'Institution name must be a string.',
        'newEducation.*.institution.max' => 'Institution name may not be greater than 255 characters.',

        'newEducation.*.year_of_passing.required' => 'Year of passing is required.',
        'newEducation.*.year_of_passing.digits' => 'Year of passing must be a 4-digit number.',
        'newEducation.*.year_of_passing.integer' => 'Year of passing must be an integer.',

        'newEducation.*.course_name.required' => 'Course name is required.',
        'newEducation.*.course_name.alpha' => 'Course name must only contain letters.',

        'newEducation.*.percentage.required' => 'Percentage/CGPA is required.',
        'newEducation.*.percentage.numeric' => 'The percentage/Cgpa must be a number.',
        'newEducation.*.percentage.min' => 'The percentage/CGPA must be at least 0.',
        'newEducation.*.percentage.max' => 'The percentage/CGPA may not be greater than 100.',
    ];

    public function registerEmployeeDetails()
    {
        try {

            $this->validate($this->validationRules());

            if ($this->image) {
                $this->imageBinary = base64_encode(file_get_contents($this->image->getRealPath()));
            }

           $report_to = EmployeeDetails::where('emp_id', $this->manager_id)->value('manager_id');



            EmployeeDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'first_name' => ucfirst(strtolower($this->first_name)),
                    'last_name' => ucfirst(strtolower($this->last_name)),
                    'gender' => $this->gender,
                    'email' => $this->company_email,
                    'company_id' =>[$this->company_id],
                    'hire_date' => $this->hire_date,
                    'employee_type' => $this->employee_type,
                    'manager_id' => $this->manager_id,
                    'dept_head' => $report_to,
                    'dept_id' => $this->department_id,
                    'sub_dept_id' => $this->sub_department_id,
                    'job_role' => $this->job_title,
                    'job_mode' => $this->job_mode,
                    'employee_status' => $this->employee_status,
                    'emergency_contact' => $this->mobile_number,
                    'inter_emp' => $this->inter_emp,
                    'job_location' => $this->job_location,
                    'image' => $this->imageBinary,
                    'emp_domain' => $this->emp_domain,
                    'referral' => $this->referrer,
                    'probation_Period' => $this->probation_period,
                    'confirmation_date' => $this->confirmation_date,
                    'shift_type' => $this->shift_type,
                    'shift_start_time' => $this->shift_start_time,
                    'shift_end_time' => $this->shift_end_time,
                ]
            );

            $this->emp_id = EmployeeDetails::where('email', $this->company_email)->value('emp_id');





            // Clear the form fields
            $this->currentStep++;
        } catch (\Exception $e) {
            Log::error('Error in registerEmployeeDetails method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function registerEmployeeJobDetails()
    {


        try {
            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'blood_group' => $this->blood_group,
                    'signature' => $this->signature_image,
                    'nationality' => $this->nationality,
                    'religion' => $this->religion,
                    'marital_status' => $this->marital_status,
                    'physically_challenge' => $this->physically_challenge,
                    'email' => $this->email,
                    'mobile_number' => $this->mobile_number,
                    'alternate_mobile_number' => $this->alternate_mobile_number,
                    'city' => $this->city,
                    'state' => $this->state,
                    'postal_code' => $this->postal_code,
                    'country' => $this->country,
                    'company_name' => $this->company_name,
                    'present_address' => $this->present_address,
                    'permenant_address' => $this->address,
                    'passport_no' => $this->passport_no,
                    'pan_no' => strtoupper($this->pan_no) ? strtoupper($this->pan_no) : "",
                    'adhar_no' => $this->adhar_no,
                    'pf_no' => $this->pf_no,
                    'nick_name' => $this->nick_name,
                    'facebook' => $this->facebook,
                    'twitter' => $this->twitter,
                    'linked_in' => $this->linked_in,
                    // 'qualification'=>json_encode($this->education),
                    // 'experience'=>json_encode($this->experience),
                ]
            );
            $this->currentStep++;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function registerEmployeeParentsDetails()
    {

        try {

            if ($this->father_image) {

                $this->father_image_binary = base64_encode(file_get_contents($this->father_image->getRealPath()));
            }

            if ($this->mother_image) {
                $this->mother_image_binary = base64_encode(file_get_contents($this->mother_image->getRealPath()));
            }
            EmpParentDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'father_first_name' => $this->father_first_name,
                    'father_last_name' => $this->father_last_name,
                    'mother_first_name' => $this->mother_first_name,
                    'mother_last_name' => $this->mother_last_name,
                    'father_dob' => $this->father_dob,
                    'mother_dob' => $this->mother_dob,
                    'father_address' => $this->father_address,
                    'mother_address' => $this->mother_address,
                    // 'father_city'=>$this->father_city,
                    // 'mother_city'=>$this->mother_city,
                    // 'father_state'=>$this->father_state,
                    // 'mother_state'=>$this->mother_state,
                    // 'father_country'=>$this->father_country,
                    // 'mother_country'=>$this->mother_country,
                    'father_email' => $this->father_email,
                    'mother_email' => $this->mother_email,
                    'father_phone' => $this->father_phone,
                    'mother_phone' => $this->mother_phone,
                    'father_occupation' => $this->father_occupation,
                    'mother_occupation' => $this->mother_occupation,
                    'father_image' => $this->father_image_binary,
                    'mother_image' => $this->mother_image_binary,
                    'father_blood_group' => $this->father_blood_group,
                    'mother_blood_group' => $this->mother_blood_group,
                    'father_nationality' => $this->father_nationality,
                    'mother_nationality' => $this->mother_nationality,
                    'father_religion' => $this->father_religion,
                    'mother_religion' => $this->mother_religion,
                ]
            );
            $this->currentStep++;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function registerEmployeeSpouseDetails()
    {
        try {



            EmpSpouseDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'name' => $this->spouse_first_name,
                    'gender' => $this->spouse_gender,
                    'qualification' => $this->spouse_qualification,
                    'profession' => $this->spouse_profession,
                    'dob' => $this->spouse_dob,
                    'nationality' => $this->spouse_nationality,
                    'bld_group' => $this->spouse_bld_group,
                    'adhar_no' => $this->spouse_adhar_no,
                    'pan_no' => $this->spouse_pan_no,
                    'religion' => $this->spouse_religion,
                    'email' => $this->spouse_email,
                    'address' => $this->spouse_address,
                    'children' => json_encode($this->children),

                ]
            );
            $this->currentStep++;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function registerEmployeeBankDetails()
    {

        try {

            EmpBankDetail::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'bank_name' => $this->bank_name,
                    'bank_branch' => $this->bank_branch,
                    'account_number' => $this->account_number,
                    'ifsc_code' => $this->ifsc_code,
                    'bank_address' => $this->bank_address,

                ]
            );
            $this->currentStep++;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addEducationDetails()
    {

        try {
            $this->validate([
                'education' => 'required|array|min:1',
            ], [
                'education.required' => 'At least add one Educational qualification',
            ]);

            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'qualification' => json_encode($this->education),

                ]
            );
            $this->currentStep++;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addExperienceDetails()
    {
        try {
            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    // 'qualification' => json_encode($this->education),
                    'experience' => json_encode($this->experience),
                ]
            );

            $this->successModal = true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function logout()
    {
        auth()->guard('com')->logout();
        return redirect('/Login&Register');
    }


    public function selectedCompany()
    {

        if ($this->company_id) {
            $this->selectedId = Company::find($this->company_id);
            $this->company_name = $this->selectedId->company_name;
            $this->companyName = $this->company_name;
            $this->email_domain=$this->selectedId->email_domain;
            $trimmedEmail = substr($this->company_email, 0, strpos($this->company_email, '@'));
            $this->company_email=$trimmedEmail.'@'. $this->email_domain;

            // $this->selectedEmployees = EmployeeDetails::where('company_id', $this->company_id)->get();
            // dd( $this->selectedEmployees);
            // if ($this->selectedEmployees->isNotEmpty()) {
            //     foreach ($this->selectedEmployees as $employee) {

            //         if (!in_array($employee->manager_id, $this->managerIds)) {
            //             $this->managerIds[] = $employee->manager_id;
            //         }
            //     }
            // }
            $managers = EmployeeDetails::where('dept_id', $this->department_id)
                ->pluck('manager_id')
                ->unique()
                ->toArray();

            $this->managerIds = EmployeeDetails::whereIn('emp_id', $managers)
                ->get(['emp_id', 'first_name', 'last_name', 'manager_id']);

            //  dd( $this->managerIds);

        }
    }

    public function selectedDepartment($value)
    {
        if ($value !== '') {
            $this->sub_departments = EmpSubDepartments::where('dept_id', $value)->get();

            $managers = EmployeeDetails::where('dept_id', $this->department_id)
                ->pluck('manager_id')
                ->unique()
                ->toArray();

            $this->managerIds = EmployeeDetails::whereIn('emp_id', $managers)
                ->get(['emp_id', 'first_name', 'last_name', 'manager_id']);
        }
    }

    public function fetchReportTo()
    {
        // Code to fetch report_to based on the selected manager_id
        if ($this->manager_id) {
            $managerDetails = EmployeeDetails::where('emp_id', $this->manager_id)->first();

            if ($managerDetails) {
                $this->managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
            } else {
                $this->managerName = null;
            }
        } else {
            $this->managerName = null;
        }

        $this->report_to = $this->managerName; // Set report_to property as managerName
    }


    public function mount()
    {

        // Set default values if the properties are not set
        $this->job_mode = $this->job_mode ?? 'Office';
        $this->employee_status = $this->employee_status ?? 'active';
        $this->employee_type = $this->employee_type ?? 'full-time';
        $this->physically_challenge = $this->physically_challenge ?? 'No';
        $this->inter_emp = $this->inter_emp ?? 'no';
        $this->hire_date = Carbon::now()->toDateString();
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);

        $empCompanyId = $employee->company_id;

        $this->companieIds = Company::wherein('company_id', $empCompanyId)->select('company_id', 'company_name')->get();

        $companieIdsLength = count($this->companieIds);
        if ($companieIdsLength == 1) {
            $this->company_id = $this->companieIds->first()->company_id;
        }
        $this->departments = EmpDepartment::where('company_id', $empCompanyId)->get();
        if ($this->shift_type == 'GS') {
            $this->shift_start_time = "10:00";
            $this->shift_end_time = "19:00";
        }
        $this->editEmployee();

        // if ($empId) {
        //     try {
        //         $decryptedEmpId = Crypt::decrypt($empId);

        //         $this->editEmployee($decryptedEmpId);
        //     } catch (DecryptException $e) {
        //         // Handle the error appropriately
        //         session()->flash('error_message', 'Invalid Employee ID.');
        //         return redirect()->route('some-error-route'); // Or handle the error as needed
        //     }
        // }
    }
    public function editEmployee()
    {


        $id = request()->query('emp_id') ? request()->query('emp_id') : '';
        if ($id) {
            // Assign fetched employee details to the respective properties
            try {

                $this->action = 'edit';
                // Employee job Details

                // Decrypt the ID
                $this->emp_id = Crypt::decrypt($id);
                $empdetails = EmployeeDetails::where('emp_id', $this->emp_id)->first();
                // dd( $empdetails);
                $this->first_name = $empdetails->first_name;
                $this->last_name = $empdetails->last_name;
                $this->company_email = $empdetails->email;
                $this->email_domain = substr($this->company_email, strpos($this->company_email, '@') + 1);
                $this->gender = $empdetails->gender;
                $this->mobile_number = $empdetails->emergency_contact;
                $this->imageBinary = $empdetails->image;

                // dd(($empdetails->image));
                $this->hire_date = $empdetails->hire_date;
                $this->company_id = $empdetails->company_id;
                // dd( $this->company_id);
                $this->employee_type = $empdetails->employee_type;
                $this->manager_id = $empdetails->manager_id;
                $this->department_id = $empdetails->dept_id;
                //    $this->sub_department_id=$empdetails->sub_dept_id;
                if ($this->department_id) {
                    $this->sub_departments = EmpSubDepartments::where('dept_id', $empdetails->dept_id)->get();
                    $this->sub_department_id = $empdetails->sub_dept_id;


                    $managers = EmployeeDetails::where('dept_id', $this->department_id)
                        ->pluck('manager_id')
                        ->unique()
                        ->toArray();

                    $this->managerIds = EmployeeDetails::whereIn('emp_id', $managers)
                        ->get(['emp_id', 'first_name', 'last_name', 'manager_id']);
                    $this->manager_id = $empdetails->manager_id;
                } else {
                    $this->sub_department_id = '';
                }

                $this->job_title = $empdetails->job_role;
                $this->emp_domain = $empdetails->emp_domain;
                $this->employee_status = $empdetails->employee_status;
                $this->mobile_number = $empdetails->emergency_contact;
                $this->shift_type = $empdetails->shift_type;
                $this->shift_start_time = $empdetails->shift_start_time ? $this->shift_start_time : "10:00";
                $this->shift_end_time = $empdetails->shift_type ? $this->shift_end_time : "19:00";
                $this->referrer = $empdetails->referral;
                $this->probation_period = $empdetails->probation_Period;
                $this->job_mode = $empdetails->job_mode;
                $this->probation_period = $empdetails->probation_Period;

                // Employee Personal details
                $empPersonalDetails = EmpPersonalInfo::where('emp_id', $this->emp_id)->first();
                if ($empPersonalDetails != null) {
                    $this->date_of_birth = $empPersonalDetails->date_of_birth;
                    $this->blood_group = $empPersonalDetails->blood_group;
                    $this->nationality = $empPersonalDetails->nationality;
                    $this->religion = $empPersonalDetails->religion;
                    $this->marital_status = $empPersonalDetails->marital_status;
                    $this->physically_challenge = $empPersonalDetails->physically_challenge;
                    $this->email = $empPersonalDetails->email;
                    $this->alternate_mobile_number = $empPersonalDetails->alternate_mobile_number;
                    $this->city = $empPersonalDetails->city;
                    $this->state = $empPersonalDetails->state;
                    $this->postal_code = $empPersonalDetails->postal_code;
                    $this->country = $empPersonalDetails->country;
                    $this->present_address = $empPersonalDetails->present_address;
                    $this->address = $empPersonalDetails->permenant_address;
                    $this->passport_no = $empPersonalDetails->passport_no;
                    $this->pan_no = $empPersonalDetails->pan_no;
                    $this->adhar_no = $empPersonalDetails->adhar_no;
                    $this->pf_no = $empPersonalDetails->pf_no;
                    $this->facebook = $empPersonalDetails->facebook;
                    $this->twitter = $empPersonalDetails->twitter;
                    $this->linked_in = $empPersonalDetails->linked_in;

                    // Educational Details
                    if ($empPersonalDetails->qualification != null) {
                        $this->education = json_decode($empPersonalDetails->qualification, true);
                    }


                    // Experience Details
                    if ($empPersonalDetails->experience != null) {
                        $this->experience = json_decode($empPersonalDetails->experience, true);
                    }
                }


                //  Employee Parents Details
                $empParentDetails = EmpParentDetails::where('emp_id', $this->emp_id)->first();
                if ($empParentDetails != null) {
                    $this->father_first_name = $empParentDetails->father_first_name;
                    $this->father_last_name = $empParentDetails->father_last_name;
                    $this->mother_first_name = $empParentDetails->mother_first_name;
                    $this->mother_last_name = $empParentDetails->mother_last_name;
                    $this->mother_dob = $empParentDetails->mother_dob;
                    $this->father_dob = $empParentDetails->father_dob;
                    $this->father_address = $empParentDetails->father_address;
                    $this->mother_address = $empParentDetails->mother_address;
                    $this->father_email = $empParentDetails->father_email;
                    $this->mother_email = $empParentDetails->mother_email;
                    $this->father_occupation = $empParentDetails->father_occupation;
                    $this->mother_occupation = $empParentDetails->mother_occupation;
                    $this->father_blood_group = $empParentDetails->father_blood_group;
                    $this->mother_blood_group = $empParentDetails->mother_blood_group;
                    $this->father_nationality = $empParentDetails->father_nationality;
                    $this->mother_nationality = $empParentDetails->mother_nationality;
                    $this->father_religion = $empParentDetails->father_religion;
                    $this->mother_religion = $empParentDetails->mother_religion;
                    $this->father_phone = $empParentDetails->father_phone;
                    $this->mother_phone = $empParentDetails->mother_phone;
                    $this->father_image_binary = $empParentDetails->father_image;
                    $this->mother_image_binary = $empParentDetails->mother_image;
                }

                //Employee Spouse Details
                $empSpouseDetails = EmpSpouseDetails::where('emp_id', $this->emp_id)->first();
                // dd( $empSpouseDetails);
                if ($empSpouseDetails != null) {
                    $this->spouse_first_name = $empSpouseDetails->name;
                    // $this->spouse_last_name = $empSpouseDetails->name;
                    $this->spouse_first_name = $empSpouseDetails->name;
                    $this->spouse_gender = $empSpouseDetails->gender;
                    $this->spouse_profession = $empSpouseDetails->profession;
                    $this->spouse_qualification = $empSpouseDetails->qualification;
                    $this->spouse_dob = $empSpouseDetails->dob;
                    $this->spouse_nationality = $empSpouseDetails->nationality;
                    $this->spouse_bld_group = $empSpouseDetails->bld_group;
                    $this->spouse_adhar_no = $empSpouseDetails->adhar_no;
                    $this->spouse_pan_no = $empSpouseDetails->pan_no;
                    $this->spouse_religion = $empSpouseDetails->religion;
                    $this->spouse_email = $empSpouseDetails->email;
                    $this->spouse_address = $empSpouseDetails->adhar_no;
                    // $this->children = $empSpouseDetails->children;
                    $this->children = json_decode($empSpouseDetails->children, true);
                }

                //Employee Bank Details
                $empBankDetails = EmpBankDetail::where('emp_id', $this->emp_id)->first();
                // dd($empBankDetails);
                if ($empBankDetails != null) {
                    $this->bank_name = $empBankDetails->bank_name;
                    $this->bank_branch = $empBankDetails->bank_branch;
                    $this->account_number = $empBankDetails->account_number;
                    $this->ifsc_code = $empBankDetails->ifsc_code;
                    $this->bank_address = $empBankDetails->bank_address;
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Handle the case where decryption fails
                abort(404, 'Invalid Employee ID');
            }
        }
    }
    public function updateEmployee()
    {
        $this->validate($this->validationRules());
        $employee = EmployeeDetails::find($this->employeeId);
        // Update the employee details
        if ($employee) {
            if ($this->image instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $this->image->store('employee_image', 'public');
            } else {
                $imagePath = $this->image; // Already a path, use as is
            }

            $employee->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'email' => $this->email,
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'mobile_number' => $this->mobile_number,
                'alternate_mobile_number' => $this->alternate_mobile_number,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'hire_date' => $this->hire_date,
                'employee_type' => $this->employee_type,
                'manager_id' => $this->manager_id,
                'report_to' => $this->report_to,
                'department' => $this->department,
                'job_title' => $this->job_title,
                'employee_status' => $this->employee_status,
                'emergency_contact' => $this->emergency_contact,
                'password' => $this->password,
                'image' => $imagePath,
                'blood_group' => $this->blood_group,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'marital_status' => $this->marital_status,
                'spouse' => $this->spouse,
                'physically_challenge' => $this->physically_challenge,
                'inter_emp' => $this->inter_emp,
                'job_location' => $this->job_location,
                'education' => $this->education,
                'experience' => $this->experience,
                'pan_no' => $this->pan_no,
                'aadhar_no' => $this->aadhar_no,
                'pf_no' => $this->pf_no,
                'nick_name' => $this->nick_name,
                'time_zone' => $this->time_zone,
                'biography' => $this->biography,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'linked_in' => $this->linked_in,
                'company_id' => $this->company_id,
                'is_starred' => $this->is_starred,
            ]);
            // Show success message
            session()->flash('emp_success', 'Employee updated successfully!');

            // Redirect to the employee list or any other desired route
            return redirect('/update-employee-details');
        }
    }

    public function render()
    {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $hrCompanies = Company::where('company_id',  $empCompanyId)->get();
        // $hrDetails = Company::where('company_id', $hrEmail)->first();
        $this->companies = $hrCompanies;
        // $this->hrDetails = $hrDetails;
        return view('livewire.add-employee-details', [
            'managerName' => $this->managerName,
            'companyName' => $this->company_name,
        ]);
    }
}
