<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;
use App\Models\UserRole;

class StudioController extends Controller
{
    public function create()
    {
       // dd('studio create');
        $this->checkpermission('studio-create');
        $sizes = \DB::table('photosizes')->where('company_id', Auth::user()->company_id)->get();
        
        return view('backend.studio.create', compact('sizes'));
    }
    public function photosizelist()
    {
     // $this->checkpermission('studio-size-list');
    	$sizes = \DB::table('photosizes')->where('company_id', Auth::user()->company_id)->get();
      //dd('list',$sizes);
        return view('backend.studio.sizelist',compact('sizes'));
    }
    public function sizecreate()
    {
      //dd('jjjj');
     // $this->checkpermission('studio-size-create');
        return view('backend.studio.photosizecreate');
    }
    public function photosizestore(Request $request)
    {
         $input=\Request::all();
       // dd($input);
        $this->validate($request, [
            'size' => 'required',
            'status' => 'required',
        ]);
        //dd('esevai store');
       $message = DB::table('photosizes')->insert([
                'company_id' => Auth::user()->company_id,
                'size' => $request->size,
                'status' => $request->status,
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
                ]);
       if ($message) {
            return redirect()->route('studio.size.sizelist')->with('success_message', 'successfully created ');
        } else {
            return redirect()->route('studio.size.create')->with('error_message', 'Failed To create');
        }
    }
    
    public function studiobillverify(Request $request)
    {
      $input = \Request::all();
     //dd($input);
      $name = $request->name;
      $mobile = $request->mobile;
      $address = $request->address;
      $email = $request->email;
      $deliverd_date = $request->deliverd_date;
      $pack_size = $request->pack_size;
      $size = $request->size;
      $qty = $request->qty;
      $amt = $request->amt;
      $counts= sizeof($pack_size);
      if($request->customer_id){
        $customer_id = $request->customer_id;
      }else{
        $customer_id = 'no';
      }
     
      for($i=0; $i < $counts; $i++){
        if($pack_size[$i] != null && $size[$i] == null){
            $sizes[] = $pack_size[$i];
        }else{
            $sizes[] = $size[$i];
        }
      }

      $grand=0;
      for($i=0; $i < $counts; $i++){
        $price =$amt[$i];
        $grand += $price;
      }
     
        return view('backend.studio.studioverify', compact('grand','name','mobile','address','email','deliverd_date','sizes','qty','amt','customer_id'));
    }
    public function studiobillverifyexist(Request $request)
    {
      $input = \Request::all();
     //dd($input);
      $name = $request->name;
      $mobile = $request->mobile;
      $address = $request->address;
      $email = $request->email;
      $deliverd_date = $request->deliverd_date;
      $pack_size = $request->pack_size;
      $size = $request->size;
      $qty = $request->qty;
      $amt = $request->amt;
      $counts= sizeof($pack_size);
      if($request->customer_id){
        $customer_id = $request->customer_id;
      }else{
        $customer_id = 'no';
      }
     
      for($i=0; $i < $counts; $i++){
        if($pack_size[$i] != null && $size[$i] == null){
            $sizes[] = $pack_size[$i];
        }else{
            $sizes[] = $size[$i];
        }
      }

      $grand=0;
      for($i=0; $i < $counts; $i++){
        $price =$amt[$i];
        $grand += $price;
      }
     
        return view('backend.studio.studioverify1', compact('grand','name','mobile','address','email','deliverd_date','sizes','qty','amt','customer_id'));
    }
    public function studiopayment(Request $request)
    {
        return view('backend.studio.payment');
    }
    
    public function poststudiopayment(Request $request)
    {
         $input = \Request::all();
         
         $customer_id = \Request::get('customer_id');
        $customer_id = str_replace(".", "/", $customer_id);
        $type='studio';
       
         $checkfeeExist =\DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                    ->where('type',$type)
                    ->where('customer_id',$customer_id)->first();
          
                   
                if($checkfeeExist)
                    {
                    $getFee = \DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                    ->where('type',$type)
                    ->where('customer_id',$customer_id)->get();
                    }

                    // $amount=0;
                    $t1_feename=array();
                    $t1_amt=array();
                    //$term_type=array();
                    $t1_totamt=0;
                    $t1_ids=array();

                    foreach ($getFee as $amt ) {
                        $size[]=$amt->size;
                        $quandity[]=$amt->quantity;
                        $t1_amt[]=$amt->amount;
                        $t1_totamt+=$amt->amount;
                        $t1_ids[]=$amt->id;
                    }

                    if(!empty($t1_ids)){

                      foreach ($t1_ids as $key => $value) {
                            $all_paidamt = DB::table('service_payment')->where('company_id', Auth::user()->company_id)
                           // ->where('type',$type)
                            ->where('customer_id',$customer_id)->get();
                            
                        }
                     $allpaid_feeId=array();
                    foreach($all_paidamt as $firstlevelids){
                      $allpaid_feeId[]=$firstlevelids->bill_id;
                       
                    }
                      $all_paidamt1=array();
                   // dd($allpaid_feeId);
                        foreach ($allpaid_feeId as $key => $value) {
                            $all_paidamt1[] = DB::table('service_payment')->where('company_id', Auth::user()->company_id)
                            ->where('customer_id',$customer_id)
                            ->where('bill_id',$value)
                            ->get();
                        }
                        $allpaid_ids=array();
                        $total_paidAmt=0;
                        if(!empty($all_paidamt1)){
                        foreach($all_paidamt1 as $firstlevelids){
                        foreach($firstlevelids as $paidids) {
                          //dd($paidids);
                            $allpaid_feeId[]=$paidids->id;
                           // $allpaid_feeName[]=$paidids->fee_name;
                           // $allpaid_termType[]=$paidids->payment_type;
                            $allpaid_ids[]=$paidids->bill_id;
                            $allpaid_amt[]=$paidids->amount;
                            $allpaid_qty[]=$paidids->quantity;
                           // $allconcession_amt[]=$paidids->concession;
                            $allpaid_date[]=$paidids->payment_date;
                           // $allpaid_recvdby[]=$paidids->recived_by;
                           // $allpaid_paymentmode[]=$paidids->payment_mode;
                           // $allpaid_cheqNo[]=$paidids->cheque_no;
                           // $allpaid_cheqDate[]=$paidids->cheque_date;
                           // $allpaid_bankname[]=$paidids->bank_name;
                           // $allpaid_onlineTfno[]=$paidids->transaction_no;
                          //  $allpaid_onlinebkName[]=$paidids->online_bankname;
                            $total_paidAmt+=$paidids->amount;
                            $invoiceids[]=$paidids->invoice_no;
                        }
                    }
                    }
                   

                    //dd('hi',$all_paidconcessionamt1,$unique_paid_id);
                    $allunpaid_ids= array_diff($t1_ids, $allpaid_ids);
                    $all_unpaidamt=array();
                     if(!empty($allunpaid_ids))
                    {
                        
                    foreach ($allunpaid_ids as $key => $value) {
                            $all_unpaidamt[] = DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                            ->where('id',$value)->get();
                        }
                        }
                        $unpaid_totamt=0;

                if($all_unpaidamt != null )
                {
                    foreach ($all_unpaidamt as $key ) {
                        foreach ($key as $amt) {
                             if($amt->amount != null)
                            {
                            $unpaid_size[]=$amt->size;
                            $unpaid_amt[]=$amt->amount;
                            $unpaid_service_id[]=$amt->service_id;
                            $unpaid_totamt+=$amt->amount;
                            $unpaid_quantity[]=$amt->quantity;
                            $unpaid_ids[]=$amt->id;

                             }
                        }
                    }
                }else{
                              $unpaid_size=[];
                              $unpaid_ids=[];
                               $unpaid_service_id=[];
                              $unpaid_amt=[];
                              $unpaid_quantity=[];
                             }

                $tot_bal_amt=$t1_totamt- $total_paidAmt;


                    }
                 $sizes = \DB::table('photosizes')->where('company_id', Auth::user()->company_id)->get();
                 $customer =\DB::table('ser_customers')->where('company_id', Auth::user()->company_id)
                    ->where('type',$type)
                    ->where('customer_id',$customer_id)->first();
            
            return view('backend.studio.customerpayment', compact('unpaid_size','unpaid_ids','unpaid_totamt','unpaid_amt','total_paidAmt','allpaid_ids','tot_bal_amt','total_paidAmt','t1_totamt','allpaid_ids','allunpaid_ids','t1_feename','t1_amt','t1_ids','unpaid_quantity','customer_id','allpaid_amt','allpaid_date','invoiceids','allpaid_qty','sizes','customer','unpaid_service_id'));

       }

