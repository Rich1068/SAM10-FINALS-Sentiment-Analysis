<x-app-layout>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <!-- Page Title -->
            <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 flex items-center justify-center space-x-3">
            <span>Sentiment Analysis History</span>
        </h1>
        <p class="text-base text-gray-300 flex items-center justify-center space-x-3">
            <i class="fas fa-info-circle"></i> <!-- Icon for Information -->
            <span>View your past sentiment analysis inputs and results.</span>
        </p>
    </div>

            <div class="container mx-auto py-8">
                <!-- Card -->
                <div class="bg-gray-800/75 shadow-md rounded-lg">
                    <!-- Card Header -->
                    <div class="px-6 py-4 bg-gray-900 border-b border-gray-700 rounded-t-xl">
                        <h2 class="text-2xl font-semibold text-gray-100 flex items-center space-x-2">
                            <i class="fas fa-history"></i> <!-- Icon for History -->
                            <span>Analysis History</span>
                        </h2>
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

            <!-- Modal -->
            <<div id="readMoreModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-gray-800 rounded-lg shadow-lg max-w-4xl w-4/5 p-8 relative">
                    <!-- Close Button -->
                    <button 
                        id="close-modal" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-300 focus:outline-none text-2xl font-semibold"
                        aria-label="Close Modal">
                        &times;
                    </button>

                    <h2 class="text-2xl font-semibold text-gray-100 mb-4">Full Text</h2>

                    <!-- Scrollable Content -->
                    <div id="modal-content" class="max-h-96 overflow-y-auto text-gray-300 whitespace-pre-line px-4 py-3 bg-gray-700 rounded-lg">
                        <p id="modal-text"></p>
                    </div>

                    <!-- Close Button at Bottom -->
                    <div class="mt-6 flex justify-end">
                        <button id="close-modal-bottom" class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:from-red-600 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                            Close
                        </button>
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
                    {
                        data: 'input_text',
                        name: 'input_text',
                        render: function (data, type, row) {
                            if (type === 'display' && data.length > 100) {
                                return `
                                    <span class="truncated-text">${data.substring(0, 100)}...</span>
                                    <button class="read-more-btn text-teal-500 hover:text-teal-400 focus:outline-none" data-full-text="${data}">
                                        Read More
                                    </button>`;
                            }
                            return data; // Return full text if under 100 characters
                        },
                    },
                    { data: 'result', name: 'result' },
                ],
                language: {
                    paginate: {
                        previous: "<span>&laquo; Previous</span>",
                        next: "<span>Next &raquo;</span>"
                    },
                    search: "Search history:",
                },
            });

            $('#historyTable').on('click', '.read-more-btn', function () {
            const fullText = $(this).data('full-text');
            $('#modal-text').text(fullText); // Set the modal text
            $('#readMoreModal').removeClass('hidden'); // Show the modal
        });

        // Close Modal Functionality
        $('#close-modal, #close-modal-bottom').on('click', function () {
            $('#readMoreModal').addClass('hidden'); // Hide the modal
        });

        // Close Modal on Outside Click
        $(document).on('click', function (e) {
            if ($(e.target).closest('#readMoreModal div').length === 0 && !$(e.target).hasClass('read-more-btn')) {
                $('#readMoreModal').addClass('hidden'); // Hide modal if click outside
            }
        });
        });
    </script>

    <!-- DataTables Custom Styling -->
    <style>
        /* Add your provided CSS styles here */
        /* General Card Styling */
        .bg-gray-800/75 {
            border-radius: 15px;
            overflow: hidden;
        }

        /* Modal Container */
        #readMoreModal .bg-gray-800 {
            max-width: 800px; /* Larger modal width */
            margin: auto;
            border-radius: 12px; /* Smooth rounded corners */
            padding: 1.5rem; /* Add padding */
        }

        /* Modal Text Content */
        #modal-content {
            max-height: 500px; /* Adjust maximum height */
            overflow-y: auto; /* Enable scrolling */
            padding: 1rem; /* Inner padding for better spacing */
            background: #374151; /* Subtle dark background */
            border: 1px solid #4b5563; /* Border for better definition */
            border-radius: 8px; /* Rounded corners */
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

        

        /* Scrollbar Styling */
        #modal-content::-webkit-scrollbar {
            width: 12px; /* Slightly larger scrollbar */
        }


        #modal-content::-webkit-scrollbar {
        width: 12px; /* Slightly larger scrollbar */
        }

        #modal-content::-webkit-scrollbar-track {
            background: #2d3748; /* Match modal background */
            border-radius: 8px; /* Rounded scrollbar track */
        }

        #modal-content::-webkit-scrollbar-thumb {
            background-color: #4b5563; /* Scrollbar color */
            border-radius: 8px; /* Rounded scrollbar thumb */
            border: 3px solid #2d3748; /* Add space around scrollbar */
        }

        #modal-content::-webkit-scrollbar-thumb:hover {
            background-color: #6b7280; /* Lighter color on hover */
        }

        /* Close Button (Top) */
        #close-modal {
            cursor: pointer;
            font-size: 1.5rem; /* Adjust size of close icon */
            color: #e5e7eb; /* Light gray */
            background: none; /* Transparent background */
            border: none; /* Remove borders */
            transition: color 0.3s ease; /* Smooth hover transition */
        }

        #close-modal:hover {
            color: #f87171; /* Red hover effect */
        }

        /* Close Button (Bottom) */
        #close-modal-bottom {
            padding: 0.75rem 1.5rem; /* Larger button */
            font-size: 1rem; /* Adjust font size */
            font-weight: bold; /* Make the text bold */
            transition: all 0.3s ease; /* Smooth hover transition */
        }

        #close-modal-bottom:hover {
            transform: translateY(-2px); /* Slight lift on hover */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
        }

        /* Pagination Styling */
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

        /* Active Pagination Button */
        .dataTables_paginate .paginate_button.current {
            background-color: #4CAF50; /* Green for active button */
            color: white;
            border-color: #4CAF50; /* Match active color with border */
        }

        /* Length Dropdown */
        #historyTable_length select {
            background-color: #2d3748; /* Dark dropdown background */
            color: #e5e7eb; /* Light text */
            border: 1px solid #4b5563;
            border-radius: 10px;
            padding: 0.5rem;
        }

        /* Table Borders */
        #historyTable th,
        #historyTable td {
            border: 1px solid #4b5563;
            padding: 1rem;
        }

         /* Align the dropdown and search bar */
        #historyTable_wrapper .dataTables_length,
        #historyTable_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        #historyTable_wrapper .dataTables_length {
            float: left; /* Align to the left */
            margin-right: 1rem;
        }

        #historyTable_wrapper .dataTables_filter {
            float: right; /* Align to the right */
        }

        /* Make both elements more visually appealing */
        #historyTable_wrapper .dataTables_length label,
        #historyTable_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Add spacing between the label and input/select */
        }

        #historyTable_length {
        display: flex;
        align-items: center;
        gap: 0.5rem; /* Add spacing between label and dropdown */
        }   

        /* Styling for the dropdown */
    #historyTable_length select {
        background-color: #1f2937; /* Darker background for dropdown */
        color: #e5e7eb; /* Light text for readability */
        border: 1px solid #4b5563; /* Subtle border */
        border-radius: 12px; /* More rounded corners for modern design */
        padding: 0.5rem 1rem; /* Add internal spacing */
        font-size: 0.9rem; /* Adjust font size */
        font-weight: 500; /* Slightly bold for emphasis */
        appearance: none; /* Remove default arrow styling */
        position: relative;
    }

    #historyTable_length select:hover {
        background-color: #2d3748; /* Slightly lighter on hover */
        border-color: #6b7280; /* Highlight border */
    }

    #historyTable_length select:focus {
        outline: none;
        border-color: #4CAF50; /* Green focus color */
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.5); /* Subtle glow on focus */
    }

    /* Dropdown arrow customization */
    #historyTable_length select::after {
        content: '\25BC'; /* Unicode character for arrow */
        font-size: 0.7rem;
        color: #e5e7eb;
        position: absolute;
        right: 1rem;
        top: 50%;   
        transform: translateY(-50%);
        pointer-events: none;
    }

    /* Label customization */
    #historyTable_length label {
        color: #e5e7eb; /* Light text color for label */
        font-size: 0.9rem; /* Match dropdown font size */
        font-weight: 400; /* Subtle emphasis */
    }

        /* Adjust search bar styling */
        #historyTable_filter input {
            width: 300px; /* Larger width for better usability */
            background-color: #2d3748; /* Match table background */
            color: #e5e7eb;
            border: 1px solid #4b5563;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        #historyTable_filter input:focus {
            outline: none;
            border-color: #4CAF50; /* Highlight on focus */
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        /* Clearfix for proper alignment */
        #historyTable_wrapper::after {
            content: '';
            display: block;
            clear: both;
        }
    </style>
</x-app-layout> 
