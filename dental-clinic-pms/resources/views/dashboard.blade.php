<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ ucfirst($user->role) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($user->role === 'administrator')
                {{-- Administrator Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stats Cards -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-900">
                            <h3 class="text-lg font-semibold">Total Patients</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $data['total_patients'] }}</p>
                            <p class="text-sm text-gray-500">{{ $data['new_patients_this_month'] }} new this month</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-900">
                            <h3 class="text-lg font-semibold">Today's Appointments</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $data['total_appointments_today'] }}</p>
                            <p class="text-sm text-gray-500">{{ $data['upcoming_appointments'] }} upcoming</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-900">
                            <h3 class="text-lg font-semibold">Revenue This Month</h3>
                            <p class="text-3xl font-bold text-yellow-600">₱{{ number_format($data['revenue_this_month'], 2) }}</p>
                                <p class="text-sm text-gray-500">₱{{ number_format($data['outstanding_balance'], 2) }} outstanding</p>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-900">
                            <h3 class="text-lg font-semibold">Low Stock Items</h3>
                            <p class="text-3xl font-bold text-red-600">{{ $data['low_stock_items'] }}</p>
                            <p class="text-sm text-gray-500">Require attention</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Activities -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                            <div class="space-y-4">
                                @foreach($data['recent_activities'] as $activity)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                            <span class="text-{{ $activity['color'] }}-600">•</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm">{{ $activity['message'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Staff Overview -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Staff Overview</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Active Dentists:</span>
                                    <span class="font-semibold">{{ $data['active_dentists'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Staff:</span>
                                    <span class="font-semibold">{{ $data['active_staff'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($user->role === 'dentist')
                {{-- Dentist Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Today's Appointments</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $data['todays_appointments']->count() }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Active Treatment Plans</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $data['active_treatment_plans'] }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Patients This Week</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $data['patients_treated_this_week'] }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Total Patients</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $data['total_patients'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Today's Appointments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Today's Schedule</h3>
                            @if($data['todays_appointments']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['todays_appointments'] as $appointment)
                                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                                            <p class="font-semibold">{{ $appointment->appointment_datetime->format('g:i A') }}</p>
                                            <p class="text-sm">{{ $appointment->patient->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $appointment->appointment_type }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No appointments scheduled for today.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Treatment Plans -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Pending Treatment Plans</h3>
                            @if($data['pending_treatment_plans']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['pending_treatment_plans'] as $plan)
                                        <div class="border border-gray-200 rounded p-3">
                                            <p class="font-semibold">{{ $plan->patient->full_name }}</p>
                                            <p class="text-sm">{{ $plan->plan_title }}</p>
                                            <p class="text-xs text-gray-500">${{ number_format($plan->estimated_cost, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No pending treatment plans.</p>
                            @endif
                        </div>
                    </div>
                </div>

            @elseif($user->role === 'receptionist')
                {{-- Receptionist Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Today's Appointments</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $data['todays_appointments']->count() }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Tomorrow's Appointments</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $data['tomorrows_appointments']->count() }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Pending Confirmations</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $data['pending_confirmations'] }}</p>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Overdue Invoices</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $data['overdue_invoices'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Today's Appointments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Today's Appointments</h3>
                            @if($data['todays_appointments']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['todays_appointments'] as $appointment)
                                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                                            <p class="font-semibold">{{ $appointment->appointment_datetime->format('g:i A') }}</p>
                                            <p class="text-sm">{{ $appointment->patient->full_name }}</p>
                                            <p class="text-xs text-gray-500">Dr. {{ $appointment->dentist->name }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No appointments scheduled for today.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Registrations -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Recent Patient Registrations</h3>
                            @if($data['recent_registrations']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['recent_registrations'] as $patient)
                                        <div class="border border-gray-200 rounded p-3">
                                            <p class="font-semibold">{{ $patient->full_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $patient->patient_id }}</p>
                                            <p class="text-xs text-gray-500">{{ $patient->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No recent registrations.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($user->role === 'administrator' || $user->role === 'receptionist')
                            <a href="{{ route('patients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                New Patient
                            </a>
                            <a href="{{ route('appointments.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                                Schedule Appointment
                            </a>
                        @endif
                        
                        @if($user->role === 'administrator' || $user->role === 'dentist')
                            <a href="{{ route('treatment-plans.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">
                                Create Treatment Plan
                            </a>
                        @endif
                        
                        @if($user->role === 'administrator' || $user->role === 'receptionist')
                            <a href="{{ route('invoices.create') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                                Generate Invoice
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
