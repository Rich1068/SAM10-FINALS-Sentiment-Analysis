<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Sentiment\Analyzer;
use Illuminate\View\View;

class SentimentalController extends Controller
{
    public function show(): View
    {
        return view('sentimental');
    }

    public function analyse(Request $request): View
    {
        $analyzer = new Analyzer();
        //scan the input
        $output_text = $analyzer->getSentiment($request->text_to_analyze);
           $mood = '';
           //determining the mood
           if($output_text['neg'] > 0 && $output_text['neg'] < 0.49){
               $mood = 'Somewhat Negative ';
           }
           elseif($output_text['neg'] > 0.49){
               $mood = 'Mostly Negative';
           }
       
           if($output_text['neu'] > 0 && $output_text['neg'] < 0.49){
               $mood = 'Somewhat neutral ';
           }
           elseif($output_text['neu'] > 0.49){
               $mood = 'Mostly neutral';
           }
       
           if($output_text['pos'] > 0 && $output_text['pos'] < 0.49){
               $mood = 'Somewhat positive ';
           }
           elseif($output_text['pos'] > 0.49){
               $mood = 'Mostly positive';
           }

        $text = $request->text_to_analyze;
        if (!empty($output_text['pos_words'])) {
            foreach ($output_text['pos_words'] as $word) {
                $text = preg_replace(
                    "/\b" . preg_quote($word, '/') . "\b/i",
                    "<span style='color: green; font-weight: bold;'>$word</span>",
                    $text
                ) ?? $text;
            }
        }
    
        // Highlight negative words
        if (!empty($output_text['neg_words'])) {
            foreach ($output_text['neg_words'] as $word) {
                $text = preg_replace(
                    "/\b" . preg_quote($word, '/') . "\b/i",
                    "<span style='color: red; font-weight: bold;'>$word</span>",
                    $text
                ) ?? $text;
            }
        }
           return view('sentimental')->with([
            'mood' => $mood,
            'scores' => $output_text,
            'highlighted_text' => $text,
        ]);
    }
}
