<x-app-layout>
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            
            <!-- Page Title -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
                    Laravel Sentiment Analysis POC
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Enter a sentence or phrase to analyze its sentiment.
                </p>
            </div>

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
                    @endif
                </div>
            @endif

            <!-- Footer Section -->
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Powered by Laravel Sentiment Analyzer
            </div>
        </div>
    </div>
</x-app-layout>
