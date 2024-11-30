<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Sentiment\Analyzer;
use Illuminate\View\View;
use App\Models\UserHistory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

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
       
           if($output_text['neu'] > 0 && $output_text['neu'] < 0.49){
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

        //highlight words pos to green, neg to red
        $text = htmlspecialchars($request->text_to_analyze, ENT_QUOTES, 'UTF-8');
        if (!empty($output_text['pos_words'])) {
            foreach ($output_text['pos_words'] as $word) {
                $text = preg_replace(
                    "/\b" . preg_quote($word, '/') . "\b/i",
                    "<span style='color: green; font-weight: bold;'>$word</span>",
                    $text
                ) ?? $text;
            }
        }
    
        if (!empty($output_text['neg_words'])) {
            foreach ($output_text['neg_words'] as $word) {
                $text = preg_replace(
                    "/\b" . preg_quote($word, '/') . "\b/i",
                    "<span style='color: red; font-weight: bold;'>$word</span>",
                    $text
                ) ?? $text;
            }
        }
        // create history
        UserHistory::create([
            'user_id' => Auth::id(), 
            'input_text' => $request->text_to_analyze,
            'negative_score' => $output_text['neg'],
            'neutral_score' => $output_text['neu'],
            'positive_score' => $output_text['pos'],
            'result' => $mood,
        ]);
           return view('sentimental')->with([
            'mood' => $mood,
            'scores' => $output_text,
            'highlighted_text' => $text,
        ]);
    }
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:txt,pdf,docx|max:2048',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $content = '';

        // Extract text based on file type
        if ($extension === 'txt') {
            $content = file_get_contents($file->getPathname());
        } elseif ($extension === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($file->getPathname());
            $content = $pdf->getText();
        } elseif ($extension === 'docx') {
            $phpWord = IOFactory::load($file->getPathname(), 'Word2007');
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $content .= $element->getText();
                    }
                }
            }
        }

        return response()->json(['text' => $content]);
    }
    public function history(Request $request)
    {
        if ($request->ajax()) {
            //data from history table
            $data = UserHistory::where('user_id', Auth::id())->latest()->get();
            //transfer query data to datatables
            return DataTables::of($data)
                //change created_at format
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->make(true);
        }
    
        return view('history');
    }
}
