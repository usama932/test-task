<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use File;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::pluck('value','name')->all();
        $all_columns =array(
            array(
                'name'=>'site_title',
                'id'=>'site_title',
                'type'=>'text',
                'label'=>'Site Title',
                'place_holder'=>'Enter Site Title',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px'
            ),
            array(
                'name'=>'meta_keywords',
                'id'=>'meta_keywords',
                'type'=>'textarea',
                'label'=>'Meta Keywords',
                'place_holder'=>'Enter Site Meta Keywords separated by common limit 9 words',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px',
                'rows'=>'2',
            ),
            array(
                'name'=>'meta_desc',
                'id'=>'meta_desc',
                'type'=>'textarea',
                'label'=>'Meta Description',
                'place_holder'=>'Enter Site Meta Description limit 39 charaters',
                'class'=>'form-control form-control-solid',
                'style'=>'width:30px;max-width:100%;margin-top:12px',
                'rows'=>'2',
            ),
            array(
                'name' => 'favicon',
                'id' => 'favicon',
                'type' => 'file',
                'label' => 'Favicon (Png)',
                'class' => 'custom-file-input',
                'style'=>'width:60px;max-width:100%;padding:12px;margin-bottom:10px'
            ),
            array(
                'name'=>'favicon',
                'type'=>'hidden',
                'value'=>'',

            ),
            array(
                'name'=>'logo',
                'id'=>'logo',
                'type'=>'file',
                'label'=>'Logo',
                'class'=>'custom-file-input',
                'style'=>'width:80px;max-width:100%;padding:12px;margin-bottom:10px'
            ),
            array(
                'name'=>'logo',
                'type'=>'hidden',
                'value'=>'',

            ),

            array(
                'name'=>'auth_page_heading',
                'id'=>'auth_page_heading',
                'type'=>'text',
                'label'=>'Auth Page Heading',
                'place_holder'=>'',
                'class'=>'form-control form-control-solid',
            ),
	
	        array(
		        'name'=>'auth_logo',
		        'id'=>'auth_logo',
		        'type'=>'file',
		        'label'=>'Auth Page Logo',
		        'class'=>'custom-file-input',
		        'style'=>'width:80px;max-width:100%;padding:12px;margin-bottom:10px'
	        ),
	        array(
		        'name'=>'auth_logo',
		        'type'=>'hidden',
		        'value'=>'',
	
	        ),
            array(
                'name'=>'auth_image',
                'id'=>'auth_image',
                'type'=>'file',
                'label'=>'Auth Page Image',
                'class'=>'custom-file-input',
                'style'=>'width:80px;max-width:100%;padding:12px;margin-bottom:10px'
            ),
            array(
                'name'=>'auth_image',
                'type'=>'hidden',
                'value'=>'',

            ),

            array(
                'name'=>'copy_right',
                'id'=>'copy_right',
                'type'=>'text',
                'label'=>'Copyright Text',
                'place_holder'=>'Enter Copyright text',
                'class'=>'form-control form-control-solid',
            ),
            array(
                'name'=>'email',
                'id'=>'email',
                'type'=>'email',
                'label'=>'Email',
                'place_holder'=>'Enter Booking Receiving Email',
                'class'=>'form-control form-control-solid',
            ),
            // array(
            //     'name'=>'test_secret_key',
            //     'id'=>'test_secret_key',
            //     'type'=>'text',
            //     'label'=>'Test Secret Key',
            //     'place_holder'=>'Test Secret Key',
            //     'class'=>'form-control form-control-solid',
            //     'style'=>'width:30px;max-width:100%;margin-top:12px'
            // ),
            // array(
            //     'name'=>'test_publish_key',
            //     'id'=>'test_publish_key',
            //     'type'=>'text',
            //     'label'=>'Test Publish Key',
            //     'place_holder'=>'Test Publish Key',
            //     'class'=>'form-control form-control-solid',
            //     'style'=>'width:30px;max-width:100%;margin-top:12px'
            // ),
            // array(
            //     'name'=>'server_secret_key',
            //     'id'=>'server_secret_key',
            //     'type'=>'text',
            //     'label'=>'Server Secret Key',
            //     'place_holder'=>'Server Secret Key',
            //     'class'=>'form-control form-control-solid',
            //     'style'=>'width:30px;max-width:100%;margin-top:12px'
            // ),
            // array(
            //     'name'=>'server_publish_key',
            //     'id'=>'server_publish_key',
            //     'type'=>'text',
            //     'label'=>'Server Publish Key',
            //     'place_holder'=>'Server Publish Key',
            //     'class'=>'form-control form-control-solid',
            //     'style'=>'width:30px;max-width:100%;margin-top:12px'
            // ),
            // array(
            //     'name'=>'tested_mode',
            //     'id'=>'tested_mode',
            //     'type'=>'checkbox',
            //     'label'=>'Tested Mode',
            //     'place_holder'=>' Tested Mode',
            //     'value'=> '0',
            //     'class'=>'form-control form-control-solid',
            //     'style'=>'width:30px;max-width:100%;margin-top:12px'
            // ),
        );
        return view('admin.settings.index', ['title' => 'Site Setting','settings'=>$settings,
            'all_columns'=>$all_columns]);
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
        $input = $request->all();
        $settings = Setting::pluck('value', 'name')->all();

        $data = array();
        if(!empty($request->file())){

            foreach($request->file() as $name=>$file_data){


                if ($request->hasFile($name)) {

                    if ($request->file($name)->isValid()) {

                        $this->validate($request, [
                            $name => 'required|image|mimes:jpeg,png,jpg,svg'
                        ]);

                        $file = $request->file($name);

                        $fileName = $file->getClientOriginalName();

                        $newFileName = rand().$fileName;

                        $destinationPath = public_path('/uploads/');

                        $request->file($name)->move($destinationPath,$newFileName);

                        if (isset($settings[$name])) {
                            if (file_exists(public_path('/uploads/'.$settings[$name]))) {

                                $delete_old_file = public_path('/uploads/'.$settings[$name]);

                                File::delete($delete_old_file);
                            }
                        }


                        $data[$name] = $newFileName;

                    }


                }


            }

        }

        DB::table('settings')->truncate();

        unset($input['_token']);

        unset($input['_method']);

        if(count($data)>0){
            $input = array_merge($input,$data);
        }
        foreach ($input as $key => $value) {
            $setting = new Setting();

            $setting->name = $key;

            $setting->value = $value;

            $setting->save();

        }
        Session::flash('success_message', 'Settings saved successfully!');

        return redirect()->back();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
