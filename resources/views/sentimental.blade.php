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
                            <div class="relative">
                                <!-- Textarea with scroll -->
                                <textarea 
                                    name="text_to_analyze" 
                                    id="text_to_analyze" 
                                    class="mt-3 block w-full px-5 py-3 text-gray-100 bg-gray-900 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 resize-none overflow-y-auto"
                                    placeholder="Enter your text here..."
                                    rows="3"></textarea>
                            </div>
                            <!-- Word Count -->
                            <div class="mt-2 text-sm text-gray-400">
                                <span id="word-count">Word Count: 0</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button 
                                id="analyze-button"
                                type="submit" 
                                class="px-6 py-3 text-white font-semibold bg-gray-500 rounded-lg cursor-not-allowed focus:outline-none focus:ring-4 focus:ring-gray-400"
                                disabled>
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
                <h2 class="text-3xl font-bold text-gray-100 mb-6 text-center">
            Sentiment Analysis Result
        </h2>
                            <p class="text-2xl text-gray-300 mb-4 text-center">
                        <strong>Your Mood:</strong> 
                        <span class="font-bold" 
                            style="
                                color:
                                    @if($mood === 'Somewhat Negative') #FF7043; /* Orange */
                                    @elseif($mood === 'Mostly Negative') #F44336; /* Red */
                                    @elseif($mood === 'Somewhat Neutral') #FFEB3B; /* Light Yellow */
                                    @elseif($mood === 'Mostly Neutral') #FFC107; /* Yellow */
                                    @elseif($mood === 'Somewhat Positive') #8BC34A; /* Light Green */
                                    @elseif($mood === 'Mostly Positive') #4CAF50; /* Green */
                                    @else #FFFFFF; /* Default (White) */
                                @endif
                            ">
                            {{ $mood }}
                        </span>
                    </p>

                                <!-- Dynamic Segmented Gradient Bar -->
                    <div class="mt-4 relative w-full h-8 bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                        <!-- Positive Section -->
                        <div 
                            class="absolute top-0 left-0 h-full flex items-center justify-center text-sm font-semibold text-gray-100"
                            style="width: calc({{ $percentages['positive'] }}%); background: linear-gradient(to right, #4CAF50, #388E3C);">
                            {{ number_format($percentages['positive'], 1) }}%
                        </div>
                        <!-- Neutral Section -->
                        <div 
                            class="absolute top-0 h-full flex items-center justify-center text-sm font-semibold text-gray-800"
                            style="left: calc({{ $percentages['positive'] }}%); width: calc({{ $percentages['neutral'] }}%); background: linear-gradient(to right, #FFEB3B, #FFC107);">
                            {{ number_format($percentages['neutral'], 1) }}%
                        </div>
                        <!-- Negative Section -->
                        <div 
                            class="absolute top-0 h-full flex items-center justify-center text-sm font-semibold text-gray-100"
                            style="left: calc({{ $percentages['positive'] + $percentages['neutral'] }}%); width: calc({{ $percentages['negative'] }}%); background: linear-gradient(to right, #F44336, #D32F2F);">
                            {{ number_format($percentages['negative'], 1) }}%
                        </div>
                    </div>


                    <!-- Tooltip for Hover -->
                    <div id="hover-percentage" class="absolute bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg hidden"></div>

                                        <!-- Legend -->
                    <div class="mt-4 flex justify-between text-sm text-gray-400">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gradient-to-r from-green-400 to-green-600 rounded-full mr-2"></div>
                            <span>Positive</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full mr-2"></div>
                            <span>Neutral</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gradient-to-r from-red-400 to-red-600 rounded-full mr-2"></div>
                            <span>Negative</span>
                        </div>
                    </div>

                    <!-- Analyzed Text -->
                    @if(isset($highlighted_text))   
                    <p class="mt-6 text-lg text-gray-300 analyzed-text">
                        <strong>Analyzed Text:</strong><br>
                        <span class="font-mono">{!! $highlighted_text !!}</span>
                    </p>
                          <!-- Word Count -->
                        @if(isset($word_count))
                                <div class="mt-4 text-sm text-gray-400 text-left">
                                    <strong>Word Count:</strong> {{ $word_count }}
                                </div>
                            @endif
                    @endif
                </div>
            @endif


            <!-- Footer Section -->
            <div class="mt-10 text-center text-sm text-gray-400">
                Powered by Laravel Sentiment Analyzer
            </div>
        </div>
    </div>
    
    <script>
            document.addEventListener('DOMContentLoaded', () => {
                const textarea = document.getElementById('text_to_analyze');
                const wordCountDisplay = document.getElementById('word-count');

                textarea.addEventListener('input', () => {
                    // Update word count
                    const text = textarea.value.trim();
                    const wordCount = text ? text.split(/\s+/).length : 0; // Count words
                    wordCountDisplay.innerText = `Word Count: ${wordCount}`; // Update word count display
                });
            });
    </script>

    <script>
        // Select the textarea/input and button
        const inputField = document.getElementById('text_to_analyze'); // Make sure this ID matches your input's ID
        const analyzeButton = document.getElementById('analyze-button');

        // Add event listener to monitor input changes
        inputField.addEventListener('input', () => {
            if (inputField.value.trim() !== '') {
                // Enable the button if input is not empty
                analyzeButton.disabled = false;
                analyzeButton.classList.remove('bg-gray-500', 'cursor-not-allowed', 'focus:ring-gray-400');
                analyzeButton.classList.add('bg-gradient-to-r', 'from-teal-400', 'to-blue-500', 'hover:from-teal-500', 'hover:to-blue-600', 'focus:ring-teal-500');
            } else {
                // Disable the button if input is empty
                analyzeButton.disabled = true;
                analyzeButton.classList.remove('bg-gradient-to-r', 'from-teal-400', 'to-blue-500', 'hover:from-teal-500', 'hover:to-blue-600', 'focus:ring-teal-500');
                analyzeButton.classList.add('bg-gray-500', 'cursor-not-allowed', 'focus:ring-gray-400');
            }
        });
    </script>


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
    // Calculate the percentage widths dynamically
    const total = {{ $percentages['positive'] }} + {{ $percentages['neutral'] }} + {{ $percentages['negative'] }};
    const positiveWidth = ({{ $percentages['positive'] }} / total) * 100;
    const neutralWidth = ({{ $percentages['neutral'] }} / total) * 100;
    const negativeWidth = ({{ $percentages['negative'] }} / total) * 100;

    // Get the gradient bar and hover tooltip
    const gradientBar = document.querySelector('.relative.w-full.h-8'); // The segmented bar
    const hoverPercentage = document.getElementById('hover-percentage'); // Tooltip to show percentages

    gradientBar.addEventListener('mousemove', (e) => {
        const rect = gradientBar.getBoundingClientRect(); // Get bar position
        const x = e.clientX - rect.left; // Mouse position relative to the bar
        const totalWidth = rect.width; // Total width of the bar

        let tooltipText = ''; // Initialize tooltip text

        // Check which section the mouse is over and update tooltip text
        if (x < (totalWidth * positiveWidth) / 100) {
            tooltipText = `Positive: ${positiveWidth.toFixed(1)}%`;
        } else if (x < (totalWidth * (positiveWidth + neutralWidth)) / 100) {
            tooltipText = `Neutral: ${neutralWidth.toFixed(1)}%`;
        } else {
            tooltipText = `Negative: ${negativeWidth.toFixed(1)}%`;
        }

        // Display the tooltip and position it near the mouse
        hoverPercentage.innerText = tooltipText;
        hoverPercentage.style.display = 'block';
        hoverPercentage.style.left = `${e.clientX}px`; // Tooltip follows the mouse horizontally
        hoverPercentage.style.top = `${rect.top - 25}px`; // Tooltip appears above the bar
    });

    gradientBar.addEventListener('mouseleave', () => {
        hoverPercentage.style.display = 'none'; // Hide the tooltip when mouse leaves the bar
    });
@endif

    </script>
</x-app-layout>
