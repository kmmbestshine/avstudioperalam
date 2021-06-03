<?php

namespace App\Http\Controllers\backend;

use App\Models\Pettycash;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Productcategory;
use App\Models\Sale;
use App\Models\Company;
use App\Models\Salescart;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //$birthday = Sale::whereMonth('sales_date', '=', date('m'))->whereDay('sales_date', '=', date('d')+1)->get();
        $type='studio';
        $studio=\DB::table('serv_invoice_nos')->where('serv_invoice_nos.company_id',Auth::user()->company_id)->where('type',$type)->get();
        
        $tot_revenue=0;
        $tot_paid=0;
        $tot_due=0;
        if($studio){
            foreach ($studio as $s) {
                $with=$s->subtotal;
                $with1=$s->paid_amt;
                $with2=$s->due_amt;
                $tot_revenue += $with;
                $tot_paid += $with1;
                $tot_due += $with2;
                
            }
        }
        $totalcustomer = count($studio);

         $type1='eservice';
        $esevai=\DB::table('serv_invoice_nos')->where('serv_invoice_nos.company_id',Auth::user()->company_id)->where('type',$type1)->get();
        
        $tot_revenue1=0;
        $tot_paid1=0;
        $tot_due1=0;
        if($esevai){
            foreach ($esevai as $e) {
                $with3=$e->subtotal;
                $with4=$e->paid_amt;
                $with5=$e->due_amt;
                $tot_revenue1 += $with3;
                $tot_paid1 += $with4;
                $tot_due1 += $with5;
                
            }
        }
        $totalcustomer1 = count($esevai);

        
        $deposit=\DB::table('deposits')->where('deposits.company_id',Auth::user()->company_id)->get();
        
        $tot_available=0;
        if($deposit){
            foreach ($deposit as $d) {
                $with4=$d->available_amt;
                $tot_available += $with4;
                
            }
        }
        $fundtransfer=\DB::table('fund_transfer')->where('fund_transfer.company_id',Auth::user()->company_id)->get();
        
        $tot_transfer=0;
        $tot_comission=0;
        if($fundtransfer){
            foreach ($fundtransfer as $f) {
                $with5=$f->amount;
                $with6=$f->commision;
                $tot_comission += $with6;
                $tot_transfer += $with5;
                
            }
        }
        $totalcustomer2 = count($fundtransfer);
//dd($studio,$tot_revenue,$tot_paid,$tot_due);
        $sales = Sale::where('sales.company_id',Auth::user()->company_id)
        ->join('products', 'sales.product_id', '=', 'products.id')->select('sales.*','products.gst_percent')->get();
       // dd($sales);
        $totalrevenue = 0;
        if ($sales) {
            foreach ($sales as $w) {
                $with = $w->price;
                $quantity = $w->quantity;
                $gst= ($w->gst_percent / 100) * $with * $quantity;
                $with1 = $with + $gst ;
                $totalrevenue += $with1;
            }
        }
        $ccategory = Productcategory::where('company_id',Auth::user()->company_id)->get();
        $cproduct = Product::where('company_id',Auth::user()->company_id)->get();
        $totalcategory = count($ccategory);
        $totalproduct = count($cproduct);
        $salescart = Salescart::where('company_id',Auth::user()->company_id)->get();
// invoice id
          $inv_profile=\DB::table('invoiceprofiles')->first();

            $inv_prefix=$inv_profile->serialPrefix; 
            $start_serial_no=$inv_profile->serialNumberStart;
            $replacedata=$inv_prefix.$start_serial_no;

            $check_max_inv_no=\DB::table('invoicenos')->whereNotNull('invoice_id')->orderBy('invoice_id', 'desc')
            ->where('company_id',Auth::user()->company_id)->first();
            
            if($check_max_inv_no)
            {
                
                $replacedata1=$inv_prefix;
                $invid=str_replace($replacedata1,'',$check_max_inv_no->invoice_id)+1;
                $invlen=3-strlen($invid);
               // dd($invlen,$invid);
                $finalid='';
                if($invlen != 0){
                    for($i=0;$i<$invlen;$i++)
                    {
                        if($i==0)
                        {
                             $finalid='0'.$invid;   
                        }else
                        {
                            $finalid='0'.$finalid;
                        }
                    }

                }else{
                    $finalid=$invid;
                }
                $request['inv_id']=$inv_prefix.$finalid;
            }
            else
            {
                $request['inv_id']=$inv_prefix.$start_serial_no;
                $invoice=$request['inv_id'];
            }
             $inv_ids=$request['inv_id'];
             //dd($inv_ids);

        return view('backend.dashboard.index', compact('totalrevenue', 'totalcategory', 'totalproduct', 'salescart','inv_ids','tot_revenue','tot_paid','tot_due','totalcustomer','tot_revenue1','tot_paid1','tot_due1','totalcustomer1','tot_available','tot_comission','tot_transfer','totalcustomer2'));
    }
    public function superadminindex()
    {
        //$birthday = Sale::whereMonth('sales_date', '=', date('m'))->whereDay('sales_date', '=', date('d')+1)->get();
       // dd('superadmin');
        $sales = Sale::all();
        $totalrevenue = 0;
        if ($sales) {
            foreach ($sales as $w) {
                $with = $w->price;
                $totalrevenue += $with;
            }
        }
        $ccategory = Productcategory::all();
        $cproduct = Product::all();
        $totalcategory = count($ccategory);
        $totalproduct = count($cproduct);
        $salescart = Salescart::all();


        return view('backend.dashboard.superadminindex', compact('totalrevenue', 'totalcategory', 'totalproduct', 'salescart'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

    }
public function companyedit(Company $company)
    {
        $companys = Company::find(Auth::user()->company_id);
   // dd('jjjjj',$companys);
       return view('backend.dashboard.update',compact('companys'));
    }
    public function updates(Request $request)
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
            
           
        ]);

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
                $id = Company::where('id', Auth::user()->company_id)->update([
                    'company_name' => $request->company_name,
                    'email' => $request->company_email,
                    'contact_no' => $request->company_mobile,
                    'address' => $request->company_address,
                    'city' => $request->company_city,
                    'image' => 'company/'.$filename,
                   
                    
                ]);
                return redirect()->back()->with('success_message', 'Successfully Updated your Shop');
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