    public function studiopaymentverify( Request $request) 
     {
      $input = \Request::all();
     
      $type='studio';
$customer =\DB::table('ser_customers')->where('company_id', Auth::user()->company_id)
                    ->where('type',$type)
                    ->where('customer_id',$request->customer_id)->first();
        // dd($customer,$input);
     //  $input = \Request::all();
     // dd($input);
      $name = $customer->client_name;
      $mobile = $customer->phone;
      $address = $customer->address;
      $email = $customer->email;
     $unpaid_ids = $request->unpaid_ids;
      
     
      foreach ($unpaid_ids as $key => $paid_id) {
        $selected_payment[] =\DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                    ->where('type',$type)
                    ->where('customer_id',$request->customer_id)
                    ->where('id',$paid_id)->get();
      }
      $grand=0;
      foreach ($selected_payment as $key => $get1) {
        foreach ($get1 as $key => $get) {
          $sizes[] = $get->size;
          $qty[] = $get->quantity;
          $amt[] = $get->amount;
          $paid_ids[] = $get->id;
          $grand += $get->amount;
         
      }}

        return view('backend.studio.studiopaymentverify1', compact('customer','grand','name','mobile','address','email','sizes','qty','amt','paid_ids'));

       
   }
    public function viewAllcustomer(Request $request)
    {
      $allcustomer=[];
        return view('backend.studio.viewAllcustomer',compact('allcustomer'));
    }
    public function UploadOrderphotos(Request $request,$id)
    {
      
      $customer_id=$id;
      //dd($service_id);
        return view('backend.studio.uploadCustomerphotos',compact('customer_id'));
    }
    public function postUploadOrderphotos(Request $request)
    {
    $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
  
        $imageName = time().'.'.$request->image->extension();  
        
   
        $request->image->move(public_path('customerImage'), $imageName);

         $inv_details = \DB::table('serv_invoice_nos')->where('customer_id',$request->customer_id)->first();
        // dd($inv_details);

        $message1 =  DB::table('customer_photos')->insert(
                array(
            'event_name' => $request->name,
            'customer_id' => $inv_details->customer_id,
            'service_id' => $inv_details->service_id,
            'images' => $imageName,
           // 'status' => $request->address,
            'company_id' => Auth::user()->company_id,
           // 'payment_status' => $request->email,
           // 'dates' => date("Y-m-d"),
            'created_at' => date('Y-m-d H:i:s'),
                ));

         
  
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
   
    }
     public function studioallCustomers(Request $request)
    {
      $input = \Request::all();
     // dd($input);
      $start = $request->start;
        $end = $request->end;
        $type='studio';
        $allcustomer = \DB::table('ser_customers')->join('service_bills', 'service_bills.customer_id', 'ser_customers.customer_id')
       // ->join('serv_invoice_nos', 'serv_invoice_nos.customer_id', 'ser_customers.customer_id')
        ->where('ser_customers.company_id', Auth::user()->company_id)->where('ser_customers.type',$type)
        ->where('service_bills.service_id','!=', null)->whereBetween('ser_customers.dates', [$start, $end])->get();
        
        return view('backend.studio.viewAllcustomer',compact('allcustomer'));
    }
    public function updateOrderstatus(Request $request,$id)
    {
      
      $update = \DB::table('serv_invoice_nos')->where('serv_invoice_nos.customer_id',$id)->first();
     // dd('kkkkkkk',$id,$update);
        return view('backend.studio.updateStatus',compact('update'));
    }
    public function updateproofOrderstatus(Request $request,$id)
    {
      
      $update = \DB::table('serv_invoice_nos')->where('customer_id',$id)->first();
     // dd('kkkkkkk',$id,$update);
        return view('backend.studio.updateproofStatus',compact('update'));
    }
    public function studioUpdateProofstatus(Request $request,$id)
    {
      $input = \Request::all();
      //dd($input);
      
       $message =  DB::table('serv_invoice_nos')->where('customer_id',$id)->update(
                array(
            
            'proof_status' => $request->status,
            'proof_dt' => date("Y-m-d"),
            
                ));
       $getCustomer=[];
       //return response(['success_message' => 'SuccessFully Make sales']);
        return view('backend.studio.viewAllundeliverd',compact('getCustomer'));
    }
    public function viewAllproof(Request $request)
    {
      $getCustomer=[];
        return view('backend.studio.viewAllProoflist',compact('getCustomer'));
    }
    public function studioallproof(Request $request)
    {
      $input = \Request::all();
     // dd($input);
      $start = $request->start;
        $end = $request->end;
        $type='studio';
        $total_balancAmt=0; 
        $total_paidAmt =0;
        $total_customerAmt =0;

              $getCustomer = \DB::table('ser_customers')->where('ser_customers.company_id', Auth::user()->company_id)
               ->join('serv_invoice_nos', 'serv_invoice_nos.customer_id', 'ser_customers.customer_id')
               ->where('serv_invoice_nos.proof_status','=', 1)
               ->where('serv_invoice_nos.deliverd_status','=', '0')
               ->orderBy('ser_customers.customer_id', 'asc')->get()->unique('customer_id');

                    foreach ($getCustomer as $customer ) {
                      $getFee =\DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                                        ->where('bal_status','!=', 1)
                                        ->where('customer_id',$customer->customer_id)->get();
                                  $amount=0;
                                  foreach ($getFee as $amt ) {
                                  $amount+=$amt->amount;
                                  }
                                  $customer->getcustFee=$amount;
//paid details             
                    $paidAmts =\DB::table('service_payment')->where('company_id', Auth::user()->company_id)
                    ->where('customer_id','=',$customer->customer_id)
                    ->groupBy('customer_id')->sum('amount');

                    $customer->paidcust_Amount=$paidAmts;
//Balance Details
                    $balancAmt= $customer->getcustFee - $customer->paidcust_Amount;
                    $customer->balancAmt=$balancAmt; 
                    $total_balancAmt += $balancAmt;
                     //$total_paidAmt +=$paidAmts;
                     if($balancAmt != '0')
                     {
                     $total_customerAmt +=$amount;
                     $total_paidAmt +=$paidAmts;
                        
                     }
                   
                       }
        return view('backend.studio.viewAllProoflist',compact('getCustomer'));
    }
    public function viewAlldeliverd(Request $request)
    {
      $getCustomer=[];
        return view('backend.studio.viewAlldeliverd',compact('getCustomer'));
    }
     public function studioalldeliverd(Request $request)
    {

      $input = \Request::all();
     // dd($input);
      $start = $request->start;
        $end = $request->end;
        $type='studio';
        $total_balancAmt=0; 
        $total_paidAmt =0;
        $total_customerAmt =0;

              $getCustomer = \DB::table('ser_customers')->where('ser_customers.company_id', Auth::user()->company_id)
               ->join('serv_invoice_nos', 'serv_invoice_nos.customer_id', 'ser_customers.customer_id')
               ->where('serv_invoice_nos.proof_status','=', 1)
               ->where('serv_invoice_nos.deliverd_status','=', '1')
               ->orderBy('ser_customers.customer_id', 'asc')->get()->unique('customer_id');

                    foreach ($getCustomer as $customer ) {
                      $getFee =\DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                                        ->where('bal_status','!=', 1)
                                        ->where('customer_id',$customer->customer_id)->get();
                                  $amount=0;
                                  foreach ($getFee as $amt ) {
                                  $amount+=$amt->amount;
                                  }
                                  $customer->getcustFee=$amount;
//paid details             
                    $paidAmts =\DB::table('service_payment')->where('company_id', Auth::user()->company_id)
                    ->where('customer_id','=',$customer->customer_id)
                    ->groupBy('customer_id')->sum('amount');

                    $customer->paidcust_Amount=$paidAmts;
//Balance Details
                    $balancAmt= $customer->getcustFee - $customer->paidcust_Amount;
                    $customer->balancAmt=$balancAmt; 
                    $total_balancAmt += $balancAmt;
                     //$total_paidAmt +=$paidAmts;
                     if($balancAmt != '0')
                     {
                     $total_customerAmt +=$amount;
                     $total_paidAmt +=$paidAmts;
                        
                     }
                   
                       }
        
        return view('backend.studio.viewAlldeliverd',compact('getCustomer'));
    }
    public function viewAllundeliverd(Request $request)
    {
      //$this->checkpermission('studio-order-undeliverd');
      $getCustomer=[];
        return view('backend.studio.viewAllundeliverd',compact('getCustomer'));
    }
     public function studioallUndeliverd(Request $request)
    {
      $input = \Request::all();
     // dd($input);
      $start = $request->start;
        $end = $request->end;
        $type='studio';
        $total_balancAmt=0; 
        $total_paidAmt =0;
        $total_customerAmt =0;

              $getCustomer = \DB::table('ser_customers')->where('ser_customers.company_id', Auth::user()->company_id)
               ->join('serv_invoice_nos', 'serv_invoice_nos.customer_id', 'ser_customers.customer_id')
               ->where('serv_invoice_nos.proof_status','=', 0)
               ->orderBy('ser_customers.customer_id', 'asc')->get()->unique('customer_id');

                    foreach ($getCustomer as $customer ) {
                      $getFee =\DB::table('service_bills')->where('company_id', Auth::user()->company_id)
                                        ->where('bal_status','!=', 1)
                                        ->where('customer_id',$customer->customer_id)->get();
                                  $amount=0;
                                  foreach ($getFee as $amt ) {
                                  $amount+=$amt->amount;
                                  }
                                  $customer->getcustFee=$amount;
//paid details             
                    $paidAmts =\DB::table('service_payment')->where('company_id', Auth::user()->company_id)
                    ->where('customer_id','=',$customer->customer_id)
                    ->groupBy('customer_id')->sum('amount');

                    $customer->paidcust_Amount=$paidAmts;
//Balance Details
                    $balancAmt= $customer->getcustFee - $customer->paidcust_Amount;
                    $customer->balancAmt=$balancAmt; 
                    $total_balancAmt += $balancAmt;
                     //$total_paidAmt +=$paidAmts;
                     if($balancAmt != '0')
                     {
                     $total_customerAmt +=$amount;
                     $total_paidAmt +=$paidAmts;
                        
                     }
                   
                       }


       
        return view('backend.studio.viewAllundeliverd',compact('getCustomer'));
    }
    public function studioUpdateDeliverystatus(Request $request)
    {
      $input = \Request::all();
      
       $message =  DB::table('serv_invoice_nos')->where('customer_id',$request->customer_id)->update(
                array(
            
            'deliverd_status' => $request->status,
            'deliverd_dt' => date("Y-m-d"),
                ));
       $getCustomer=[];
       //return response(['success_message' => 'SuccessFully Make sales']);
        return view('backend.studio.viewAllProoflist',compact('getCustomer'));
    }
    public function studiopaymentstore123(Request $request)
    {
        $input = \Request::all();
      // dd($input);
       $type='studio';
        foreach ($request->unpaid_ids as $key => $unpaid_id) {
         $unpaid_fee[]= DB::table('service_bills')->where('id',$unpaid_id)->where('company_id', Auth::user()->company_id)->get();
        }
       // dd($unpaid_fee);
        $tot_fee_amt=0;
        foreach ($unpaid_fee as $key1 => $get1) {
          foreach ($get1 as $key => $get) {
           $service_id= $get->service_id;
           $customer_id = $get->customer_id;
           $sizes[]= $get->size;
           $quantity[]= $get->quantity;
           $amount[]= $get->amount;
           $delivery_date[]= $get->delivery_date;
           $tot_fee_amt +=$get->amount;

          }
         
        }

      // dd($tot_fee_amt);
        // Invoice Id

             $companyname=\DB::table('companies')->where('id', Auth::user()->company_id)->select('company_name')->first();
            //dd($companyname);
            $invoice_company_name=str_replace(" ","",$companyname->company_name);
            $companyname=substr($invoice_company_name, 0, 3);
            $check_max_invoice_no=\DB::table('serv_invoice_nos')->where('company_id', Auth::user()->company_id)->where('type',$type)->orderBy('id', 'desc')->first();
            //dd($check_max_invoice_no);
            if($check_max_invoice_no)
            {
                $companyid=(Auth::user()->company_id);
                $replacedata=$companyname.'STU'.$companyid;

                $invoiceid=str_replace($replacedata,'',$check_max_invoice_no->id)+1;
               // dd($replacedata,$check_max_invoice_no->invoice_no);
                $invoicelen=4-strlen($invoiceid);
                //dd($invoiceid);
                $finalid='';
                if($invoicelen != 0){
                    for($i=0;$i<$invoicelen;$i++)
                    {
                        if($i==0)
                        {
                             $finalid='0'.$invoiceid;   
                        }else
                        {
                            $finalid='0'.$finalid;
                        }
                    }

                }else{
                    $finalid=$invoiceid;
                }
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.$finalid;
            }
            else
            {
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.'0001';
                $invoice=$request['invoice_id'];
            }
             $invoice_ids=$request['invoice_id'];
            // dd($invoice_ids);

$customerObj1 = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->select('customer_id')->latest('id')->first();
            $compay_id=Auth::user()->company_id;
            if ($customerObj1) {
                  $orderNr = $customerObj1->customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-'.$compay_id . str_pad(1, 8, "0", STR_PAD_LEFT);
              }
             /* if ($customerObj1) {
                  $orderNr = $customerObj1->customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = $stpad = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-' . str_pad(1, 8, "0", STR_PAD_LEFT);
              }*/
            
              DB::table('serv_invoice_nos')->insert([
                'service_id' => $service_id,
                'customer_id' => $customer_id,
                'invoice_no' => $invoice_ids,
                'subtotal' => $request->subtot,
                'payment_mode' => $request->pmMode,
                'type' => $type,
                'cheq_no' => $request->cheqno,
                'cheq_dt' => $request->cheqdate,
                'bank_name' => $request->bank_name,
                'transaction_no' => $request->trans_no,
                'online_bank_name' => $request->bank_name1,
                'company_id' => Auth::user()->company_id,
                 'paid_amt' => $request->amountRecieved,
                'due_amt' => $request->due_amount,
                'inv_dt' => date('Y-m-d'),
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                
                ]);
             // 
            //insert Paid Amount
              for ($i = 0; $i < $request->input('total_photo'); $i++) {
        $result=DB::table('service_payment')->insert(
            array(
              'service_id' =>$service_id,
            'customer_id' =>$customer_id,
            'invoice_no' =>$invoice_ids,
            'company_id' => Auth::user()->company_id,
             'bill_id' => $request['unpaid_ids'][$i],
             //'payment_status' => $request['rate'][$i],
             'quantity' => $request['qty'][$i],
             //'price' => $request['qty'][$i],
             'amount' => $request['amt'][$i],
            // 'particulars' => $request['service_name'][$i],
             'delivery_date' => $delivery_date[$i],
             //'units' => $request['deliverd_date'][$i],
             'payment_date' => date('Y-m-d'),
             'created_at' => date('Y-m-d H:i:s'),
            
                    ));
        }

        

        if($tot_fee_amt == $request->amountRecieved){
          $balance_amt = 0;
        }else{
          $balance_amt = $tot_fee_amt - $request->amountRecieved;
        }

           $last_size = end($sizes);
           $last_qty = end($quantity);
           $last_delivery_dt = end($delivery_date);
          // dd($invoice_ids,$service_id,'hhh',$delivery_date,$balance_amt,$tot_fee_amt,$request->amountRecieved,$last_size,$last_qty,$last_delivery_dt);  
             //dd($request->due_amount,$input,$request->input('total_photo')) ;
             // dd($service_id);
        // for ($i = 0; $i < $request->input('total_photo'); $i++) {
        $result=DB::table('service_bills')->insert(
            array(
              'service_id' =>$service_id,
            'customer_id' =>$customer_id,
            'invoice_no' =>$invoice_ids,
            'size' => $last_size,
            'quantity' => $last_qty,
            'type' => $type,
            //'price' => $request['rate'][$i],
            'amount' => $balance_amt,
           // 'particulars' => $request['service_name'][$i],
            'delivery_date' => $last_delivery_dt,
            //'units' => $request['units'][$i],
           'company_id' => Auth::user()->company_id,
            'bill_createdBy' => Auth::user()->username,
            'bill_date' => date('Y-m-d'),
             'created_at' => date('Y-m-d H:i:s'),
            
                    ));
      //  }
        
       
        $company=\DB::table('companies')->where('id', Auth::user()->company_id)->first();
       // dd($company);
        $report = DB::table('service_bills')->select('service_bills.*')
            ->where('service_bills.invoice_no',$invoice_ids)
            ->where('service_bills.type',$type)
            ->where('service_bills.company_id',Auth::user()->company_id)->get();
         //dd($report); 
        $invoice_details = DB::table('serv_invoice_nos')->where('invoice_no',$invoice_ids)
        ->where('type',$type)->where('company_id',Auth::user()->company_id)->first();
           // dd($invoice_details,$report);
        $customer = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->latest('id')->first();
       
        return view('backend.pdfbill.studiobill', compact('report','invoice_details','company','customer','service_id'));
    }
     public function studiopaymentstore(Request $request)
    {
        $input = \Request::all();
      // dd('old',$input);
       $type='studio';
       
       // $customer_id = $request->customer_id;
        $tot_received = $request->amountRecieved;
         $customer_id = $request->customer_id;

       // dd($customer_id);
        // Invoice Id

             $companyname=\DB::table('companies')->where('id', Auth::user()->company_id)->select('company_name')->first();
            //dd($companyname);
            $invoice_company_name=str_replace(" ","",$companyname->company_name);
            $companyname=substr($invoice_company_name, 0, 3);
            $check_max_invoice_no=\DB::table('serv_invoice_nos')->where('company_id', Auth::user()->company_id)->orderBy('id', 'desc')->first();
            //dd($check_max_invoice_no);
            if($check_max_invoice_no)
            {
                $companyid=(Auth::user()->company_id);
                $replacedata=$companyname.'STU'.$companyid;

                $invoiceid=str_replace($replacedata,'',$check_max_invoice_no->id)+1;
               // dd($replacedata,$check_max_invoice_no->invoice_no);
                $invoicelen=4-strlen($invoiceid);
                //dd($invoiceid);
                $finalid='';
                if($invoicelen != 0){
                    for($i=0;$i<$invoicelen;$i++)
                    {
                        if($i==0)
                        {
                             $finalid='0'.$invoiceid;   
                        }else
                        {
                            $finalid='0'.$finalid;
                        }
                    }

                }else{
                    $finalid=$invoiceid;
                }
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.$finalid;
            }
            else
            {
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.'0001';
                $invoice=$request['invoice_id'];
            }
             $invoice_ids=$request['invoice_id'];

            $customerObj1 = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->select('customer_id')->latest('id')->first();
            $compay_id=Auth::user()->company_id;
            if ($customerObj1) {
                  $orderNr = $customerObj1->customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-'.$compay_id . str_pad(1, 8, "0", STR_PAD_LEFT);
              }
             /* if ($customer_id) {
                  $orderNr = $customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-' . str_pad(1, 8, "0", STR_PAD_LEFT);
              }*/
              if($request->amountRecieved > $request->subtot){
                 $due_amt = 0;
                 $received =$request->subtot;
              }else{
                $due_amt =$request->due_amount;
                $received =$request->amountRecieved;
              }
           // dd($invoice_ids,$service_id);
              DB::table('serv_invoice_nos')->insert([
                'service_id' => $service_id,
                'customer_id' => $customer_id,
                'invoice_no' => $invoice_ids,
                'subtotal' => $request->subtot,
                'payment_mode' => $request->pmMode,
                'type' => $type,
                'cheq_no' => $request->cheqno,
                'cheq_dt' => $request->cheqdate,
                'bank_name' => $request->bank_name,
                'transaction_no' => $request->trans_no,
                'online_bank_name' => $request->bank_name1,
                'company_id' => Auth::user()->company_id,
                 'paid_amt' => $received,
                'due_amt' => $due_amt,
                'inv_dt' => date('Y-m-d'),
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                
                ]);
            

             
            // dd($request->due_amount,$input,$request->input('total_photo'),$request->paid_ids[0]) ;
             // dd($service_id);
       

        $sub_totals = $request->subtot;
        $tot_paids = $request->amountRecieved;
// SUB TOTAL IS EQUAL TO PAID AMOUNT

  if($sub_totals == $tot_paids){

        foreach ($request->paid_ids as $key => $value) {
         $result1[]=DB::table('service_bills')->where('id',$value)->get();

        }
          
             
                            foreach ($result1 as $key => $res1) {
                              foreach ($res1 as $key => $res) {
                               // dd($res->amount);
                                $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                              }
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                           // dd('equal');
// SUB TOTAL IS EQUAL TO PAID AMOUNT
  }elseif($sub_totals > $tot_paids){
                            foreach ($request->paid_ids as $key => $value) {
                             $result1[]=DB::table('service_bills')->where('id',$value)->get();

                            }

                            $tot_all_amt=0;
                            $bill_tot_amount=0;
                           
                            foreach ($result1 as $key => $res1) {
                              foreach ($res1 as $key => $res) {
                               // dd($res->amount);
                                $all_ids[]=$res->id;
                            $all_amt[] = $res->amount;
                            $all_size[] = $res->size;
                            $all_type[] = $res->type;
                            $all_quantity[] = $res->quantity;
                            $all_delivery_date[] = $res->delivery_date;
                              }
                            }
                            
                            $single_id = count($all_ids);
                           // dd($single_id);
                            if($single_id == 1){
                                      $single=DB::table('service_bills')->where('id',$all_ids[0])->first();
                                      $single_amt=$single->amount;
                                      $singlepaid_amt = $request->amountRecieved;
                                      $single_bal_amt = $single_amt - $singlepaid_amt;
                                     

                                      $result=DB::table('service_payment')->insert(
                                      array(
                                        'service_id' =>$service_id,
                                      'customer_id' =>$customer_id,
                                      'invoice_no' =>$invoice_ids,
                                      'company_id' => Auth::user()->company_id,
                                       'bill_id' => $all_ids[0],
                                       'quantity' => $all_quantity[0],
                                       'size' => $all_size[0],
                                       'amount' => $singlepaid_amt,
                                       'delivery_date' => $all_delivery_date[0],
                                       'payment_date' => date('Y-m-d'),
                                       'created_at' => date('Y-m-d H:i:s'),
                                      
                                              ));

                                      $result=DB::table('service_bills')->insert(
                                        array(
                                          'service_id' =>$service_id,
                                        'customer_id' =>$customer_id,
                                        'invoice_no' =>$invoice_ids,
                                        'size' => $all_size[0],
                                        'quantity' => $all_quantity[0],
                                        'type' => $all_type[0],
                                        'bal_status' => 1,
                                        'amount' => $single_bal_amt,
                                        'delivery_date' => $all_delivery_date[0],
                                       'company_id' => Auth::user()->company_id,
                                        'bill_createdBy' => Auth::user()->username,
                                        'bill_date' => date('Y-m-d'),
                                         'created_at' => date('Y-m-d H:i:s'),
                                        
                                                ));
                                     //  dd('single inserted',$single,$single_amt,$singlepaid_amt,$single_bal_amt);



                            }else{
                                       // dd($result1,$request->amountRecieved);
                                        
                                        if($request->amountRecieved <= $all_amt[0] ){
                                          $first_bill_id = current($all_ids);
                                          $first_bill_paid = $request->amountRecieved;
                                          $first_bill_bal = $all_amt[0] - $request->amountRecieved;
                                         // dd('kkkk',$first_bill_bal,$first_bill_paid,$all_amt[0]);
                                          $result=DB::table('service_payment')->insert(
                                            array(
                                              'service_id' =>$service_id,
                                            'customer_id' =>$customer_id,
                                            'invoice_no' =>$invoice_ids,
                                            'company_id' => Auth::user()->company_id,
                                             'bill_id' => $all_ids[0],
                                             'quantity' => $all_quantity[0],
                                             'size' => $all_size[0],
                                             'amount' => $first_bill_paid,
                                             'delivery_date' => $all_delivery_date[0],
                                             'payment_date' => date('Y-m-d'),
                                             'created_at' => date('Y-m-d H:i:s'),
                                            
                                                    ));

                                            $result=DB::table('service_bills')->insert(
                                              array(
                                                'service_id' =>$service_id,
                                              'customer_id' =>$customer_id,
                                              'invoice_no' =>$invoice_ids,
                                              'size' => $all_size[0],
                                              'quantity' => $all_quantity[0],
                                              'type' => $all_type[0],
                                              'bal_status' => 1,
                                              'amount' => $first_bill_bal,
                                              'delivery_date' => $all_delivery_date[0],
                                             'company_id' => Auth::user()->company_id,
                                              'bill_createdBy' => Auth::user()->username,
                                              'bill_date' => date('Y-m-d'),
                                               'created_at' => date('Y-m-d H:i:s'),
                                              
                                                      ));
                                           // dd($first_bill_bal);
                                        }else{
                                          $tot_mor_amt=0;
                                          $mor_tot_amt=0;
                                         // dd($result1,'hhhh');
                                          foreach ($result1 as $key => $got1) {
                                            foreach ($got1 as $key => $got) {
                                             $tot_mor_amt += $got->amount;
                                            if($request->amountRecieved >= $tot_mor_amt ){
                                                $mor_bill_id[]=$got->id;
                                                $mor_bill_qty[]=$got->quantity;
                                                $mor_bill_size[]=$got->size;
                                                $mor_bill_amt[]=$got->amount;
                                                $mor_tot_amt +=$got->amount;
                                                $mor_bill_deliver_dt[]=$got->delivery_date;
                                              }
                                            }
                                            
                                          }

                                         // dd($all_ids,$mor_bill_id);
                                          $bal_bill_ids=array_diff($all_ids,$mor_bill_id);
                                          $first_bal_bill_id = current($bal_bill_ids);
                                          $difference_amt=DB::table('service_bills')->where('id',$first_bal_bill_id)->first();
                                          $push_one_id = $difference_amt->id;
                                          $push_one_qty = $difference_amt->quantity;
                                          $push_one_size = $difference_amt->size;
                                          $push_one_delivery_date = $difference_amt->delivery_date;
                                          //dd($request->amountRecieved,$mor_tot_amt);
                                          $push_one_amt= $request->amountRecieved - $mor_tot_amt;
                                          $bal_one_amt = $difference_amt->amount - $push_one_amt;
                                          array_push($mor_bill_amt,$push_one_amt);
                                          array_push($mor_bill_id,$push_one_id);
                                          array_push($mor_bill_qty,$push_one_qty);
                                          array_push($mor_bill_size,$push_one_size);
                                          array_push($mor_bill_deliver_dt,$push_one_delivery_date);

                                          $cound = count($mor_bill_id);

                                          for ($i = 0; $i < $cound; $i++) {
                                            $result123=DB::table('service_payment')->insert(
                                                array(
                                                  'service_id' =>$service_id,
                                                'customer_id' =>$customer_id,
                                                'invoice_no' =>$invoice_ids,
                                                'company_id' => Auth::user()->company_id,
                                                 'bill_id' => $mor_bill_id[$i],
                                                 'size' => $mor_bill_size[$i],
                                                 'quantity' => $mor_bill_qty[$i],
                                                 'amount' => $mor_bill_amt[$i],
                                                 'delivery_date' => $mor_bill_deliver_dt[$i],
                                                 'payment_date' => date('Y-m-d'),
                                                 'created_at' => date('Y-m-d H:i:s'),
                                                
                                                        ));
                                            }

                                             $result234=DB::table('service_bills')->insert(
                                                    array(
                                                      'service_id' =>$service_id,
                                                    'customer_id' =>$customer_id,
                                                    'invoice_no' =>$invoice_ids,
                                                    'size' => $push_one_size,
                                                    'quantity' => $push_one_qty,
                                                    'bal_status' => 1,
                                                    'type' => $type,
                                                    'amount' => $bal_one_amt,
                                                    'delivery_date' => $push_one_delivery_date,
                                                   'company_id' => Auth::user()->company_id,
                                                    'bill_createdBy' => Auth::user()->username,
                                                    'bill_date' => date('Y-m-d'),
                                                     'created_at' => date('Y-m-d H:i:s'),
                                                    
                                                            ));


                                         // dd('hhhhhhhh',$mor_bill_amt,$mor_bill_id,$mor_bill_qty,$mor_bill_size,$mor_bill_deliver_dt,$push_one_delivery_date,$request->amountRecieved,$mor_bill_amt);
                                          
                                        }
                            }

                            
        }else{

                             //$result2=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();
                             foreach ($request->paid_ids as $key => $value) {
                             $result2[]=DB::table('service_bills')->where('id',$value)->get();

                            }
                            

                            foreach ($result2 as $key => $res1) {
                              foreach ($res1 as $key => $res) {
                               //dd($res->amount);
                            $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                              }
                               
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                    // dd('less than');       
          
        }

$company=\DB::table('companies')->where('id', Auth::user()->company_id)->first();
       // dd($company);
        foreach ($request['paid_ids'] as $key => $paidid) {
         $report[] = DB::table('service_bills')->select('service_bills.*')
            ->where('service_bills.id',$paidid)
            ->where('service_bills.type',$type)
            ->where('service_bills.company_id',Auth::user()->company_id)->get();
        }
        
        // dd($report); 
        $invoice_details = DB::table('serv_invoice_nos')->where('invoice_no',$invoice_ids)
        ->where('type',$type)->where('company_id',Auth::user()->company_id)->first();
           // dd($invoice_details,$report,$company);
        $customer = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->latest('id')->first();

        return view('backend.pdfbill.studiobillexist', compact('report','invoice_details','company','customer','service_id','tot_received'));
    }
    public function studiobillstoreexist(Request $request)
    {
        $input = \Request::all();
      // dd('old',$input);
       $type='studio';
       
        $customer_id = $request->customer_id;

       // dd($customer_id);
        // Invoice Id

             $companyname=\DB::table('companies')->where('id', Auth::user()->company_id)->select('company_name')->first();
            //dd($companyname);
            $invoice_company_name=str_replace(" ","",$companyname->company_name);
            $companyname=substr($invoice_company_name, 0, 3);
            $check_max_invoice_no=\DB::table('serv_invoice_nos')->where('company_id', Auth::user()->company_id)->where('type',$type)->orderBy('id', 'desc')->first();
            //dd($check_max_invoice_no);
            if($check_max_invoice_no)
            {
                $companyid=(Auth::user()->company_id);
                $replacedata=$companyname.'STU'.$companyid;

                $invoiceid=str_replace($replacedata,'',$check_max_invoice_no->id)+1;
               // dd($replacedata,$check_max_invoice_no->invoice_no);
                $invoicelen=4-strlen($invoiceid);
                //dd($invoiceid);
                $finalid='';
                if($invoicelen != 0){
                    for($i=0;$i<$invoicelen;$i++)
                    {
                        if($i==0)
                        {
                             $finalid='0'.$invoiceid;   
                        }else
                        {
                            $finalid='0'.$finalid;
                        }
                    }

                }else{
                    $finalid=$invoiceid;
                }
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.$finalid;
            }
            else
            {
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.'0001';
                $invoice=$request['invoice_id'];
            }
             $invoice_ids=$request['invoice_id'];
            $customerObj1 = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->select('customer_id')->latest('id')->first();
            $compay_id=Auth::user()->company_id;
            if ($customerObj1) {
                  $orderNr = $customerObj1->customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-'.$compay_id . str_pad(1, 8, "0", STR_PAD_LEFT);
              }
             /* if ($compay_id) {
                  $orderNr = Auth::user()->company_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'ESO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'ESO-' . str_pad(1, 8, "0", STR_PAD_LEFT);
              }*/
              if($request->amountRecieved > $request->subtot){
                 $due_amt = 0;
                 $received =$request->subtot;
              }else{
                $due_amt =$request->due_amount;
                $received =$request->amountRecieved;
              }
            //dd($invoice_ids,$service_id);
              DB::table('serv_invoice_nos')->insert([
                'service_id' => $service_id,
                'customer_id' => $customer_id,
                'invoice_no' => $invoice_ids,
                'subtotal' => $request->subtot,
                'payment_mode' => $request->pmMode,
                'type' => $type,
                'cheq_no' => $request->cheqno,
                'cheq_dt' => $request->cheqdate,
                'bank_name' => $request->bank_name,
                'transaction_no' => $request->trans_no,
                'online_bank_name' => $request->bank_name1,
                'company_id' => Auth::user()->company_id,
                 'paid_amt' => $received,
                'due_amt' => $due_amt,
                'inv_dt' => date('Y-m-d'),
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                
                ]);
            

             
             //dd($request->due_amount,$input,$request->input('total_photo')) ;
             // dd($service_id);
         for ($i = 0; $i < $request->input('total_photo'); $i++) {
        $result=DB::table('service_bills')->insert(
            array(
              'service_id' =>$service_id,
            'customer_id' =>$customer_id,
            'invoice_no' =>$invoice_ids,
            'size' => $request['sizes'][$i],
            'quantity' => $request['qty'][$i],
            'type' => $type,
            'amount' => $request['amt'][$i],
            'delivery_date' => $request['deliverd_date'][$i],
           'company_id' => Auth::user()->company_id,
            'bill_createdBy' => Auth::user()->username,
            'bill_date' => date('Y-m-d'),
             'created_at' => date('Y-m-d H:i:s'),
            
                    ));
        }

        $sub_totals = $request->subtot;
        $tot_paids = $request->amountRecieved;
// SUB TOTAL IS EQUAL TO PAID AMOUNT

  if($sub_totals == $tot_paids){
          $result1=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();

                            foreach ($result1 as $key => $res) {

                            $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                           // dd('equal');
// SUB TOTAL IS EQUAL TO PAID AMOUNT
  }elseif($sub_totals > $tot_paids){
                            $result1=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();
                            $tot_all_amt=0;
                            $bill_tot_amount=0;
                            foreach ($result1 as $key => $res1) {
                            $all_ids[]=$res1->id;
                            $all_amt[] = $res1->amount;
                            $all_size[] = $res1->size;
                            $all_type[] = $res1->type;
                            $all_quantity[] = $res1->quantity;
                            $all_delivery_date[] = $res1->delivery_date;

                          
                            }
                            
                            $single_id = count($all_ids);
                            if($single_id == 1){
                                      $single=DB::table('service_bills')->where('id',$all_ids[0])->first();
                                      $single_amt=$single->amount;
                                      $singlepaid_amt = $request->amountRecieved;
                                      $single_bal_amt = $single_amt - $singlepaid_amt;
                                     

                                      $result=DB::table('service_payment')->insert(
                                      array(
                                        'service_id' =>$service_id,
                                      'customer_id' =>$customer_id,
                                      'invoice_no' =>$invoice_ids,
                                      'company_id' => Auth::user()->company_id,
                                       'bill_id' => $all_ids[0],
                                       'quantity' => $all_quantity[0],
                                       'size' => $all_size[0],
                                       'amount' => $singlepaid_amt,
                                       'delivery_date' => $all_delivery_date[0],
                                       'payment_date' => date('Y-m-d'),
                                       'created_at' => date('Y-m-d H:i:s'),
                                      
                                              ));

                                      $result=DB::table('service_bills')->insert(
                                        array(
                                          'service_id' =>$service_id,
                                        'customer_id' =>$customer_id,
                                        'invoice_no' =>$invoice_ids,
                                        'size' => $all_size[0],
                                        'quantity' => $all_quantity[0],
                                        'type' => $all_type[0],
                                        'bal_status' => 1,
                                        'amount' => $single_bal_amt,
                                        'delivery_date' => $all_delivery_date[0],
                                       'company_id' => Auth::user()->company_id,
                                        'bill_createdBy' => Auth::user()->username,
                                        'bill_date' => date('Y-m-d'),
                                         'created_at' => date('Y-m-d H:i:s'),
                                        
                                                ));
                                      // dd('single inserted',$single,$single_amt,$singlepaid_amt,$single_bal_amt);



                            }else{
                                       // dd($result1,$request->amountRecieved);
                                        
                                        if($request->amountRecieved <= $all_amt[0] ){
                                          $first_bill_id = current($all_ids);
                                          $first_bill_paid = $request->amountRecieved;
                                          $first_bill_bal = $all_amt[0] - $request->amountRecieved;
                                         // dd('kkkk',$first_bill_bal,$first_bill_paid,$all_amt[0]);
                                          $result=DB::table('service_payment')->insert(
                                            array(
                                              'service_id' =>$service_id,
                                            'customer_id' =>$customer_id,
                                            'invoice_no' =>$invoice_ids,
                                            'company_id' => Auth::user()->company_id,
                                             'bill_id' => $all_ids[0],
                                             'quantity' => $all_quantity[0],
                                             'size' => $all_size[0],
                                             'amount' => $first_bill_paid,
                                             'delivery_date' => $all_delivery_date[0],
                                             'payment_date' => date('Y-m-d'),
                                             'created_at' => date('Y-m-d H:i:s'),
                                            
                                                    ));

                                            $result=DB::table('service_bills')->insert(
                                              array(
                                                'service_id' =>$service_id,
                                              'customer_id' =>$customer_id,
                                              'invoice_no' =>$invoice_ids,
                                              'size' => $all_size[0],
                                              'quantity' => $all_quantity[0],
                                              'type' => $all_type[0],
                                              'bal_status' => 1,
                                              'amount' => $first_bill_bal,
                                              'delivery_date' => $all_delivery_date[0],
                                             'company_id' => Auth::user()->company_id,
                                              'bill_createdBy' => Auth::user()->username,
                                              'bill_date' => date('Y-m-d'),
                                               'created_at' => date('Y-m-d H:i:s'),
                                              
                                                      ));
                                           // dd($first_bill_bal);
                                        }else{
                                          $tot_mor_amt=0;
                                          $mor_tot_amt=0;
                                         // dd($result1);
                                          foreach ($result1 as $key => $got) {
                                            $tot_mor_amt += $got->amount;
                                            if($request->amountRecieved >= $tot_mor_amt ){
                                                $mor_bill_id[]=$got->id;
                                                $mor_bill_qty[]=$got->quantity;
                                                $mor_bill_size[]=$got->size;
                                                $mor_bill_amt[]=$got->amount;
                                                $mor_tot_amt +=$got->amount;
                                                $mor_bill_deliver_dt[]=$got->delivery_date;
                                              }
                                          }

                                          
                                          $bal_bill_ids=array_diff($all_ids,$mor_bill_id);
                                          $first_bal_bill_id = current($bal_bill_ids);
                                          $difference_amt=DB::table('service_bills')->where('id',$first_bal_bill_id)->first();
                                          $push_one_id = $difference_amt->id;
                                          $push_one_qty = $difference_amt->quantity;
                                          $push_one_size = $difference_amt->size;
                                          $push_one_delivery_date = $difference_amt->delivery_date;
                                          //dd($request->amountRecieved,$mor_tot_amt);
                                          $push_one_amt= $request->amountRecieved - $mor_tot_amt;
                                          $bal_one_amt = $difference_amt->amount - $push_one_amt;
                                          array_push($mor_bill_amt,$push_one_amt);
                                          array_push($mor_bill_id,$push_one_id);
                                          array_push($mor_bill_qty,$push_one_qty);
                                          array_push($mor_bill_size,$push_one_size);
                                          array_push($mor_bill_deliver_dt,$push_one_delivery_date);

                                          $cound = count($mor_bill_id);

                                          for ($i = 0; $i < $cound; $i++) {
                                            $result123=DB::table('service_payment')->insert(
                                                array(
                                                  'service_id' =>$service_id,
                                                'customer_id' =>$customer_id,
                                                'invoice_no' =>$invoice_ids,
                                                'company_id' => Auth::user()->company_id,
                                                 'bill_id' => $mor_bill_id[$i],
                                                 'size' => $mor_bill_size[$i],
                                                 'quantity' => $mor_bill_qty[$i],
                                                 'amount' => $mor_bill_amt[$i],
                                                 'delivery_date' => $mor_bill_deliver_dt[$i],
                                                 'payment_date' => date('Y-m-d'),
                                                 'created_at' => date('Y-m-d H:i:s'),
                                                
                                                        ));
                                            }

                                             $result234=DB::table('service_bills')->insert(
                                                    array(
                                                      'service_id' =>$service_id,
                                                    'customer_id' =>$customer_id,
                                                    'invoice_no' =>$invoice_ids,
                                                    'size' => $push_one_size,
                                                    'quantity' => $push_one_qty,
                                                    'bal_status' => 1,
                                                    'type' => $type,
                                                    'amount' => $bal_one_amt,
                                                    'delivery_date' => $push_one_delivery_date,
                                                   'company_id' => Auth::user()->company_id,
                                                    'bill_createdBy' => Auth::user()->username,
                                                    'bill_date' => date('Y-m-d'),
                                                     'created_at' => date('Y-m-d H:i:s'),
                                                    
                                                            ));


                                         // dd('hhhhhhhh',$mor_bill_amt,$mor_bill_id,$mor_bill_qty,$mor_bill_size,$mor_bill_deliver_dt,$push_one_delivery_date,$request->amountRecieved,$mor_bill_amt);
                                          
                                        }
                            }

                            
        }else{

                             $result2=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();

                            foreach ($result2 as $key => $res) {

                            $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                    // dd('less than');       
          
        }


       
        $company=\DB::table('companies')->where('id', Auth::user()->company_id)->first();
        //dd($company);
        $bal_status=1;
        $report = DB::table('service_bills')->select('service_bills.*')
            //->where('service_bills.service_id',$service_id)
            ->where('service_bills.invoice_no',$invoice_ids)
            ->where('service_bills.type',$type)
            ->where('service_bills.bal_status','!=',$bal_status)
            ->where('service_bills.company_id',Auth::user()->company_id)->get();
         //dd($report); 
        $invoice_details = DB::table('serv_invoice_nos')->where('invoice_no',$invoice_ids)
        ->where('type',$type)->where('company_id',Auth::user()->company_id)->first();
           // dd($invoice_details,$report);
         $amt_received = $request->amountRecieved;
       $customer = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->latest('id')->first();
        return view('backend.pdfbill.studiobill', compact('report','invoice_details','company','customer','amt_received'));
    }
    public function studiobillstore(Request $request)
    {
        $input = \Request::all();
      // dd('new',$input);
       $type='studio';
       $company_id1= Auth::user()->company_id;
       if($request->customer_id == 'no' )
       {
        // Insert Customer
                  $customerObj = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->select('customer_id')->latest('id')->first();
                  //$customerObj = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->select('customer_id')->latest('id')->first();
                       if ($customerObj) {
                            $orderNr = $customerObj->customer_id;
                            $removed1char = substr($orderNr, 1);
                            $customer_id = $stpad = '#' . str_pad($removed1char + 1, 7, "0", STR_PAD_LEFT);
                        } else {
                            $customer_id = '#'.$company_id1 . str_pad(1, 7, "0", STR_PAD_LEFT);
                        }
    //dd($customer_id,'new');
                    $message1 =  DB::table('ser_customers')->insert(
                            array(
                        'client_name' => $request->name,
                        'customer_id' => $customer_id,
                        'type' => $type,
                        'phone' => $request->mobile,
                        'address' => $request->address,
                        'company_id' => Auth::user()->company_id,
                        'email' => $request->email,
                        'dates' => date("Y-m-d"),
                        'created_at' => date('Y-m-d H:i:s'),
                            ));

    //user name and password creation
                $role = \DB::table('roles')->where('company_id', Auth::user()->company_id)->where('name','=', 'Customer')->first();
//dd($role);
                      $message = User::create([
                          'name' => $request->name,
                          'email' => $request->email,
                          'username' => $customer_id,
                          'hint_password' => $request->mobile,
                         // 'type' => 'user',
                          'password' => bcrypt($request->mobile),
                          'company_id' => Auth::user()->company_id,
                          'salary' => '1',
                          'created_at' => date('Y-m-d H:i:s'),
                      ]);
                        if ($message) {
                            UserRole::create([
                                'role_id' => $role->id,
                                'company_id' => Auth::user()->company_id,
                                'user_id' => $message->id
                            ]);
                          }

    }else{
                    $customer_id = $request->customer_id;
                   // dd('old',$customer_id);
       }
        

       // dd($customer_id);
        // Invoice Id

             $companyname=\DB::table('companies')->where('id', Auth::user()->company_id)->select('company_name')->first();
            //dd($companyname);
            $invoice_company_name=str_replace(" ","",$companyname->company_name);
            $companyname=substr($invoice_company_name, 0, 3);
            $check_max_invoice_no=\DB::table('serv_invoice_nos')->where('company_id', Auth::user()->company_id)->where('type',$type)->orderBy('id', 'desc')->first();
            //dd($check_max_invoice_no);
            if($check_max_invoice_no)
            {
                $companyid=(Auth::user()->company_id);
                $replacedata=$companyname.'STU'.$companyid;

                $invoiceid=str_replace($replacedata,'',$check_max_invoice_no->id)+1;
               // dd($replacedata,$check_max_invoice_no->invoice_no);
                $invoicelen=4-strlen($invoiceid);
                //dd($invoiceid);
                $finalid='';
                if($invoicelen != 0){
                    for($i=0;$i<$invoicelen;$i++)
                    {
                        if($i==0)
                        {
                             $finalid='0'.$invoiceid;   
                        }else
                        {
                            $finalid='0'.$finalid;
                        }
                    }

                }else{
                    $finalid=$invoiceid;
                }
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.$finalid;
            }
            else
            {
                $request['invoice_id']=$companyname.'-STU-'.Auth::user()->company_id.'0001';
                $invoice=$request['invoice_id'];
            }
             $invoice_ids=$request['invoice_id'];


$customerObj1 = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->select('customer_id')->latest('id')->first();
            $compay_id=Auth::user()->company_id;
            if ($customerObj1) {
                  $orderNr = $customerObj1->customer_id;
                  $removed1char = substr($orderNr, 1);
                  $service_id = 'SO-' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
              } else {
                  $service_id = 'SO-'.$compay_id . str_pad(1, 8, "0", STR_PAD_LEFT);
              }
              
              if($request->amountRecieved > $request->subtot){
                 $due_amt = 0;
                 $received =$request->subtot;
              }else{
                $due_amt =$request->due_amount;
                $received =$request->amountRecieved;
              }
            //dd($invoice_ids,$service_id);
              DB::table('serv_invoice_nos')->insert([
                'service_id' => $service_id,
                'customer_id' => $customer_id,
                'invoice_no' => $invoice_ids,
                'subtotal' => $request->subtot,
                'payment_mode' => $request->pmMode,
                'type' => $type,
                'cheq_no' => $request->cheqno,
                'cheq_dt' => $request->cheqdate,
                'bank_name' => $request->bank_name,
                'transaction_no' => $request->trans_no,
                'online_bank_name' => $request->bank_name1,
                'company_id' => Auth::user()->company_id,
                 'paid_amt' => $received,
                'due_amt' => $due_amt,
                'inv_dt' => date('Y-m-d'),
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                
                ]);
            

             
             //dd($request->due_amount,$input,$request->input('total_photo')) ;
             // dd($service_id);
         for ($i = 0; $i < $request->input('total_photo'); $i++) {
        $result=DB::table('service_bills')->insert(
            array(
              'service_id' =>$service_id,
            'customer_id' =>$customer_id,
            'invoice_no' =>$invoice_ids,
            'size' => $request['sizes'][$i],
            'quantity' => $request['qty'][$i],
            'type' => $type,
            'amount' => $request['amt'][$i],
            'delivery_date' => $request['deliverd_date'][$i],
           'company_id' => Auth::user()->company_id,
            'bill_createdBy' => Auth::user()->username,
            'bill_date' => date('Y-m-d'),
             'created_at' => date('Y-m-d H:i:s'),
            
                    ));
        }

        $sub_totals = $request->subtot;
        $tot_paids = $request->amountRecieved;
// SUB TOTAL IS EQUAL TO PAID AMOUNT

  if($sub_totals == $tot_paids){
          $result1=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();

                            foreach ($result1 as $key => $res) {

                            $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                           // dd('equal');
// SUB TOTAL IS EQUAL TO PAID AMOUNT
  }elseif($sub_totals > $tot_paids){
                            $result1=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();
                            $tot_all_amt=0;
                            $bill_tot_amount=0;
                            foreach ($result1 as $key => $res1) {
                            $all_ids[]=$res1->id;
                            $all_amt[] = $res1->amount;
                            $all_size[] = $res1->size;
                            $all_type[] = $res1->type;
                            $all_quantity[] = $res1->quantity;
                            $all_delivery_date[] = $res1->delivery_date;

                          
                            }
                            
                            $single_id = count($all_ids);
                            if($single_id == 1){
                                      $single=DB::table('service_bills')->where('id',$all_ids[0])->first();
                                      $single_amt=$single->amount;
                                      $singlepaid_amt = $request->amountRecieved;
                                      $single_bal_amt = $single_amt - $singlepaid_amt;
                                     

                                      $result=DB::table('service_payment')->insert(
                                      array(
                                        'service_id' =>$service_id,
                                      'customer_id' =>$customer_id,
                                      'invoice_no' =>$invoice_ids,
                                      'company_id' => Auth::user()->company_id,
                                       'bill_id' => $all_ids[0],
                                       'quantity' => $all_quantity[0],
                                       'size' => $all_size[0],
                                       'amount' => $singlepaid_amt,
                                       'delivery_date' => $all_delivery_date[0],
                                       'payment_date' => date('Y-m-d'),
                                       'created_at' => date('Y-m-d H:i:s'),
                                      
                                              ));

                                      $result=DB::table('service_bills')->insert(
                                        array(
                                          'service_id' =>$service_id,
                                        'customer_id' =>$customer_id,
                                        'invoice_no' =>$invoice_ids,
                                        'size' => $all_size[0],
                                        'quantity' => $all_quantity[0],
                                        'type' => $all_type[0],
                                        'bal_status' => 1,
                                        'amount' => $single_bal_amt,
                                        'delivery_date' => $all_delivery_date[0],
                                       'company_id' => Auth::user()->company_id,
                                        'bill_createdBy' => Auth::user()->username,
                                        'bill_date' => date('Y-m-d'),
                                         'created_at' => date('Y-m-d H:i:s'),
                                        
                                                ));
                                      // dd('single inserted',$single,$single_amt,$singlepaid_amt,$single_bal_amt);



                            }else{
                                       // dd($result1,$request->amountRecieved);
                                        
                                        if($request->amountRecieved <= $all_amt[0] ){
                                          $first_bill_id = current($all_ids);
                                          $first_bill_paid = $request->amountRecieved;
                                          $first_bill_bal = $all_amt[0] - $request->amountRecieved;
                                         // dd('kkkk',$first_bill_bal,$first_bill_paid,$all_amt[0]);
                                          $result=DB::table('service_payment')->insert(
                                            array(
                                              'service_id' =>$service_id,
                                            'customer_id' =>$customer_id,
                                            'invoice_no' =>$invoice_ids,
                                            'company_id' => Auth::user()->company_id,
                                             'bill_id' => $all_ids[0],
                                             'quantity' => $all_quantity[0],
                                             'size' => $all_size[0],
                                             'amount' => $first_bill_paid,
                                             'delivery_date' => $all_delivery_date[0],
                                             'payment_date' => date('Y-m-d'),
                                             'created_at' => date('Y-m-d H:i:s'),
                                            
                                                    ));

                                            $result=DB::table('service_bills')->insert(
                                              array(
                                                'service_id' =>$service_id,
                                              'customer_id' =>$customer_id,
                                              'invoice_no' =>$invoice_ids,
                                              'size' => $all_size[0],
                                              'quantity' => $all_quantity[0],
                                              'type' => $all_type[0],
                                              'bal_status' => 1,
                                              'amount' => $first_bill_bal,
                                              'delivery_date' => $all_delivery_date[0],
                                             'company_id' => Auth::user()->company_id,
                                              'bill_createdBy' => Auth::user()->username,
                                              'bill_date' => date('Y-m-d'),
                                               'created_at' => date('Y-m-d H:i:s'),
                                              
                                                      ));
                                           // dd($first_bill_bal);
                                        }else{
                                          $tot_mor_amt=0;
                                          $mor_tot_amt=0;
                                         // dd($result1);
                                          foreach ($result1 as $key => $got) {
                                            $tot_mor_amt += $got->amount;
                                            if($request->amountRecieved >= $tot_mor_amt ){
                                                $mor_bill_id[]=$got->id;
                                                $mor_bill_qty[]=$got->quantity;
                                                $mor_bill_size[]=$got->size;
                                                $mor_bill_amt[]=$got->amount;
                                                $mor_tot_amt +=$got->amount;
                                                $mor_bill_deliver_dt[]=$got->delivery_date;
                                              }
                                          }

                                          
                                          $bal_bill_ids=array_diff($all_ids,$mor_bill_id);
                                          $first_bal_bill_id = current($bal_bill_ids);
                                          $difference_amt=DB::table('service_bills')->where('id',$first_bal_bill_id)->first();
                                          $push_one_id = $difference_amt->id;
                                          $push_one_qty = $difference_amt->quantity;
                                          $push_one_size = $difference_amt->size;
                                          $push_one_delivery_date = $difference_amt->delivery_date;
                                          //dd($request->amountRecieved,$mor_tot_amt);
                                          $push_one_amt= $request->amountRecieved - $mor_tot_amt;
                                          $bal_one_amt = $difference_amt->amount - $push_one_amt;
                                          array_push($mor_bill_amt,$push_one_amt);
                                          array_push($mor_bill_id,$push_one_id);
                                          array_push($mor_bill_qty,$push_one_qty);
                                          array_push($mor_bill_size,$push_one_size);
                                          array_push($mor_bill_deliver_dt,$push_one_delivery_date);

                                          $cound = count($mor_bill_id);

                                          for ($i = 0; $i < $cound; $i++) {
                                            $result123=DB::table('service_payment')->insert(
                                                array(
                                                  'service_id' =>$service_id,
                                                'customer_id' =>$customer_id,
                                                'invoice_no' =>$invoice_ids,
                                                'company_id' => Auth::user()->company_id,
                                                 'bill_id' => $mor_bill_id[$i],
                                                 'size' => $mor_bill_size[$i],
                                                 'quantity' => $mor_bill_qty[$i],
                                                 'amount' => $mor_bill_amt[$i],
                                                 'delivery_date' => $mor_bill_deliver_dt[$i],
                                                 'payment_date' => date('Y-m-d'),
                                                 'created_at' => date('Y-m-d H:i:s'),
                                                
                                                        ));
                                            }

                                             $result234=DB::table('service_bills')->insert(
                                                    array(
                                                      'service_id' =>$service_id,
                                                    'customer_id' =>$customer_id,
                                                    'invoice_no' =>$invoice_ids,
                                                    'size' => $push_one_size,
                                                    'quantity' => $push_one_qty,
                                                    'bal_status' => 1,
                                                    'type' => $type,
                                                    'amount' => $bal_one_amt,
                                                    'delivery_date' => $push_one_delivery_date,
                                                   'company_id' => Auth::user()->company_id,
                                                    'bill_createdBy' => Auth::user()->username,
                                                    'bill_date' => date('Y-m-d'),
                                                     'created_at' => date('Y-m-d H:i:s'),
                                                    
                                                            ));


                                         // dd('hhhhhhhh',$mor_bill_amt,$mor_bill_id,$mor_bill_qty,$mor_bill_size,$mor_bill_deliver_dt,$push_one_delivery_date,$request->amountRecieved,$mor_bill_amt);
                                          
                                        }
                            }

                            
        }else{

                             $result2=DB::table('service_bills')->where('invoice_no',$invoice_ids)->get();

                            foreach ($result2 as $key => $res) {

                            $bill_ids[]=$res->id;
                            $bill_amt[] = $res->amount;
                            $bill_size[] = $res->size;
                            $bill_type[] = $res->type;
                            $bill_quantity[] = $res->quantity;
                            $bill_delivery_date[] = $res->delivery_date;
                            }
                           // dd($result1);
                              $countss= count($bill_ids);

                             for ($i = 0; $i < $countss; $i++) {
                              $result=DB::table('service_payment')->insert(
                              array(
                                'service_id' =>$service_id,
                              'customer_id' =>$customer_id,
                              'invoice_no' =>$invoice_ids,
                              'company_id' => Auth::user()->company_id,
                               'bill_id' => $bill_ids[$i],
                               'quantity' => $bill_quantity[$i],
                               'size' => $bill_size[$i],
                               'amount' => $bill_amt[$i],
                               'delivery_date' => $bill_delivery_date[$i],
                               'payment_date' => date('Y-m-d'),
                               'created_at' => date('Y-m-d H:i:s'),
                              
                                      ));
                          }
                    // dd('less than');       
          
        }


       
        $company=\DB::table('companies')->where('id', Auth::user()->company_id)->first();
        //dd($company);
        $bal_status=1;
        $report = DB::table('service_bills')->select('service_bills.*')
            ->where('service_bills.service_id',$service_id)
            ->where('service_bills.type',$type)
            ->where('service_bills.bal_status','!=',$bal_status)
            ->where('service_bills.company_id',Auth::user()->company_id)->get();
         //dd($report); 
        $invoice_details = DB::table('serv_invoice_nos')->where('service_id',$service_id)
        ->where('type',$type)->where('company_id',Auth::user()->company_id)->first();
           // dd($invoice_details,$report);
         $amt_received = $request->amountRecieved;
       $customer = \DB::table('ser_customers')->where('company_id', Auth::user()->company_id)->where('type',$type)->latest('id')->first();
        return view('backend.pdfbill.studiobill', compact('report','invoice_details','company','customer','amt_received'));
    }
}
