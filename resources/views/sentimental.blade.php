<x-app-layout>
    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600">
                    Sentiment Analysis POC
                </h1>
                <p class="text-base text-gray-300">
                    Analyze text sentiment by entering a sentence or uploading a file.
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-6">
                <nav class="flex justify-center space-x-6 border-b border-gray-700 pb-2" role="tablist">
                    <button 
                        id="text-tab"
                        class="tab-btn px-6 py-2 text-lg font-semibold text-gray-300 bg-gradient-to-br from-transparent to-transparent border-b-4 border-transparent hover:border-teal-500 focus:border-teal-500 focus:outline-none"
                        data-tab="text-analysis">
                        Text Analysis
                    </button>
                    <button 
                        id="file-tab"
                        class="tab-btn px-6 py-2 text-lg font-semibold text-gray-300 bg-gradient-to-br from-transparent to-transparent border-b-4 border-transparent hover:border-teal-500 focus:border-teal-500 focus:outline-none"
                        data-tab="file-upload">
                        Upload File
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Text Analysis Tab -->
                <div id="text-analysis" class="tab-content">
                    <div class="bg-gray-800/75 backdrop-blur-sm shadow-lg rounded-lg p-8">
                        <form action="{{ route('analyse.submit') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="text_to_analyze" class="block text-lg font-medium text-gray-300">
                                    Text to Analyze:
                                </label>
                                <input 
                                    type="text" 
                                    name="text_to_analyze" 
                                    id="text_to_analyze" 
                                    class="mt-3 block w-full px-5 py-3 text-gray-100 bg-gray-900 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="Enter text here..." 
                                    required>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    class="px-6 py-3 text-white font-semibold bg-gradient-to-r from-teal-400 to-blue-500 rounded-lg hover:from-teal-500 hover:to-blue-600 focus:outline-none focus:ring-4 focus:ring-teal-500">
                                    Analyze
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- File Upload Tab -->
                <div id="file-upload" class="tab-content hidden">
                    <div class="bg-gray-800/75 backdrop-blur-sm shadow-lg rounded-lg p-8">
                        <form action="{{ route('analyse.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label for="file_to_upload" class="block text-lg font-medium text-gray-300">
                                    Upload File:
                                </label>
                                <input 
                                    type="file" 
                                    name="file" 
                                    id="file_to_upload" 
                                    class="mt-3 block w-full px-5 py-3 text-gray-100 bg-gray-900 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                    required>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    class="px-6 py-3 text-white font-semibold bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg hover:from-purple-500 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-purple-500">
                                    Upload & Analyze
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            @if(isset($mood))
                <div class="mt-10 bg-gray-800/75 backdrop-blur-sm p-8 shadow-lg rounded-lg">
                    <h2 class="text-2xl font-semibold text-gray-100 mb-6">
                        Sentiment Analysis Result
                    </h2>
                    <p class="text-lg text-gray-300">
                        <strong>Your Mood:</strong> <span class="font-bold text-teal-400">{{ $mood }}</span>
                    </p>
                    @if(isset($highlighted_text))
                        <p class="mt-6 text-lg text-gray-300">
                            <strong>Analyzed Text:</strong> 
                            <span class="font-mono">{!! $highlighted_text !!}</span>
                        </p>
                        <div class="mt-8">
                            <canvas id="sentimentChart" class="w-full h-80"></canvas>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Footer Section -->
            <div class="mt-10 text-center text-sm text-gray-400">
                Powered by Laravel Sentiment Analyzer
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
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
const ctx = document.getElementById('sentimentChart').getContext('2d');

// Create gradient colors
const gradientPositive = ctx.createLinearGradient(0, 0, 0, 300);
gradientPositive.addColorStop(0, '#4CAF50');
gradientPositive.addColorStop(1, '#2E7D32');

const gradientNeutral = ctx.createLinearGradient(0, 0, 0, 300);
gradientNeutral.addColorStop(0, '#FFC107');
gradientNeutral.addColorStop(1, '#FFB300');

const gradientNegative = ctx.createLinearGradient(0, 0, 0, 300);
gradientNegative.addColorStop(0, '#F44336');
gradientNegative.addColorStop(1, '#D32F2F');

const sentimentData = {
    labels: ['Positive', 'Neutral', 'Negative'],
    datasets: [{
        label: 'Sentiment Distribution',
        data: [{{ $percentages['positive'] }}, {{ $percentages['neutral'] }}, {{ $percentages['negative'] }}],
        backgroundColor: [gradientPositive, gradientNeutral, gradientNegative],
        hoverBackgroundColor: [gradientPositive, gradientNeutral, gradientNegative], // Keep gradient on hover
        borderColor: '#2a2a2a',
        borderWidth: 2,
        borderRadius: 15, // Rounded corners for segments
        hoverOffset: 15
    }]
};

const config = {
    type: 'doughnut',
    data: sentimentData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // Hide the default legend
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const value = tooltipItem.raw;
                        const total = sentimentData.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                },
                backgroundColor: '#2a2a2a',
                titleFont: { size: 16, weight: 'bold' },
                bodyFont: { size: 14 },
                footerFont: { size: 12 },
                borderWidth: 1,
                borderColor: '#444'
            }
        },
        cutout: '70%', // Doughnut inner cutout
        layout: {
            padding: 20
        },
        plugins: {
            // Add labels inside the chart
            datalabels: {
                color: '#ffffff',
                font: {
                    size: 14,
                    weight: 'bold'
                },
                formatter: (value, ctx) => {
                    const label = ctx.chart.data.labels[ctx.dataIndex];
                    return `${label}`;
                }
            }
        }
    }
};

// Render chart
new Chart(ctx, config);
@endif
    
        // Set default active tab
        document.getElementById('text-tab').click();
    </script>
</x-app-layout>
