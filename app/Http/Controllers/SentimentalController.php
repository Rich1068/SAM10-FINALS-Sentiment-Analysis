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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class SentimentalController extends Controller
{
    public function show(): View
    {
        return view('sentimental');
    }

    public function analyse(Request $request): View
    {
        $content = '';
        //if file upload
        if ($request->hasFile('file')) {
            //validation of request
            $request->validate([
                'file' => 'required|mimes:txt,pdf,docx|max:2048',
            ]);
    
            $file = $request->file('file');
            //get file type
            $extension = $file->getClientOriginalExtension();
            //if txt file
            if ($extension === 'txt') {
                //get the content of the file
                $content = file_get_contents($file->getPathname());
            } elseif ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($file->getPathname());
                $content = $pdf->getText();
            } elseif ($extension === 'docx') {
                //load the file to an array
                $phpWord = IOFactory::load($file->getPathname(), 'Word2007');
                //get section part from the array
                foreach ($phpWord->getSections() as $section) {
                    //get elements inside the section
                    foreach ($section->getElements() as $element) {
                        //will use the getText() method to the elements that is applicable to the method
                        if (method_exists($element, 'getText')) {
                            $content .= $element->getText() . "\n";
                        }
                    }
                }
            }
            //replace special spaces to normal space
            $content = str_replace("\u{A0}", ' ', $content);
            $content = trim($content);

            $fileName = Str::uuid() . '_' . $file->getClientOriginalName();
            // Save the file to Azure Blob Storage
            $filePath = Storage::disk('azure')->putFileAs('uploads', $file, $fileName);
        }
        //if text is analyzed
        elseif ($request->has('text_to_analyze')) {
            // Handle raw text input
            $request->validate([
                'text_to_analyze' => 'required|string',
            ]);
            $content = $request->input('text_to_analyze');
        } 

          // Calculate word count
         $word_count = str_word_count($content);


        $analyzer = new Analyzer();
        //scan the input
        $output_text = $analyzer->getSentiment($content);
           $mood = '';
           //determining the mood
           if ($output_text['neg'] > 0 && $output_text['neg'] < 0.49) {
            $mood = 'Somewhat Negative';
        } elseif ($output_text['neg'] > 0.49) {
            $mood = 'Mostly Negative';
        }
    
        if ($output_text['neu'] > 0 && $output_text['neu'] < 0.49) {
            $mood = 'Somewhat Neutral';
        } elseif ($output_text['neu'] > 0.49) {
            $mood = 'Mostly Neutral';
        }
    
        if ($output_text['pos'] > 0 && $output_text['pos'] < 0.49) {
            $mood = 'Somewhat Positive';
        } elseif ($output_text['pos'] > 0.49) {
            $mood = 'Mostly Positive';
        }

        //highlight words pos to green, neg to red
        $text = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
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
        $total = $output_text['pos'] + $output_text['neu'] + $output_text['neg'];
        $percentages = [
            'positive' => round(($output_text['pos'] / $total) * 100, 2),
            'neutral' => round(($output_text['neu'] / $total) * 100, 2),
            'negative' => round(($output_text['neg'] / $total) * 100, 2),
        ];
        // create history
        UserHistory::create([
            'user_id' => Auth::id(), 
            'input_text' => $content,
            'file_path' => $filePath,
            'negative_score' => $output_text['neg'],
            'neutral_score' => $output_text['neu'],
            'positive_score' => $output_text['pos'],
            'result' => $mood,
        ]);
           return view('sentimental')->with([
            'mood' => $mood,
            'scores' => $output_text,
            'highlighted_text' => $text,
            'percentages' => $percentages,
            'word_count' => $word_count
        ]);
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
