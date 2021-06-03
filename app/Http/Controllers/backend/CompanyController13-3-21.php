<?php

namespace App\Http\Controllers\backend;

use App\Models\Role;
use App\Models\RoleModule;
use App\Models\UserRole;
use App\Models\Company;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect, api, DB, Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Nicolaslopezj\Searchable\SearchableTrait;
use Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $users = User::join('companies', 'users.company_id', '=', 'companies.id')->where('users.type', '=' , 'company')->get();
        //dd($users);
        return view('backend.company.companylist',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $module = DB::table('company_modules')->orderBy('name', 'asc')->get();
        foreach ($module as $key => $mod) {
           $module_name[]=$mod->name;
           $module_id[]=$mod->id;

        }
       // dd($module_name,$module_id);
       return view('backend.company.create',compact('module_name','module_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input=\Request::all();
       // dd($input);
        $this->validate($request, [
            'company_name' => 'required',
            'company_email' => 'required',
            'company_mobile' => 'required',
            'company_address' => 'required',
            'company_city' => 'required',
            'company_image' => 'required',
            'userplan' => 'required',
            'Categorys' => 'required',
            'companystatus' => 'required',
           
        ]);

       
        
        $check = Company::where('email', $request->company_email)->where('contact_no', $request->company_mobile)->first();

        if(!$check)
        {

            $file = $request->company_image;
            $extension = $file->getClientOriginalExtension();
            $originalName= $file->getClientOriginalName();
            $filename = substr(str_shuffle(sha1(rand(3,300).time())), 0, 10) . "." . $extension;
            $file = \Image::make($file);
            $success = $file->resize(350,null, function ($constraint)
            {
                $constraint->aspectRatio();

            })->save('company/' . $filename);

            if($success)
            {
                $id = Company::insertGetId([
                    'company_name' => $request->company_name,
                    'email' => $request->company_email,
                    'contact_no' => $request->company_mobile,
                    'address' => $request->company_address,
                    'city' => $request->company_city,
                    'plan' => $request->userplan,
                    'category'=>$request->Categorys,
                    'status' => $request->companystatus,
                    'image' => 'company/'.$filename,
                    'created_at' => date('Y-m-d H:i:s'),
                    
                ]);
                $uname = '';
               // $digit = mt_rand(10, 99);
                $digit = date('Y');
                $length = strlen($id);
                if($length>1)
                {
                    $uname = $digit.$id.'Shop';
                }
                else
                {
                    $uname = $digit.'0'.$id.'Shop';
                }

                

                $user_id = User::insertGetId([
                    'type' => 'company',
                    'company_id' => $id,
                    'name' => $request->company_name,
                    'username' => $uname,
                    'email' => $request->company_email,
                    'password' => \Hash::make($request->company_mobile),
                    'hint_password' => $request->company_mobile,
                    'status' =>$request->companystatus,
                     'created_at' => date('Y-m-d H:i:s'),
                ]);
//dd($uname,$id,$request->company_mobile,$user_id);
                if($user_id){
                    Company::where('id', $id)->update(['user_id' => $user_id]);

                     $role_id = Role::insertGetId([
                   
                    'company_id' => $id,
                    'name' => 'Admin',
                     'created_at' => date('Y-m-d H:i:s'),
                   
                ]);

                }
                
                if ($role_id) {
                UserRole::create([
                'role_id' => $role_id,
                'company_id' => $id,
                'user_id' => $user_id
                 ]);

               // $role_module_id=[1,2,3,4,5,6,7,8,10,14,15,16,17,18,19,20,21,22,23,24];
              //  dd($input);
                foreach ($request->modules as $key => $value) {
                    $module_details[] = DB::table('company_modules')->where('id',$value)->get();
                }

                foreach ($module_details as $key => $mod1) {
                    foreach ($mod1 as $key => $mod) {
                   
                     //  dd($mod->name,'jjjjj');
                    Module::create([
                    'name' => $mod->name,
                    'module_key' => $mod->module_key,
                    'module_url' => $mod->module_url,
                    'module_rank' => $mod->module_rank,
                    'view_sidebar' => '1',
                    'role_id' => $role_id,
                    'company_id' => $id,
                   // 'module_id' => $mod->id,
                     ]);
                    }
                }
                     
               
                //dd($request->modules);
                foreach ($request->modules as $key => $value) {
                   RoleModule::create([
                    'role_id' => $role_id,
                    'company_id' => $id,
                    'module_id' => $value,
                     ]);
                }
                
               // $role_permission_id=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,26,30,31,32,33,34,35,36,37,
              //  45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81
              //  ];
                foreach ($request->permissions as $key => $value) {
                   RolePermission::create([
                    'role_id' => $role_id,
                    'company_id' => $id,
                    'permission_id' => $value,
                     ]);
                }

                 }
                return redirect()->back()->with('success_message', 'Successfully created your Shop');
                //$message['success'] = 'Company added Successfully';
               //return Redirect::back()->withInput($message);
            }
            else
            {
                return redirect()->back()->with('error_message', 'Image Upload Failed');
               // $message['error'] = 'Image Upload Failed';
               // return Redirect::back()->withInput($message);
            }
        }
        else
        {
            return redirect()->back()->with('error_message', 'Company Already Exists');
            //$message['exist'] = 'School Already Exists';
           // return Redirect::back()->withInput($message);
        }
    }
    public function viewcompanymodule()
    {
       // dd('module list');
       // $this->checkpermission('module-list');
        $module = DB::table('company_modules')->orderBy('name', 'asc')->get();
       // $module=Module::join('role_modules', 'role_modules.module_id', '=', 'modules.id')
       // ->where('role_modules.company_id',Auth::user()->company_id)->orderBy('name', 'asc')->select('modules.*')->get();
       // $module = Module::orderBy('name', 'asc')->paginate(10);
        //dd($module);
        return view('backend.company.modulelist', compact('module'));
    }

     public function createcompanymodule()
    {
       // dd('module create');
        $role = Role::where('company_id',Auth::user()->company_id)->get();
        return view('backend.company.createmodule', compact('role'));
    }

    public function storecompanymodule(Request $request)
    {
        $input=\Request::all();
        
        $this->validate($request, [
            'name' => 'required',
            'module_key' => 'required|unique:company_modules',
            'module_url' => 'required',
            'module_rank' => 'required',
        ]);
        $module = DB::table('company_modules')->insert(
                array(
               'name' => $request->name,
            'module_key' => $request->module_key,
            'module_url' => $request->module_url,
            //'company_id' => Auth::user()->company_id,
           // 'view_sidebar' => $request->view_sidebar,
            'module_icon' => $request->module_icon,
            'module_rank' => $request->module_rank,
            'created_at' => date('Y-m-d H:i:s'),
                ));
        
      // dd($input);
            return redirect()->route('companymodule.list')->with('success_message', 'You are successfully created');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
