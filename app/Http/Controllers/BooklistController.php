<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\BookList;
use League\Csv\XMLConverter;
use League\Csv\Reader;

class BooklistController extends Controller
{
    private function createQuery(Request $request)
    {
        $data = BookList::query();
        
        if ($request->get('search')) 
        {
            $data = $data->where("book_title", "LIKE", "%{$request->get('search')}%")
                    ->orWhere("book_author", "LIKE", "%{$request->get('search')}%");
        }
        
        $sort = 'asc';
        if (in_array($request->get('sort'), ['asc', 'desc'])) 
        {
            $sort = $request->get('sort');
        }
        if ($request->get('order')) 
        {
            $data = $data->orderBy($request->get('order'), $sort);
        } 
        
        return $data;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->createQuery($request)->paginate(5);        
        return response($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_title' => 'required',
            'book_author' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }  
        
        $input = $request->all();
        $create = BookList::create($input);
        return response($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = BookList::find($id);
        return response($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'book_title' => 'required',
            'book_author' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $input = $request->all();
        BookList::where("id", $id)->update($input);
        $data = BookList::find($id);
        return response($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return BookList::where('id', $id)->delete();
    }
    
    private function exportChoiceHelper($choice)
    {
        switch ($choice) {
            case 0:
                $arr = ['book_title', 'book_author'];
                break;
            case 1:
                $arr = ['book_title'];
                break;
            case 2:
                $arr = ['book_author'];
                break;
            default:
                $arr = ['book_title', 'book_author'];
        }
        
        return $arr;
    }
    
    /**
     * Export the specified resource in storage to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $choice
     * @return \Illuminate\Http\Response
     */
    public function export_csv(Request $request, $choice)
    {
        $data = $this->createQuery($request)->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($data, $this->exportChoiceHelper($choice))->download();
    }
    
    /**
     * Export the specified resource in storage to XML.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $choice
     * @return \Illuminate\Http\Response
     */
    public function export_xml(Request $request, $choice)
    {
        $data = $this->createQuery($request)->get();
        $csvExporter = new \Laracsv\Export();        
        $csv = $csvExporter->build($data, $this->exportChoiceHelper($choice))->getCsv();        
        
        $reader = Reader::createFromString($csv);
        $reader->setHeaderOffset(0);
        $converter = (new XMLConverter())
            ->rootElement('root')
            ->recordElement('record', 'no')
            ->fieldElement('field', 'name');

        $dom = $converter->convert($reader);
        $dom->formatOutput = true;
        $dom->encoding = 'utf-8';

        return $dom->saveXML();
    }
}
