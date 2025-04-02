<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\PostCreatedNotification;
use App\Models\Company;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDetails;
use App\Models\Hr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class Everyone extends Component
{
    
    use WithFileUploads;

    public $image;
    public $posts;

    public $showAlert = false;
    public $file_path;

    public $category;
    public $isManager;
    public $employeeId;
    public $showKudosDialog = false;
    public $attachment;
    public $employeeDetails;
    public $message = '';
    public $showFeedsDialog = false;
    public $empCompanyLogoUrl;

    protected $rules = [
        'category' => 'required',
        'description' => 'required',
        'attachment' => 'nullable|file|max:10240',
    ];
    protected $messages = [
        'category.required' => 'Category is required.',
  
        'description.required' => 'Description is required.',
       
    ];
    public $showImageDialog=false;
    public function addFeeds()
    {
        $this->showFeedsDialog = true;
    }
  
    public function openPost($postId)
    {
        $post = Post::find($postId);
    
        if ($post) {
            $post->update(['status' => 'Open']);
        }
    
        return redirect()->to('/feeds'); // Redirect to the appropriate route
    }
    public $description;

    // Listener for the emitted event
    protected $listeners = ['updateDescription'];

    // Method to update the description when the event is triggered
    public function updateDescription($description)
    {
        // Log received description for debugging
        Log::info('Description received in Livewire:', ['description' => $description]);

        // Update the Livewire description property
        $this->description = $description;
    }


    
    public function mount()
    {
     
        $this->posts  = Post::where('status', 'Closed')
             ->orderBy('updated_at', 'desc')
             ->get();

        $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();
            // Fetch the logged-in employee's ID
$employeeId = Auth::guard('hr')->user()->emp_id;

// Fetch emp_id and company_id for the logged-in employee
$employeeDetails = DB::table('employee_details')
    ->where('emp_id', $employeeId)
    ->select('emp_id', 'company_id') // Select specific columns
    ->first(); // Fetch the result as an object



// Fetch the company_id for the logged-in employee's parent company
$company = DB::table('employee_details')

->select('company_id') // Select specific columns
->first(); // Fetch the result as an object     

  
        $user = Auth::user();

        if ($user) {
            $this->employeeDetails = $user->employeeDetails;
        }
    
        $this->isManager = DB::table('employee_details')
            ->where('manager_id', $employeeId)
            ->exists();
       
        
            if ($this->isManager) {
                // For managers: get their posts and their team's posts
                $this->posts = Post::where('status', 'Closed')
                    ->where(function ($query) use ($employeeId) {
                        $query->where('manager_id', $employeeId) // Manager's own posts
                              ->orWhereIn('emp_id', function ($subQuery) use ($employeeId) {
                                  $subQuery->select('emp_id')
                                            ->from('employee_details')
                                            ->where('manager_id', $employeeId); // Team members' posts
                              });
                    })
                    ->orWhereNull('emp_id') 
                    ->orderBy('updated_at', 'desc')
                    ->get();
            } else {
               // For employees: get their posts, their manager's posts, and HR posts
$this->posts = Post::where('status', 'Closed')
->where(function ($query) use ($employeeId) {
$query->where('emp_id', $employeeId) // Employee's own posts
      ->orWhere('manager_id', function ($subQuery) use ($employeeId) {
          $subQuery->select('manager_id')
                   ->from('employee_details')
                   ->where('emp_id', $employeeId); // Get the employee's manager's ID
      })
      ->orWhereIn('emp_id', function ($subQuery) use ($employeeId) {
          // Get all employees under the same manager
          $subQuery->select('emp_id')
                   ->from('employee_details')
                   ->where('manager_id', function ($innerQuery) use ($employeeId) {
                       $innerQuery->select('manager_id')
                                  ->from('employee_details')
                                  ->where('emp_id', $employeeId);
                   });
      });
})
->orWhereNull('emp_id') 
->orderBy('updated_at', 'desc')
->get();

            
            }
        
        
    
                // Debugging to check the retrieved posts
               
            }
  
            private function getEmpCompanyLogoUrl()
            {
                // Get the current authenticated employee's company ID
                if (auth()->check()) {
                    // Get the current authenticated employee's company ID
                    $empCompanyId = auth()->user()->company_id;
                    $employeeId = auth()->user()->emp_id;
                    $employeeDetails = DB::table('employee_details')
                        ->where('emp_id', $employeeId)
                        ->select('company_id') // Select only the company_id
                        ->first();
        
                    // Assuming you have a Company model with a 'company_logo' attribute
                    $companyIds = json_decode($employeeDetails->company_id);
                    $company = DB::table('companies')
                        ->where('company_id', $companyIds)
                        ->where('is_parent', 'yes')
                        ->first();
        
                    // Return the company logo URL, or a default if company not found
                    return $company ? $company->company_logo : asset('user.jpg');
                } elseif (auth()->guard('hr')->check()) {
                    $empCompanyId = auth()->guard('hr')->user()->company_id;
        
                    // Assuming you have a Company model with a 'company_logo' attribute
                    $company = Company::where('company_id', $empCompanyId)->first();
                    return $company ? $company->company_logo : asset('user.jpg');
                }
            }
    public function closeFeeds()
    {
        
        $this->message = '';
        $this->showFeedsDialog = false;
        $this->resetErrorBag(); // Reset validation errors if any
        $this->resetValidation(); // Reset validation state
        $this->reset(['category', 'description', 'attachment', 'message', 'showFeedsDialog']);
    }
    public function handleRadioChange($value)
{
    // Define the URLs based on the radio button value
    $urls = [
        'posts' => '/hr/everyone',
        'activities' => '/hr/hrFeeds',
        'kudos' => '/kudos',
        'post-requests'=>'/emp-post-requests'
        // Add more mappings if necessary
    ];

    // Redirect to the correct URL
    if (array_key_exists($value, $urls)) {
        return redirect()->to($urls[$value]);
    }
}
    public function upload()
    {
        $this->validate([
            'attachment' => 'required|file|max:10240',
        ]);

        $this->attachment->store('attachments');
        $this->message = 'File uploaded successfully!';
    }



    public function submit()
    {
        $validatedData = $this->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Only allow image files
        ]);

        try {
            $fileContent = null;
            $mimeType = null;
            $fileName = null;

            // Process the uploaded image file
            if ($this->file_path) {
                if (!in_array($this->file_path->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])) {
                    session()->flash('error', 'Only image files (jpeg, png, gif, svg) are allowed.');
                    return;
                }

                $fileContent = file_get_contents($this->file_path->getRealPath());
                $mimeType = $this->file_path->getMimeType();
                $fileName = $this->file_path->getClientOriginalName();
    
                if ($fileContent === false || strlen($fileContent) > 16777215) {
                    FlashMessageHelper::flashWarning('File size exceeds the allowed limit or could not be read.');
                    return;
                }
            }
    
            $user = Auth::user();
            $employeeId = auth()->user()->emp_id;
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
             // Fetch the manager_id of the current employee
             $managerId = $employeeDetails->manager_id;
    
             if (!$managerId) {
                 FlashMessageHelper::flashError('Manager information not found for the current employee.');
                 return;
             }
     
             // Check if the authenticated employee is a manager
             $isManager = DB::table('employee_details')
                 ->where('manager_id', $employeeId)
                 ->exists();
     
             $postStatus = $isManager ? 'Closed' : 'Pending';
             $managerId = $isManager ? $employeeId : null;
             $empId = $isManager ? null : $employeeId;
    
           
    
            $hrDetails = Hr::where('hr_emp_id', $user->hr_emp_id)->first();
    
            $post = Post::create([
                'hr_emp_id' => $hrDetails->hr_emp_id ?? '-',
                'manager_id' => $managerId,
                'emp_id' => $empId,
                'category' => $this->category,
                'description' => $this->description,
                'file_path' => $fileContent,
                'mime_type' => $mimeType,
                'file_name' => $fileName,
                'status' => $postStatus,
            ]);
    
    
            $this->dispatch('postSubmitted');
            // Reset form fields and redirect to posts page
            $this->reset(['category', 'description', 'file_path']);
    
            FlashMessageHelper::flashSuccess('Post created successfully!');
           
            $this->showFeedsDialog=false;
             // Update 'manager.posts' to the actual route name for the posts page
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage(), [
                'employee_id' => $employeeId ?? 'N/A',
                'file_path_length' => isset($fileContent) ? strlen($fileContent) : null,
            ]);
            FlashMessageHelper::flashError('An error occurred while creating the post. Please try again.');
        }
    }
    
    
    
    
    

    public function render()
    {

          if (auth()->guard('hr')->check()) {
            $this->employeeDetails = Hr::where('hr_emp_id', Auth::user()->hr_emp_id)->first();
        } elseif (auth()->guard('emp')->check()) {
            $this->employeeDetails = EmployeeDetails::where('emp_id', Auth::user()->emp_id)->first();
        } else {
            // Handle case where no guard is matched
            Session::flash('error', 'User is not authenticated as HR or Employee');
            return;
        }
        
        return view('livewire.everyone',[     'empCompanyLogoUrl' => $this->empCompanyLogoUrl,     ]);
    }
}
