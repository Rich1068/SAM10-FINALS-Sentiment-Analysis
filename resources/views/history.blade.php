<x-app-layout>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600">
                    Sentiment Analysis History
                </h1>
                <p class="text-base text-gray-300">
                    View your past sentiment analysis inputs and results.
                </p>
            </div>

            <div class="container mx-auto py-8">
    <!-- Card -->
    <div class="bg-gray-800/75 shadow-md rounded-lg">

            <!-- Card -->
            <div class="bg-gray-800/75 backdrop-blur-sm shadow-lg rounded-xl">
                <!-- Card Header -->
                <div class="px-6 py-4 bg-gray-900 border-b border-gray-700 rounded-t-xl">
                    <h2 class="text-2xl font-semibold text-gray-100">Analysis History</h2>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="historyTable" class="min-w-full border-collapse border border-gray-700 text-gray-300 rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-700 text-left text-sm font-semibold uppercase">
                                    <th class="border border-gray-600 px-4 py-3">Date</th>
                                    <th class="border border-gray-600 px-4 py-3">Input Text</th>
                                    <th class="border border-gray-600 px-4 py-3">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be dynamically populated by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Script -->
    <script>
    $(document).ready(function () {
        // Initialize DataTables
        $('#historyTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('history') }}",
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'input_text', name: 'input_text' },
                { data: 'result', name: 'result' },
            ],
            language: {
                paginate: {
                    previous: "<span>&laquo; Previous</span>",
                    next: "<span>Next &raquo;</span>"
                },
                search: "Search history:",
            },
            scrollY: false, // Disable built-in scrolling
        });

        // Add scroll only for long tables
        const tableWrapper = document.getElementById('tableWrapper');
        const table = document.getElementById('historyTable');
        if (table.scrollHeight > 400) {
            tableWrapper.classList.add('table-container');
        }
    });
</script>

    <!-- DataTables Custom Styling -->
    <style>
        /* General Card Styling */
        .bg-gray-800/75 {
            border-radius: 15px;
            overflow: hidden;
        }

        /* DataTables Wrapper Customization */
        #historyTable_wrapper {
            color: #e5e7eb; /* Gray-300 text color */
        }

        /* DataTables Header */
        #historyTable thead th {
            background: #1f2937; /* Dark gray for header */
            color: #f3f4f6; /* Light gray text */
            padding: 1rem; /* Bigger padding for cleaner look */
            text-transform: uppercase; /* Capitalize header */
        }

        /* DataTables Body Styling */
        #historyTable tbody tr:nth-child(even) {
            background: #2d3748; /* Alternating rows */
        }

        #historyTable tbody tr:nth-child(odd) { background: #1f2937; }
        #historyTable tbody tr:hover {
        background: #374151; /* Hover effect */
    }

        /* Scrollable Table Container (conditionally applied) */
        .table-container {
                max-height: 400px; /* Set maximum height for scrollable tables */
                overflow-y: auto; /* Enable vertical scrolling */
                border: 1px solid #4b5563; /* Add a border */
                border-radius: 8px; /* Rounded corners */
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow */
         }

            


    /* Search Bar Styling */
    #historyTable_filter input {
        width: 300px; /* Bigger search bar */
        background-color: #2d3748; /* Dark background */
        color: #e5e7eb; /* Light gray text */
        border: 1px solid #4b5563; /* Border */
        border-radius: 10px; /* Rounded corners */
        padding: 0.5rem 1rem; /* Padding inside the input */
        margin-right: 1rem; /* Spacing from table controls */
    }

    #historyTable_filter input:focus {
        outline: none;
        border-color: #4CAF50; /* Green border on focus */
        box-shadow: 0 0 10px rgba(76, 175, 80, 0.5); /* Glow effect */
    }

      /* Pagination Container */
      .dataTables_paginate {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem; /* Add spacing between buttons */
        margin-top: 1rem;
    }

    /* Pagination Buttons */
    .dataTables_paginate .paginate_button {
        background-color: #2d3748; /* Dark button background */
        color: #e5e7eb; /* Light text */
        border-radius: 6px; /* Rounded corners */
        border: 1px solid #4b5563; /* Button border */
        padding: 0.5rem 0.75rem; /* Button padding */
        cursor: pointer;
        transition: all 0.3s ease; /* Smooth transition on hover */
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: #4b5563; /* Slightly lighter on hover */
        color: #ffffff; /* White text on hover */
    }

      /* Active Button */
      .dataTables_paginate .paginate_button.current {
        background-color: #4CAF50; /* Green for active button */
        color: white;
        border-color: #4CAF50; /* Match active color with border */
    }

    .dataTables_paginate .paginate_button.disabled {
        background-color: #1f2937; /* Darker background for disabled */
        color: #6b7280; /* Light gray text */
        border: none;
        cursor: not-allowed;
    }

    /* Adjust Previous/Next Buttons */
    .dataTables_paginate .paginate_button:first-child,
    .dataTables_paginate .paginate_button:last-child {
        padding: 0.5rem 1rem;
        font-weight: bold;
    }

    #historyTable_length {
        margin-bottom: 1rem; /* Add spacing below the dropdown */
    }

    /* Length Dropdown */
    #historyTable_length select {
        background-color: #2d3748; /* Dark dropdown background */
        color: #e5e7eb; /* Light text */
        border: 1px solid #4b5563;
        border-radius: 10px;
        padding: 0.5rem 2rem 0.5rem 1rem; /* Add right padding for the arrow */
        appearance: none; /* Remove default dropdown styling */
        -webkit-appearance: none;
        -moz-appearance: none;
        position: relative;
    }

    #historyTable_length select::after {
        content: '';
        position: absolute;
        right: 1rem; /* Position arrow on the right */
        top: 50%;
        transform: translateY(-50%);
        border: 5px solid transparent; /* Create a triangle */
        border-top-color: #e5e7eb; /* Arrow color */
        pointer-events: none;
    }

    #historyTable_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem; /* Add spacing between label and dropdown */
    }

    /* Table Borders */
    #historyTable th,
    #historyTable td {
        border: 1px solid #4b5563;
        padding: 1rem;
    }

    /* Prevent Overlap */
    .top {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap; /* Allow elements to wrap */
        gap: 1rem; /* Spacing between elements */
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }

    /* Rounded Corners for First and Last Cells */
    #historyTable tbody tr:first-child th:first-child,
    #historyTable tbody tr:last-child td:last-child {
        border-radius: 10px;
    }
    
</style>
</x-app-layout>
