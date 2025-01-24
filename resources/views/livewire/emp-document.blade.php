<div >
<style>/* Card Container */


/* General Styles */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    border-bottom: solid 1px #c6c6c6;
    max-width: 100%;
    border-radius: 8px;
}

.card-header h2 {
    font-size: 20px;
    color: #333;
    margin: 0;
}

.status-unpublished,
.status-published {
    background-color: #fff8f0;
    padding: 4px 8px;
    border-radius: 20px;
    border: 1px solid #f09541;
    font-size: 10px;
    color: #e07a1b;
}

/* Content */
.card-content p {
    font-size: 16px;
    color: #555;
    margin: 8px 0;
}

/* Footer */
.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
}

/* Left footer - Date */
.footer-left .date-time {
    font-size: 14px;
    color: #888;
}
.modal {
 
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Prevents content overflow */
}


/* Right footer - Document thumbnail */
.footer-right .document-thumbnail {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    object-fit: cover;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Card container */
.document-card {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 16px;
    border: 1px solid #c6c6c6;
    background-color: white;
    margin: 16px;
    width: 50%;
}

/* Mobile Responsiveness */
@media (max-width: 767px) {
    /* Adjusting card container for mobile screens */
    .document-card {
        width: 100%; /* Full width on mobile */
        margin: 8px 0; /* Reduce margin */
    }

    /* Adjust the card header for smaller screens */
    .card-header {
        flex-direction: column; /* Stack items vertically */
        align-items: flex-start;
    }

    /* Make text larger for readability on small screens */
    .card-header h2 {
        font-size: 18px;
    }

    /* Center the "status" button and reduce its size */
    .status-unpublished,
    .status-published {
        font-size: 8px;
        padding: 2px 6px;
        margin-top: 8px;
    }

    /* Adjust the footer layout */
    .card-footer {
        flex-direction: column; /* Stack footer items vertically */
        align-items: flex-start; /* Align left */
        margin-top: 12px;
    }

    .footer-left,
    .footer-right {
        margin-bottom: 8px; /* Add margin for spacing */
    }

    /* Make the upload button and file input more compact */
    .upload-button {
        font-size: 12px;
        margin-top: 8px;
    }

    .form-check-label {
        font-size: 12px;
    }

    /* Adjust modal dialog for smaller screens */
    .modal-dialog {
        max-width: 70%; /* Make modal wider on small screens */
    }

    /* Adjust button sizes */
    .submit-btn,
    .cancel-btn {
        font-size: 12px;
        padding: 6px 12px;
    }

    /* Adjust select dropdowns for smaller screens */
    .custom-select-doc {
        font-size: 12px;
    }

    /* Adjust the dropdown for the second select */
    .dropdown {
        margin-left: 0;
    }

    /* Reduce button sizes in the modal */
    .modal-footer button {
        font-size: 12px;
        padding: 8px 12px;
    }
}

/* Larger screens (Tablets and above) */
@media (min-width: 768px) and (max-width: 1024px) {
    .document-card {
        width: 70%; /* Adjust width for medium-sized screens */
    }

    .card-header {
        flex-direction: row; /* Restore horizontal layout */
        align-items: center;
    }
}



</style>
<div class="row" style="margin-top:-20px;">
<ul class="nav custom-nav-tabs" role="tablist" >
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>
</div>


<div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
        This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-9 custom-container d-flex flex-column bg-white" >
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text mt-1">Employee Type:</p>
                       
                        <div class="dropdown">
                        <button class="btn btn dropdown-toggle dp-info" type="button" data-bs-toggle="dropdown" style="font-size:12px">
    Employee: {{ ucfirst($selectedOption) }} 
    <span class="arrow-for-employee"></span><span class="caret"></span>
</button>

    <span class="caret"></span></button>
    <ul class="dropdown-menu" style="font-size:12px; ">
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('all')" class="dropdown-item custom-info-item">All Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('current')" class="dropdown-item custom-info-item">Current Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('past')" class="dropdown-item custom-info-item">Resigned Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('intern')" class="dropdown-item custom-info-item">Intern</a>
    </li>
</ul>

  </div>
         

                      
                    </div>
                 
                    <div class="profile">
    <div class="col m-0">
        <div class="row d-flex align-items-center">
            <!-- Search Input -->
            <div class="input-group4 d-flex align-items-center">
                <!-- Input Field with Profile Image -->
                <div class="position-relative">
                    @if($selectedEmployeeId && $selectedEmployeeFirstName)
                    <img 
        src="{{ $selectedEmployeeImage ? 'data:image/jpeg;base64,' . $selectedEmployeeImage : asset('images/user.jpg') }}" 
        alt="Profile Image" 
        class="profile-image-input"
        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;" 
    />
                    @else
                        <img 
                         src="{{ asset('images/user.jpg') }}" alt="Default Image"
                            
                            class="profile-image-input"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;"
                        />
                    @endif

               
                  <!-- Search Input Field -->
<input
    wire:model.debounce.500ms="searchTerm"
    aria-label="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    placeholder="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    type="text"
    class="form-control search-term"
    style="padding-left: 50px; padding-right: 35px;" 
/>

<!-- Display Close Icon if Employee is Selected -->
@if($selectedEmployeeId)
    <svg class="close-icon-person"  
         wire:click="removePerson('{{ $selectedEmployeeId }}')" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
         style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@else
    <!-- Display Search Icon if No Employee is Selected -->
    <i 
        class="bx bx-search search-icon position-absolute" width="20" height="20"
        wire:click="searchforEmployee"
        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
    </i>
@endif

                </div>
            </div>

            <!-- Conditional Display of Search Results -->
            @if($searchTerm)
                @if($employees->isEmpty())
                    <div class="search-container">
                        <p>No People Found</p>
                    </div>
                @else
                    <div class="search-container" style="max-height: 250px; overflow-y: auto;">
                        @foreach($employees as $employee)
                            @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
                                <label wire:click="selectEmployee('{{ $employee->emp_id }}')" style="cursor: pointer;">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <input 
                                                type="checkbox" 
                                                id="employee-{{ $employee->emp_id }}"
                                                wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"
                                                value="{{ $employee->emp_id }}"
                                                class="form-check-input custom-checkbox-information"
                                                {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                                        </div>
                                        <div class="col-auto">
                                            @if($employee->image && $employee->image !== 'null')
                                                <img class="profile-image" src="data:image/jpeg;base64,{{ $employee->image }}">
                                            @else
                                                <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h6 class="name" style="font-size: 12px; color: black;">
                                                {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }}
                                            </h6>
                                            <p style="font-size: 12px; color: grey;">(#{{ $employee->emp_id }})</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>


                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="{{ asset('images/employeeleave.png') }}"  alt="Employee Image" style="height: 180px; width:280px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>

 
 
    @if(!empty($selectedEmployeeId && $selectedEmployeeFirstName))
    <div class="row mt-3 p-0 justify-content-center">
    </div>

   @foreach((array) $selectedEmployeeId  as $emp_id)
        @php
            $employee = $employees->firstWhere('emp_id', $emp_id);
        @endphp

        @if($employee)
            <div class="row" style="margin: 5px; auto;width:100%;justify-content:center;align-items:center;margin-left:130px">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link tab-doc {{ $activeTab1 === 'tab1' ? 'active' : '' }}" wire:click.prevent="switchTab('tab1')" href="#">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-doc {{ $activeTab1 === 'tab2' ? 'active' : '' }}" wire:click.prevent="switchTab('tab2')" href="#">Letters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-doc {{ $activeTab1 === 'tab3' ? 'active' : '' }}" wire:click.prevent="switchTab('tab3')" href="#">Payslip</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-doc {{ $activeTab1 === 'tab4' ? 'active' : '' }}" wire:click.prevent="switchTab('tab4')" href="#">Forms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tab-doc {{ $activeTab1 === 'tab5' ? 'active' : '' }}" wire:click.prevent="switchTab('tab5')" href="#">Company Policies</a>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content">
                    <!-- Documents Tab -->
                    @if($activeTab1 === 'tab1')
                        <div class="tab-pane fade show active">
                            <!-- Documents Filter and Add -->
                            <div class="container mt-3">
                                <div class="row justify-content-center">
                                    <div class="row mt-3">
                                        <!-- First Dropdown -->
                                        <div class="col-12 col-md-3 mb-2">
                                            <select class="custom-select-doc dropdown-toggle" name="category" wire:model="filter_option" wire:change="loadDocuments">
                                                <option value="All">All</option>
                                                <option value="Accounts & Statutory">Accounts & Statutory</option>
                                                <option value="Address">Address</option>
                                                <option value="Background Verification">Background Verification</option>
                                                <option value="Education">Education</option>
                                                <option value="Experience">Experience</option>
                                                <option value="Joining Kit">Joining Kit</option>
                                                <option value="Previous Employment">Previous Employment</option>
                                                <option value="Projects">Projects</option>
                                                <option value="Qualification">Qualification</option>
                                                <option value="Vaccination Certificate">Vaccination Certificate</option>
                                            </select>
                                        </div>

                                        <!-- Second Dropdown -->
                                        <div class="col-12 col-md-2 mb-2" >
                                            <select class="custom-select-doc" name="view" wire:model="filter_publishtype" wire:change="publishType">
                                                <option value="All" selected>All</option>
                                                <option value="Published">Published</option>
                                                <option value="Unpublished">Unpublished</option>
                                            </select>
                                        </div>

                                        <!-- Add Document Button -->
                                        <div class="col-12 col-md-6 mb-2">
                                            <button class="btn btn-primary" style="font-size: 12px;" wire:click="addDocs">Add Documents</button>
                                        </div>
                                    </div>

                                    <!-- Document Modal -->
                                    @if($showDocDialog)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; overflow-y: auto;">
        <div class="modal-dialog modal-dialog-centered modal-lg align-items-center justify-content-center" role="document">
            <div class="modal-content" style="width:60%;">
                <div class="modal-header helpdesk-modal align-items-center">
                    <h5 class="modal-title helpdesk-title"><b>Documents</b></h5>
                    <button type="button" class="btn-close" wire:click="$set('showDocDialog', false)" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 500px; padding: 20px;">
                    <!-- Employee Details -->
                    <div class="mb-3">
                        <label for="employeeId" class="col-form-label" style="font-size: 12px;">Employee</label>
                        <input type="text" class="form-control" id="employeeId" name="employeeId" readonly value="{{ $fullName }}@if($selectedEmployeeId) (#{{ strtoupper($selectedEmployeeId) }}) @endif" style="font-size: 12px;width:70%">
                    </div>

                    <!-- Document Name -->
                    <div class="mb-3">
                        <label for="documentName" class="col-form-label" style="font-size: 12px;">Document Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="documentName" name="documentName" wire:model.defer="documentName" style="width:70%" >
                        @error('documentName') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="col-form-label" style="font-size: 12px;">Category <span style="color:red">*</span></label>
                        <div class="dropdown-container ">
                            <select wire:model.lazy="category" wire:keydown.debounce.500ms="validateField('category')" id="category" name="category" class="form-control dropdown-toggle" style="font-size: 12px;width:70%">
                                <option style="color: #778899;" value="">Select Category</option>
                                <option value="Accounts & Statutory">Accounts & Statutory</option>
                                <option value="Address">Address</option>
                                <option value="Background Verification">Background Verification</option>
                                <option value="Education">Education</option>
                                <option value="Experience">Experience</option>
                                <option value="Joining Kit">Joining Kit</option>
                                <option value="Previous Employment">Previous Employment</option>
                                <option value="Projects">Projects</option>
                                <option value="Qualification">Qualification</option>
                                <option value="Vaccination Certificate">Vaccination Certificate</option>
                            </select>
                        </div>
                        @error('category') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label" style="font-size: 12px;">Description<span style="color:red">*</span></label>
                        <textarea class="form-control" id="description" name="description" wire:model.defer="description" style="font-size: 12px; height: 40px;width:70%"></textarea>
                        @error('description') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label" style="font-size: 12px;">File:</label>
                        <input type="file" id="file" name="file" class="d-none" accept=".pdf,.xls,.xlsx,.doc,.docx,.txt,.ppt,.pptx,.gif,.jpg,.png" wire:model="file_path" >
                        <label for="file" class="upload-button d-inline-flex align-items-center" style="cursor: pointer;">
                            <i class="bx bx-upload upload-icon me-1"></i>
                            <span class="text-primary">Upload File</span>
                        </label>
                        <div class="mt-2">
                            <span class="text-muted" style="font-size: 12px;">
                                @if($file_path)
                                    Selected file: {{ $file_path->getClientOriginalName() }}
                                @else
                                    No file chosen
                                @endif
                            </span>
                        </div>
                        @error('file') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                        <p class="form-text text-muted" style="font-size: 8px;">Note: Only PDF, XLS, XLSX, DOC, DOCX, TXT, PPT, PPTX, GIF, JPG, PNG files are accepted.</p>
                    </div>

                    <!-- Publish to Portal -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="publishToPortal" name="publishToPortal" wire:model="publishToPortal">
                        <label class="form-check-label" for="publishToPortal" style="font-size: 12px;">Publish to Employee Portal</label>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center" style="padding: 10px;">
                    <button wire:click="submit" class="submit-btn" type="button">Submit</button>
                    <button wire:click="$set('showDocDialog', false)" class="cancel-btn" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@endif

                                </div>
                            </div>

                            <!-- Document Cards -->
                            @if ($documents && $documents->isNotEmpty())
    @foreach ($documents as $document)
        <div class="document-card col-12 col-md-6 col-lg-4 mb-3" 
        
        >
            <div class="card-header" style="border-bottom:none">
                <img src="{{ asset('images/emp-document.png') }}" style="height:40px;width:40px;">
                <div class="col">
                    <div class="row">
                        <p style="margin-bottom: 5px;">{{ $document->document_name }}</p>
                        <p class="main-text" style="margin-top: -5px;">{{ $document->description }}</p>
                        <p class="main-text" style="margin-top: -15px;">{{ $document->category }}</p>
                        <p class="main-text" style="margin-top: -15px;">{{ $document->created_at ? $document->created_at->format('d-m-y h:i A') : 'N/A' }}</p>
                    </div>
                </div>
                <button class="{{ $document->publish_to_portal === null ? 'status-unpublished' : 'status-published' }}">
                    {{ $document->publish_to_portal === null ? 'Unpublished' : 'Published' }}
                </button>
            </div>

          
                    @if ($document->file_path)
                        @if(strpos($document->mime_type, 'image') !== false)
                        <div class="card-footer" style="border-top:1px solid #ccc;margin-top:-10px">
                        <div class="footer-left">
                            <span class="date-time">{{ $document->file_name ?? 'No file name available' }}
                                <i class="bx bx-download" style="font-size: 1.2em; color: blue;margin-left:5px;margin-top:5px;cursor:pointer" wire:click.prevent="showImage('{{ $document->getImageUrlAttribute() }}')"></i>
                            </span>
                            </div>
                            <div class="footer-right">
                    @if ($showImageDialog)
                        <div class="modal fade show d-block" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">View File</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ $imageUrl }}" class="img-fluid" alt="Image preview" style="width:50%;height:50%">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="submit-btn" wire:click.prevent="downloadImage">Download</button>
                                        <button type="button" class="cancel-btn" wire:click="closeImageDialog">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                    @endif
                </div>
                </div>
                        @endif
                    @endif
         
             
          
        </div>
    @endforeach
@else
                                <div class="alert alert-info d-flex align-items-center mt-5" role="alert" style="width:60%">
                                    <p class="main-text mb-0">There are no documents available!</p>
                                </div>
                            @endif
                        </div>
                    @elseif($activeTab1 === 'tab2')
                        <div class="tab-pane fade show active">
                        
                  
    @if($requests->isNotEmpty() && !empty($selectedEmployeeId))
        @foreach((array)$selectedEmployeeId as $employeeId)
            {{-- Filter requests for the current employee --}}
            @php
                $employeeRequests = $requests->where('emp_id', $employeeId);
            @endphp

            {{-- Loop through each request and create a separate container --}}
            @if($employeeRequests->isNotEmpty())
                @foreach($employeeRequests as $request)
                <div class="document-card col-12 col-md-6 col-lg-4 ">
                                        <div class="card-header" style="border-bottom:none">
                                            <img src="{{ asset('images/emp-document.png') }}" style="height:40px;width:40px;">
                                           
                                            <div class="col">
                                                <div class="row">
                                                    <p style="margin-bottom: 5px;">{{ $request->letter_type }} </p>
                                                    <p class="main-text" style="margin-top: -5px;"> {{ $request->priority }}</p>
                                                    <p class="main-text" style="margin-top: -15px;"> {{ $request->reason }}</p>
                                                    <p class="main-text" style="margin-top: -15px;">{{ $request->created_at ? $request->created_at->format('d-m-y h:i A') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        
                                        </div>

                                     
                                    </div>
                
                @endforeach
            @else
                {{-- No requests found for the current employee --}}
                <div class="request-item-container">
                    <h3>No requests found for Employee ID: {{ $employeeId }}</h3>
                </div>
            @endif
        @endforeach
    @else
        {{-- No requests available --}}
        <p>No requests available for this employee.</p>
    @endif




                        </div>
                    @elseif($activeTab1 === 'tab3')
                        <div class="tab-pane fade show active">
                            <h3>Content for Tab 3</h3>
                            <p>This is the content for the third tab.</p>
                        </div>
                    @elseif($activeTab1 === 'tab4')
                        <div class="tab-pane fade show active">
                            <h3>Content for Tab 4</h3>
                            <p>This is the content for the fourth tab.</p>
                        </div>
                    @elseif($activeTab1 === 'tab5')
                        <div class="tab-pane fade show active">
                            <h3>Content for Tab 5</h3>
                            <p>This is the content for the fifth tab.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
@endif


</div>
<div class="tab-page" data-tab-page="in-review">
<div class="row mt-3 ml-3" style="font-size:12px">
      
      <div id="employee-container">
      @foreach($employeess as $employee)
  <span style="font-weight:600">
      Added New Employee: ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}
  </span>
  @if (!$loop->first)<br>@endif
  <p class="main-text">
      Hire Date: 
      @if($employee->hire_date)
          @php
              $hireDate = \Carbon\Carbon::parse($employee->hire_date);
          @endphp
          ({{ $hireDate->format('M d, Y') }})
      @else
          N/A
      @endif
  </p>
  @if (!$loop->first)<br>@endif
@endforeach

</div>





    
    
    
            <br>
        
    
    
     
                </div>
</div>
       </div> <!-- Tab buttons -->
</div>
<script>
    window.addEventListener('/hr/emp-document', () => {
        // Go back to the previous page without refreshing
        history.back();
    });
</script>



<script>
    function selectOption(option) {
        console.log("Selected: " + option);
        // You can implement further logic here, such as submitting a form or updating the UI.
    }
</script>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('goBackToEmpDocument', () => {
            console.log('Event received');
            Livewire.navigate('/hr/emp-document');
        });
    });
</script>




    </div>