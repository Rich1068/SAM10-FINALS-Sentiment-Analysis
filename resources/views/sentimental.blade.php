<x-app-layout>
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            
            <!-- Page Title -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
                    Laravel Sentiment Analysis POC
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Analyze text sentiment by entering a sentence or uploading a file.
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-300 dark:border-gray-600">
                <nav class="flex justify-center space-x-4" role="tablist">
                    <button 
                        id="text-tab"
                        class="tab-btn px-4 py-2 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-teal-500 focus:border-teal-500 focus:outline-none"
                        data-tab="text-analysis">
                        Text Analysis
                    </button>
                    <button 
                        id="file-tab"
                        class="tab-btn px-4 py-2 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-teal-500 focus:border-teal-500 focus:outline-none"
                        data-tab="file-upload">
                        Upload File
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Text Analysis Tab -->
                <div id="text-analysis" class="tab-content">
                    <!-- Form Section -->
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <form action="{{ route('analyse.submit') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Input Field -->
                            <div>
                                <label for="text_to_analyze" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Text to Analyze:
                                </label>
                                <input 
                                    type="text" 
                                    name="text_to_analyze" 
                                    id="text_to_analyze" 
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 text-gray-900 dark:text-gray-200 dark:bg-gray-700"
                                    placeholder="Enter text here..." 
                                    required>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 bg-teal-500 text-white font-semibold text-sm rounded-md hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    Analyze
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- File Upload Tab -->
                <div id="file-upload" class="tab-content hidden">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <form action="{{ route('analyse.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- File Input -->
                            <div>
                                <label for="file_to_upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Upload File:
                                </label>
                                <input 
                                    type="file" 
                                    name="file" 
                                    id="file_to_upload" 
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 text-gray-900 dark:text-gray-200 dark:bg-gray-700"
                                    required>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 bg-teal-500 text-white font-semibold text-sm rounded-md hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    Upload & Analyze
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            @if(isset($mood))
                <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-6 shadow sm:rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                        Sentiment Analysis Result
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        <strong>Your Mood:</strong> <span class="font-bold">{{ $mood }}</span>
                    </p>
                    @if(isset($highlighted_text))
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            <strong>Analyzed Text:</strong> 
                            <span class="font-mono text-sm">{!! $highlighted_text !!}</span>
                        </p>
                        <canvas id="sentimentChart" width="400" height="400"></canvas>
                    @endif
                </div>
            @endif

            <!-- Footer Section -->
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Powered by Laravel Sentiment Analyzer
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <!-- JavaScript for Tab Navigation -->
    <script>
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active state from all tabs
                document.querySelectorAll('.tab-btn').forEach(tab => {
                    tab.classList.remove('border-teal-500', 'text-teal-500');
                });

                // Show active tab content and set active state
                const target = button.getAttribute('data-tab');
                document.getElementById(target).classList.remove('hidden');
                button.classList.add('border-teal-500', 'text-teal-500');
            });
        });
        @if(isset($mood))
        const sentimentData = {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                label: 'Sentiment Distribution',
                data: [{{ $percentages['positive'] }}, {{ $percentages['neutral'] }}, {{ $percentages['negative'] }}],
                backgroundColor: ['#4CAF50', '#FFC107', '#F44336'], // Colors for the chart
                hoverOffset: 4
            }]
        };

        // Configure chart
        const config = {
            type: 'doughnut',
            data: sentimentData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                            }
                        }
                    }
                }
            }
        };

        // Render chart
        const ctx = document.getElementById('sentimentChart').getContext('2d');
        new Chart(ctx, config);
        @endif
        // Set default active tab
        document.getElementById('text-tab').click();
    </script>
</x-app-layout>
