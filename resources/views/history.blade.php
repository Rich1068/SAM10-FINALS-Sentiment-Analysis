<x-app-layout>
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<div class="container mx-auto py-8">
        <!-- Card -->
        <div class="bg-white shadow-md rounded-lg">
            <!-- Card Header -->
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700">Sentiment Analysis History</h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <table id="historyTable" class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Input Text</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be dynamically populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- DataTables Script -->
    <script>
        $(document).ready(function () {
            $('#historyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('history') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'input_text', name: 'input_text' },
                    { data: 'result', name: 'result' },
                ]
            });
        });
    </script>
</x-app-layout>