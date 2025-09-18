<?php

use App\Models\Student;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

new #[Layout('components.layouts.app')] class extends Component {
    use WithFileUploads;

    // Personal Information
    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public string $address = '';

    #[Validate('nullable|date')]
    public string $date_of_birth = '';

    #[Validate('nullable|in:male,female,other')]
    public string $gender = '';

    #[Validate('nullable|string|max:50')]
    public string $nationality = '';

    #[Validate('nullable|string|max:50')]
    public string $religion = '';

    #[Validate('nullable|string|max:50')]
    public string $id_card_number = '';

    #[Validate('nullable|string|max:5')]
    public string $blood_group = '';

    // Emergency Contact
    #[Validate('nullable|string|max:150')]
    public string $emergency_contact_name = '';

    #[Validate('nullable|string|max:20')]
    public string $emergency_contact_phone = '';

    #[Validate('nullable|string|max:50')]
    public string $emergency_contact_relation = '';

    // Account Information
    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Validate('nullable|image|max:2048')]
    public $photo;

    public $student;
    public $user;
    public $program;
    public bool $showPasswordSection = false;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->student = Student::where('user_id', $this->user->id)->with('program')->first();
        
        // Load user data
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        
        // Load student data if exists
        if ($this->student) {
            $this->phone = (string) ($this->student->phone ?? '');
            $this->address = (string) ($this->student->address ?? '');
            $this->date_of_birth = $this->student->date_of_birth?->format('Y-m-d') ?? '';
            $this->gender = (string) ($this->student->gender ?? '');
            $this->nationality = (string) ($this->student->nationality ?? '');
            $this->religion = (string) ($this->student->religion ?? '');
            $this->id_card_number = (string) ($this->student->id_card_number ?? '');
            $this->blood_group = (string) ($this->student->blood_group ?? '');
            $this->emergency_contact_name = (string) ($this->student->emergency_contact_name ?? '');
            $this->emergency_contact_phone = (string) ($this->student->emergency_contact_phone ?? '');
            $this->emergency_contact_relation = (string) ($this->student->emergency_contact_relation ?? '');
            $this->program = $this->student->program;
        }
    }

    public function togglePasswordSection(): void
    {
        $this->showPasswordSection = !$this->showPasswordSection;
        if (!$this->showPasswordSection) {
            $this->password = '';
            $this->password_confirmation = '';
        }
    }

    public function updatePhoto(): void
    {
        $this->validate([
            'photo' => 'required|image|max:2048'
        ]);

        if ($this->photo) {
            // Delete old photo if exists
            if ($this->user->profile_photo_path) {
                Storage::disk('public')->delete($this->user->profile_photo_path);
            }

            // Store new photo
            $path = $this->photo->store('profile-photos', 'public');
            
            $this->user->update([
                'profile_photo_path' => $path
            ]);

            $this->photo = null;
            session()->flash('photo-status', 'Profile photo updated successfully!');
        }
    }

    public function save(): void
    {
        $this->validate();

        // Update user data
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $this->user->update($userData);

        // Update or create student record
        Student::updateOrCreate(
            ['user_id' => $this->user->id],
            [
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'date_of_birth' => $this->date_of_birth ?: null,
                'gender' => $this->gender ?: null,
                'nationality' => $this->nationality ?: null,
                'religion' => $this->religion ?: null,
                'id_card_number' => $this->id_card_number ?: null,
                'blood_group' => $this->blood_group ?: null,
                'emergency_contact_name' => $this->emergency_contact_name ?: null,
                'emergency_contact_phone' => $this->emergency_contact_phone ?: null,
                'emergency_contact_relation' => $this->emergency_contact_relation ?: null,
            ]
        );

        // Reset password fields
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPasswordSection = false;

        // Refresh student data
        $this->student = Student::where('user_id', $this->user->id)->with('program')->first();
        
        session()->flash('status', 'Profile updated successfully!');
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Student Profile</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your personal information and account settings</p>
        </div>
    </div>

    <!-- Status Messages -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-auth-session-status class="mb-4" :status="session('photo-status')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Photo Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Profile Photo</h3>
                
                <div class="flex flex-col items-center">
                    <div class="relative mb-4">
                        @if($user->profile_photo_path)
                            <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="Profile Photo" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-neutral-600">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-gray-200 dark:border-neutral-600">
                                <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                        @endif
                        <button type="button" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 shadow-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit="updatePhoto" class="w-full">
                        <flux:input type="file" wire:model="photo" accept="image/*" label="Choose New Photo" />
                        @if($photo)
                            <flux:button type="submit" variant="primary" class="w-full mt-3">Update Photo</flux:button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Programme Information -->
            @if($program)
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Programme Details</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Programme</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $program->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Programme Code</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $program->code }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $program->department }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $program->duration_years }} years ({{ $program->total_semesters }} semesters)</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Degree Awarded</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $program->degree_awarded }}</p>
                    </div>
                    @if($student)
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $student->student_id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($student->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                   ($student->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Profile Form -->
        <div class="lg:col-span-2">
            <form wire:submit="save" class="space-y-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="name" label="Full Name" required />
                        <flux:input wire:model="email" label="Email Address" type="email" required />
                        <flux:input wire:model="phone" label="Phone Number" />
                        <flux:input wire:model="date_of_birth" label="Date of Birth" type="date" />
                        
                        <div>
                            <flux:select wire:model="gender" label="Gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </flux:select>
                        </div>
                        
                        <flux:input wire:model="nationality" label="Nationality" />
                        <flux:input wire:model="religion" label="Religion" />
                        <flux:input wire:model="id_card_number" label="National ID/NRC Number" />
                        <flux:input wire:model="blood_group" label="Blood Group" placeholder="e.g., O+, A-, B+" />
                        
                        <div class="md:col-span-2">
                            <flux:textarea wire:model="address" label="Address" rows="3" />
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Information -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Emergency Contact</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="emergency_contact_name" label="Contact Name" />
                        <flux:input wire:model="emergency_contact_phone" label="Contact Phone" />
                        <div class="md:col-span-2">
                            <flux:input wire:model="emergency_contact_relation" label="Relationship" placeholder="e.g., Parent, Spouse, Sibling" />
                        </div>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Account Security</h3>
                        <flux:button type="button" wire:click="togglePasswordSection" variant="outline" size="sm">
                            {{ $showPasswordSection ? 'Cancel' : 'Change Password' }}
                        </flux:button>
                    </div>
                    
                    @if($showPasswordSection)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="password" label="New Password" type="password" />
                        <flux:input wire:model="password_confirmation" label="Confirm New Password" type="password" />
                    </div>
                    @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">Click "Change Password" to update your account password.</p>
                    @endif
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" size="lg">
                        Save Profile
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>


