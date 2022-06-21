<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Validator,Redirect,Response,File;
// use Spatie\PdfToText\Exceptions\CouldNotExtractText;
// use Spatie\PdfToText\Exceptions\PdfNotFound;
// use Spatie\PdfToText\Pdf;
use App\Document;
use App\Models\Policy;

class PolicyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $policy = Policy::get_all_policy();
        $faq = Policy::get_all_faqs();
        
        return view('policy', compact('policy', 'faq'));
    }

    public function upload_file(Request $request) {
        request()->validate([
            'file'  => 'required|mimes:doc,docx,pdf,txt',
        ]);

        if ($files = $request->file('file')) {
            $file = $request->file('file') ;
            $type = $file->getClientMimeType();
            $fileName = $file->getClientOriginalName();
            $destinationPath = public_path().'/uploads';
            
            //store file into public/uploads folder
            if($file->move($destinationPath, $fileName)) {
                $path = public_path('/uploads/'. $fileName);

                // Convert Pdf to text
                if($type == "application/pdf") {
                    $text = $this->pdf2text($path);
                } elseif($type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    $text = $this->docx2text($path);
                } elseif($type == "application/msword") {
                    $text = $this->doc2text($path);
                } else {
                    $text = \file_get_contents($path, true);
                }

                return Response()->json([
                    "success" => true,
                    "text" => $text
                ]);
            } else {
                return Response()->json([
                    "success" => false,
                    "msg" => 'File upload failed!'
                ]);
            }      
        }
  
        return Response()->json([
            "success" => false,
            "msg" => "Please select file to upload!"
        ]);
    }

    public function upload_policy(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::save_policy($request);

        if($result) {
            $data['msg'] = "Successfully save policy!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Save policy failed!';
        }

        return response()->json($data);
    }

    public function edit_policy(Request $request) {
        $policy = Policy::get_policy($request->pid);
        $data['policy'] = $policy[0];
        
        return response()->json($data);
    }

    public function delete_policy(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::delete_policy($request->pid);

        if($result) {
            $data['msg'] = "Successfully delete policy!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Delete policy failed!';
        }

        return response()->json($data);
    }

    public function add_faq(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::add_faq($request);

        if($result) {
            $data['msg'] = "Successfully add new faq!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'add faq failed!';
        }

        return response()->json($data);
    }

    public function edit_faq(Request $request) {
        $faq = Policy::get_faq($request->fid);
        $data['faq'] = $faq[0];
        
        return response()->json($data);
    }

    public function delete_faq(Request $request) {
        $data['status'] = 'success';
        $data['msg'] = '';

        $result = Policy::delete_faq($request->fid);

        if($result) {
            $data['msg'] = "Successfully delete faq!";
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Delete faq failed!';
        }

        return response()->json($data);
    }

    public function pdf2text($path) {
        $reader = new \Asika\Pdf2text; 
        $source = $reader->decode($path);

        // Encoding as UTF-8
        $my_encoding_list = [
            "UTF-8",
            "UTF-7",
            "UTF-16",
            "UTF-32",
            "ISO-8859-16",
            "ISO-8859-15",
            "ISO-8859-10",
            "ISO-8859-1",
            "Windows-1254",
            "Windows-1252",
            "Windows-1251",
            "ASCII",
            //add yours preferred
        ];
        
        //remove unsupported encodings
        $encoding_list = array_intersect($my_encoding_list, mb_list_encodings());
        
        //detect 'finally' the encoding
        $this->encoding = mb_detect_encoding($source, $encoding_list, true);
        
        $text = iconv($this->encoding, "UTF-8//IGNORE", $source);

        return $text;
    }

    public function docx2text($path) {
        $content = '';
        $zip = zip_open($path);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        } // end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $text = strip_tags($content);

        return $text;
    }

    public function doc2text($path) {
        $fileHandle = fopen($path, "r");
        $line = @fread($fileHandle, filesize($path));   
        $lines = explode(chr(0x0D),$line);
        $text = "";
        foreach($lines as $thisline)
        {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
            {
            } else {
                $text .= $thisline." ";
            }
        }
        $text = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$text);

        return $text;
    }
}