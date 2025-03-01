<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ $familyDetail->family_name }} Personal Information
        </h2>
    </x-slot>
    <div id="successMessage" class="hidden fixed top-10 left-1/2 transform -translate-x-1/2 bg-green-500 text-white p-4 rounded-md shadow-md">
        <!-- Success message will be dynamically inserted here -->
    </div>
    <div class="flex items-center justify-center mt-20 ">
        <form id="filterForm" action="{{ route('family-details.show', $familyDetail->id) }}" method="GET">
            <!-- Month Dropdown -->
            <select name="month" id="month" class="py-2 border rounded-md">
                <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>January</option>
                <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>February</option>
                <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>March</option>
                <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>April</option>
                <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>May</option>
                <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>June</option>
                <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>July</option>
                <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>August</option>
                <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>October</option>
                <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>December</option>
            </select>
            <!-- Year Dropdown -->
            <select name="year" id="year" class="py-2 border rounded-md">
                <!-- Default to current year -->
                @foreach(range(date('Y'), 2000) as $yearOption)
                    <option value="{{ $yearOption }}" {{ request('year', date('Y')) == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mt-2">
                Filter
            </button>
        </form>
    </div>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-center justify-center bg-gray-100">
            <!-- Family Details Card -->
            <div class="lg:w-80 p-6 h-[22rem] bg-white rounded-lg shadow-lg">
                <h3 class="text-lg font-medium text-gray-700 mb-4 text-center">Family Details</h3>
                <div class="space-y-4">
                    @if($familyDetail)
                        <!-- Display Family Details -->
                        <div class="flex justify-between">
                            <p class="font-semibold text-gray-600">Family Name:</p>
                            <p class="text-gray-900">{{ $familyDetail->family_name }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="font-semibold text-gray-600">Age:</p>
                            <p class="text-gray-900">{{ $familyDetail->age }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="font-semibold text-gray-600">Relationship:</p>
                            <p class="text-gray-900">{{ $familyDetail->relationship }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="font-semibold text-gray-600">Spouse Name:</p>
                            <p class="text-gray-900">{{ $familyDetail->spouse_name }}</p>
                        </div>

                        <div class="flex justify-between">
                            <p class="font-semibold text-gray-600">Children:</p>
                            <p class="text-gray-900">{{ $familyDetail->children }}</p>
                        </div>
                        <!-- Edit Button -->
                        <div class="mt-6 text-center">
                            <button id="openFamilyDetailsModalButton"
                                class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-700">
                                Edit Family Details
                            </button>
                        </div>
                    @else
                        <!-- Add Family Button -->
                        <div class="mt-6 text-center">
                            <button id="openFamilyDetailsModalButton"
                                class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-700">
                                Add Family Details
                            </button>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Expenses Card -->
            <div class="lg:w-80 p-6 h-[22rem] bg-white rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium bold text-center">Expenses</h3>

                    <!-- Download Button -->
                    <a href="{{ route('family-details.downloadExpenses', [$familyDetail->id, 'month' => request('month', date('m')), 'year' => request('year', date('Y'))]) }}"
                       class=" text-black " id="downloadButton">
                        <i class="fa-solid fa-download"></i><!-- Icon-only Button -->
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between mb-4">
                        <p><strong>Total Expenses for {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{$year}}:</strong></p>
                        <p>₹{{ number_format($totalExpenses, 2) }}</p>
                    </div>

                    <div class="max-h-36 overflow-y-auto">

                        @if($expenses->isEmpty())
                            <p>No expenses recorded for this month.</p>
                        @else
                            <ul>
                                @foreach($expenses as $expense)
                                <li class="flex justify-between items-center space-x-4">
                                    <div class="flex-1">
                                        <span>{{ $expense->expense_name }}</span>
                                    </div>
                                    <div class="w-24 text-right">
                                        <span>₹{{ number_format($expense->amount, 2) }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 edit-expense" data-id="{{ $expense->id }}" data-name="{{ $expense->expense_name }}" data-amount="{{ $expense->amount }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-red-500 delete-expense" data-id="{{ $expense->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </li>

                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Add Expense Button -->
                <div class="mt-6 text-center">
                    <button id="openModalButton" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700">
                        Add Expense
                    </button>
                </div>
            </div>

            <!-- Incomes Card -->
            <div class="lg:w-80 p-6 h-[22rem] bg-white rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium bold text-center">Incomes</h3>

                    <!-- Download Button -->
                    <a href="{{ route('family-details.downloadIncomes', [$familyDetail->id, 'month' => request('month', date('m')), 'year' => request('year', date('Y'))]) }}"
                    class=" text-black  " id="downloadButton">
                    <i class="fa-solid fa-download"></i><!-- Icon-only Button -->
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <p class=""><strong>Total Incomes for {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{$year}}:</strong></p>
                        <p class="text-gray-900">₹{{ number_format($totalIncomes, 2) }}</p>
                    </div>
                    <div class="max-h-36 overflow-y-auto">

                        @if($incomes->isEmpty())
                            <p>No incomes recorded for this month.</p>
                        @else
                            <ul>
                                @foreach($incomes as $income)
                                <li class="flex justify-between items-center space-x-4">
                                    <div class="flex-1">
                                        <span>{{ $income->source }}</span>
                                    </div>
                                    <div class="w-24 text-right">
                                        <span>₹{{ number_format($income->amount, 2) }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 edit-income" data-id="{{ $income->id }}" data-source="{{ $income->source }}" data-amount="{{ $income->amount }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-red-500 delete-income" data-id="{{ $income->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </li>

                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <button id="openIncomeModalButton"
                        class="inline-block bg-green-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-700">
                        Add Income
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Family Details Modal -->
    <div id="familyDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            @if($familyDetail)
                Edit Family Details
            @else
                Add Family Details
            @endif
        </h3>

        <!-- Form -->
        <form id="familyForm" action="{{ $familyDetail ? route('family-details.update', $familyDetail->id) : route('family-details.store') }}" method="POST">
            @csrf
            @if($familyDetail)
                @method('PUT') <!-- Use PUT for updating the resource -->
            @endif

            <!-- Family Name -->
            <div class="mb-4">
                <label for="family_name" class="block text-sm font-medium text-gray-700">Family Name</label>
                <input type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    id="family_name" name="family_name" value="{{ $familyDetail ? $familyDetail->family_name : old('family_name') }}" required>
                @error('family_name')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Age -->
            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    id="age" name="age" value="{{ $familyDetail ? $familyDetail->age : old('age') }}">
                @error('age')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Relationship -->
            <div class="mb-4">
                <label for="relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                <input type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    id="relationship" name="relationship" value="{{ $familyDetail ? $familyDetail->relationship : old('relationship') }}">
                @error('relationship')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Spouse Name -->
            <div class="mb-4">
                <label for="spouse_name" class="block text-sm font-medium text-gray-700">Spouse Name</label>
                <input type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    id="spouse_name" name="spouse_name" value="{{ $familyDetail ? $familyDetail->spouse_name : old('spouse_name') }}">
                @error('spouse_name')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- children -->
            <div class="mb-4">
                <label for="" class="block text-sm font-medium text-gray-700">Children</label>
                <input type="number"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    id="children" name="children" step="0.01" value="{{ $familyDetail ? $familyDetail->children : old('children') }}" required>
                @error('children')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-orange-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @if($familyDetail)
                    Update Family Details
                @else
                    Add Family Details
                @endif
            </button>
        </form>

        <!-- Close Modal Button -->
        <button id="closeFamilyDetailsModalButton"
            class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Close
        </button>
    </div>
    </div>
   <!-- Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-lg font-semibold text-gray-800 mb-4" id="expenseModalTitle">Add Expense</h3>

            <!-- Form -->
            <form id="expenseForm" action="{{ route('expenses.store') }}" method="POST">
                @csrf

                <input type="hidden" name="family_detail_id" value="{{ $familyDetail->id }}">
                <input type="hidden" name="month" value="{{ now()->format('m') }}">
                <input type="hidden" name="year" value="{{ now()->year }}">
                <input type="hidden" name="expense_id" id="expense_id">

                <!-- Expense Name -->
                <div class="mb-4">
                    <label for="expense_name" class="block text-sm font-medium text-gray-700">Expense Name</label>
                    <input type="text" id="expense_name" name="expense_name" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" id="expense_amount" name="amount" required step="0.01" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700">Add Expense</button>
            </form>

            <!-- Close Modal Button -->
            <button id="closeExpenseModalButton" class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700">Close</button>
        </div>
    </div>
    <!-- Incomes Modal (Initially Hidden) -->
    <div id="incomeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Add Income</h3>

            <!-- Form -->
            <form id="incomeForm" action="{{ route('incomes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="family_detail_id" value="{{ $familyDetail->id }}">
                <input type="hidden" name="month" value="{{ now()->format('m') }}">
                <input type="hidden" name="year" value="{{ now()->year }}">
                <input type="hidden" name="income_id" id="income_id">

                <!-- Source -->
                <div class="mb-4">
                    <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                    <input type="text" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="source" name="source" required>
                    @error('source')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="price" name="amount"  required>
                    @error('amount')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-700">
                    Save Income
                </button>
            </form>

            <!-- Close Modal Button -->
            <button id="closeIncomeModalButton" class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-700">
                Close
            </button>
        </div>
    </div>

    <script>
    $(document).ready(function() {
     // Open Modal for Adding Expense
$('#openModalButton').click(function() {
    $('#expenseModal').removeClass('hidden'); // Show the modal for adding expense
    $('#expenseModalTitle').text('Add Expense'); // Set the title for the modal
    $('#expenseForm').attr('action', '{{ route('expenses.store') }}'); // Set form action to store route
    $('#expense_name').val(''); // Clear input fields
    $('#expense_amount').val('');
    $('#expense_id').val('');
    $('#expenseForm').find('input[name="_method"]').remove(); // Ensure no _method field for adding
});

// Open Modal for Editing Expense
$('.edit-expense').click(function() {
    var expenseId = $(this).data('id');
    var expenseName = $(this).data('name');
    var expenseAmount = $(this).data('amount');

    $('#expenseModal').removeClass('hidden'); // Show the modal for editing
    $('#expenseModalTitle').text('Edit Expense'); // Change the title to Edit
    $('#expenseForm').attr('action', '/expenses/' + expenseId); // Set the form action to the update route
    $('#expense_name').val(expenseName); // Pre-populate the expense name
    $('#expense_amount').val(expenseAmount); // Pre-populate the amount
    $('#expenseForm').append('<input type="hidden" name="_method" value="PUT">'); // Add the hidden input for method spoofing
});

// Close Expense Modal
$('#closeExpenseModalButton').click(function() {
    $('#expenseModal').addClass('hidden'); // Hide the modal
});

// Handle Expense Form Submission (Add or Edit)
$('#expenseForm').submit(function(e) {
    e.preventDefault();
    var formAction = $(this).attr('action'); // Get the action (store or update route)
    var formMethod = $(this).find('input[name="_method"]').val() || 'POST'; // Default to POST if no method spoofing

    $.ajax({
        url: formAction,
        method: formMethod, // POST or PUT based on the action
        data: $(this).serialize(), // Serialize form data
        success: function(response) {
            $('#expenseModal').addClass('hidden'); // Hide modal on success
            $('#successMessage').text(response.message).removeClass('hidden');  // Insert message and show it

                    // Optionally, hide the success message after 5 seconds
                    setTimeout(function() {
                        $('#successMessage').addClass('hidden');
                        location.reload();// Hide the success message
                    }, 5000);

        },
        error: function(xhr) {
            console.log(xhr);
            alert('An error occurred while processing your request');
        }
    });
});

// Handle Delete Expense
$('.delete-expense').click(function() {
    var expenseId = $(this).data('id'); // Get the expense ID
    if (confirm('Are you sure you want to delete this expense?')) {
        $.ajax({
            url: '/expenses/' + expenseId, // The delete URL
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
            },
            success: function(response) {
                $('#successMessage').text(response.message).removeClass('hidden');  // Insert message and show it

                    // Optionally, hide the success message after 5 seconds
                    setTimeout(function() {
                        $('#successMessage').addClass('hidden');
                        location.reload();// Hide the success message
                    }, 5000);

            },
            error: function(xhr) {
                console.log(xhr);
                alert('An error occurred while deleting the expense');
            }
        });
    }
});


     // Open Income Modal to Add
        $('#openIncomeModalButton').click(function() {
            $('#modalTitle').text('Add Income');
            $('#source').val('');
            $('#amount').val('');
            $('#income_id').val('');
            $('#incomeModal').removeClass('hidden'); // Show the modal
        });
        // Open Income Modal to Edit
        $('.edit-income').click(function() {
            var incomeId = $(this).data('id');
            var source = $(this).data('source');
            var price = $(this).data('amount');
            $('#modalTitle').text('Edit Income');
            $('#source').val(source);
            $('#price').val(price);
            $('#income_id').val(incomeId);
            $('#incomeModal').removeClass('hidden'); // Show the modal
        });
        // Close Income Modal
        $('#closeIncomeModalButton').click(function() {
            $('#incomeModal').addClass('hidden'); // Hide the modal
        });
        // Close Modal when clicking outside the modal content
        $('#incomeModal').click(function(e) {
            if ($(e.target).is('#incomeModal')) {
                $('#incomeModal').addClass('hidden'); // Hide modal if outside is clicked
            }
        });
        // Handle form submission via AJAX (Add/Edit)
        $('#incomeForm').submit(function(e) {
            e.preventDefault();

            var incomeId = $('#income_id').val();
            var url = incomeId ? "/incomes/" + incomeId : "{{ route('incomes.store') }}"; // Determine if it's an edit or add
            var method = incomeId ? "PUT" : "POST"; // Use PUT for editing

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#incomeModal').addClass('hidden'); // Close modal
                    $('#successMessage').text(response.message).removeClass('hidden');  // Insert message and show it

                    // Optionally, hide the success message after 5 seconds
                    setTimeout(function() {
                        $('#successMessage').addClass('hidden');
                        location.reload();// Hide the success message
                    }, 5000);
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = "";
                    $.each(errors, function(key, value) {
                        errorMessages += value + "\n";
                    });
                    alert(errorMessages);  // Display validation errors
                }
            });
        });
        $('.delete-income').click(function() {
            var incomeId = $(this).data('id'); // Assuming you're passing the ID via data-id attribute

            if (confirm('Are you sure you want to delete this income?')) {
                $.ajax({
                    url: '/incomes/' + incomeId, // The route to delete the income (make sure it's correct)
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}', // Ensure you're passing the CSRF token for security
                    },
                    success: function(response) {
                        $('#successMessage').text(response.message).removeClass('hidden');  // Insert message and show it

                    // Optionally, hide the success message after 5 seconds
                    setTimeout(function() {
                        $('#successMessage').addClass('hidden');
                        location.reload();// Hide the success message
                    }, 5000);
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error: ' + error); // Log the error
                        alert('An error occurred. Please try again later.');
                    }
                });
            }
        });
        $('#openFamilyDetailsModalButton').click(function() {
        $('#familyDetailsModal').removeClass('hidden'); // Show the modal
        });
        $('#closeFamilyDetailsModalButton').click(function() {
        $('#familyDetailsModal').addClass('hidden'); // Hide the modal
        });
        // Close Modal when clicking outside the modal content
        $('#familyDetailsModal').click(function(e) {
        if ($(e.target).is('#familyDetailsModal')) {
            $('#familyDetailsModal').addClass('hidden'); // Hide modal if outside is clicked
        }
        });
        // Handle form submission for Family Details via AJAX
        $('#familyForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            var formAction = $(this).attr('action'); // The action URL is dynamic (either create or update)
            var method = formAction.includes('update') ? 'PUT' : 'POST'; // Use PUT if updating
            $.ajax({
                url: formAction,  // Dynamic URL based on whether it's an update or create
                method: method,   // Use POST for create, PUT for update
                data: $(this).serialize(),  // Serialize the form data
                success: function(response) {
                    // Close the modal after success
                    $('#familyDetailsModal').addClass('hidden');
                    $('#successMessage').text(response.message).removeClass('hidden');  // Insert message and show it

                    // Optionally, hide the success message after 5 seconds
                    setTimeout(function() {
                        $('#successMessage').addClass('hidden');
                        location.reload();// Hide the success message
                    }, 5000);

                },
                error: function(xhr) {
                    // Handle validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = "";
                    $.each(errors, function(key, value) {
                        errorMessages += value + "\n";
                    });
                    alert(errorMessages);  // Display validation errors
                }
            });
        });
        document.getElementById('downloadButton').addEventListener('click', function() {
        document.getElementById('filterForm').submit();  // Submit the form when button is clicked
        })
   });
    </script>
</x-app-layout>
