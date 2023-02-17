<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::pluck('name', 'id')->toArray();
        return view('admin.sections.index', ['sections' => $sections]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        switch ($section->id) {
            case 1:
                return $this->sectionOne($section->id);
                break;
            case 2:
                return $this->sectionTwo($section->id);
                break;
            case 3:
                return $this->sectionThree($section->id);
                break;
            case 4:
                return $this->sectionFour($section->id);
                break;
            case 5:
                return $this->sectionfive($section->id);
                break;
        }
    }

    public function sectionOne($id)
    {

        $data_file = public_path('data/Aboutus.json');

        $content = File::get($data_file);
        $columns = json_decode($content, TRUE);

        $section = Section::findOrFail($id);
        $storedColumns = json_decode($section->content, TRUE);
        return view('admin.sections.edit', ['section' => $section, 'all_columns' => $columns, 'storedColumns' => $storedColumns, 'title' => "Edit Section"]);
    }




    public function update(Request $request, Section $section)
    {
     
        $storedColumns = json_decode($section->content, TRUE);
        //        dd($storedColumns);
        $data = array();

        if (!empty($request->file())) {

            foreach ($request->file() as $name => $file_data) {


                if ($request->hasFile($name)) {

                    if ($request->file($name)->isValid()) {

                        $this->validate($request, [
                            $name => 'required|image|mimes:jpeg,png,jpg,svg,pdf'
                        ]);

                        $file = $request->file($name);

                        $fileName = $file->getClientOriginalName();

                        $newFileName = rand() . $fileName;

                        $destinationPath = public_path('/uploads/');

                        $request->file($name)->move($destinationPath, $newFileName);

                        if (isset($storedColumns[$name])) {
                            if (file_exists(public_path('/uploads/' . $storedColumns[$name]))) {

                                $delete_old_file = public_path('/uploads/' . $storedColumns[$name]);

                                File::delete($delete_old_file);
                            }
                        }


                        $data[$name] = $newFileName;
                    }
                }
            }
        }

        $input = $request->all();


        unset($input['_token']);

        unset($input['_method']);
        if (count($data) > 0) {
            $input = array_merge($input, $data);
        }


        $section->content =  json_encode($input);

        $section->save();


        Session::flash('success_message', 'Great! Section has been saved successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
