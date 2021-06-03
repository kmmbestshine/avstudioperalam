<?php

namespace App\Http\Controllers\backend;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Salescart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\Module;
use Session;
use PDF;

class SalesController extends Controller
{
    public function create()
    {
        $this->checkpermission('sales-create');
        $salescart = Salescart::where('company_id',Auth::user()->company_id)->get();
        return view('backend.sales.create', compact('salescart'));
    }

    public function store(Request $request)
    {
       // $this->checkpermission('salescarts-create');
        $this->validate($request, [
            'product_id' => 'required',
            'price' => 'required',
            'sales_quantity' => 'required',
        ]);
        if ($request->ajax()) {
            $sales = new Salescart();
            $sales->product_id = $request->product_id;
            $sales->customer_id = $request->customer_id;
            $sales->quantity = $request->sales_quantity;
            $sales->price = $request->price * $request->sales_quantity;
            $sales->sales_status = $request->sales_status;
            $sales->company_id = Auth::user()->company_id;
            $sales->saller_name = Auth::user()->username;
            $sales->sales_date = date('Y-m-d');
            $sales->warenty_end =  $request->warentyEnd;
            if ($sales->save()) {
                $product = Product::where('company_id',Auth::user()->company_id)->find($request->product_id);
                $product->stock = $product->stock - $request->sales_quantity;
                if ($product->update()) {
                    return response(['success_message' => 'SuccessFully Make sales']);
                }
            }

        } else {
            return response(['error_message' => 'Filed To Make sales']);
        }
    }
    public function gettaxrate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->where('company_id',Auth::user()->company_id)->get();
       // dd('jjj');
        echo $product[0]->gst_percent;

    }

    public function index()
    {
        $this->checkpermission('sales-list');
       
        $sales = Sale::join('products', 'products.id', '=', 'sales.product_id')
            ->select('sales.*', 'products.name')
            ->orderBy('sales.created_at', 'DEC')
            ->where('sales.company_id',Auth::user()->company_id)
            ->get();
        return view('backend.sales.list', compact('sales'));
    }

    public function ajaxlist()
    {
        $sales = Salescart::join('products', 'products.id', '=', 'salescarts.product_id')
            ->select('salescarts.*', 'products.name','products.gst_percent')
            ->orderBy('salescarts.created_at', 'DEC')
            ->where('salescarts.company_id',Auth::user()->company_id)
            ->get();
        return view('backend.sales.ajaxlist', compact('sales'));
    }

    public function ajaxform()
    {
       // $salescart = Salescart::all();
        $salescart = Salescart::join('products', 'products.id', '=', 'salescarts.product_id')
            ->select('salescarts.*', 'products.name','products.gst_percent')
            ->orderBy('salescarts.created_at', 'DEC')
            ->where('salescarts.company_id',Auth::user()->company_id)
            ->get();
        return view('backend.sales.ajaxform', compact('salescart'));
    }

    public function refreshproduct()
    {
        $product = Product::where('stock', '>=', 1)->where('company_id',Auth::user()->company_id)->get();
        return view('backend.sales.refreshproduct', compact('product'));
    }

    public function getquantity(Request $request)
    {
        $product = Product::where('id', $request->product_id)->where('company_id',Auth::user()->company_id)->get();
        echo $product[0]->stock;

    }

    public function getprice(Request $request)
    {
        $product = Product::where('id', $request->product_id)->where('company_id',Auth::user()->company_id)->get();
        echo $product[0]->price;
    }

    public function getproductname(Request $request)
    {
        $product = Product::where('id', $request->product_id)->where('company_id',Auth::user()->company_id)->get();
        echo $product[0]->name;
    }

    public function getallpdf()
    {
        $report = Salescart::join('products', 'products.id', '=', 'salescarts.product_id')
            ->select('salescarts.*', 'products.name')
            ->where('salescarts.company_id',Auth::user()->company_id)
            ->get();
        return view('backend.pdfbill.salesbill', compact('report'));
    }

    public function getcustomreport(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $report = Sale::join('products', 'products.id', 'sales.product_id')
            ->select('sales.*', 'products.name')
            ->whereBetween('sales.sales_date', [$start, $end])
            ->where('sales.company_id',Auth::user()->company_id)
            ->get();
        $pdf = PDF::loadview('backend.pdfbill.allreport', compact('report', 'start', 'end'));
        return $pdf->download('salesreport.pdf');
    }

    public function savetosales(Request $request)
    {
        $input = \Request::all();
     
        // Invoice Id

             $companyname=\DB::table('companies')->where('id', Auth::user()->company_id)->select('company_name')->first();
            //dd($companyname);
            $invoice_company_name=str_replace(" ","",$companyname->company_name);
            $companyname=substr($invoice_company_name, 0, 3);
            $check_max_invoice_no=\DB::table('invoice_nos')->where('company_id', Auth::user()->company_id)->orderBy('id', 'desc')->first();
            //dd($check_max_invoice_no);
            if($check_max_invoice_no)
            {
                $companyid=(Auth::user()->company_id);
                $replacedata=$companyname.'REC'.$companyid;
                $invoiceid=str_replace($replacedata,'',$check_max_invoice_no->invoice_id)+1;
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
                $request['invoice_id']=$companyname.'REC'.Auth::user()->company_id.$finalid;
            }
            else
            {
                $request['invoice_id']=$companyname.'REC'.Auth::user()->company_id.'0001';
                $invoice=$request['invoice_id'];
            }
             $invoice_ids=$request['invoice_id'];

             //dd($request['invoice_id']);
         
         if($request->due_amount == "NaN")
         {
            $due_amount=0;
         }else{
            $due_amount=$request->due_amount;
         }

        // dd($request->customer_id[0]);

         if($request->customer_id[0]){
            $customer_id = $request->customer_id[0];
         }else{
            $customer_id = 0;
         }
       
           // dd($inv_ids,$request->subtot,$request->gsttotal,$request->grandtotal,$request->amountdiscount,$request->amountRecieved,$due_amount);

              DB::table('invoice_nos')->insert([
                
                'customer_id' => $customer_id,
                'invoice_id' => $invoice_ids,
                'subtotal' => $request->subtot,
                'gst_total' => $request->gsttotal,
                'grand_total' => $request->grandtotal,
                'discount_amt' => $request->amountdiscount,
                'company_id' => Auth::user()->company_id,
                 'paid_amt' => $request->amountRecieved,
                'due_amt' => $due_amount,
                 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                
                ]);
            //  dd($inv_ids);
              if($request->salesdate){
                $sales_date=$request->salesdate;
              }else{
                $sales_date=date('Y-m-d');
              }
             //dd() 
         for ($i = 0; $i < $request->input('total_product'); $i++) {
        $result=DB::table('sales')->insert(
            array(
            'customer_id' =>$customer_id,
            'invoice_id' =>$invoice_ids,
            'product_id' => $request['product_id'][$i],
            'quantity' => $request['quantity'][$i],
            'price' => $request['price'][$i],
            'sales_status' => $request['sales_status'][$i],
            'warenty_end' => $request['warentyEnd'][$i],
           'company_id' => Auth::user()->company_id,
            'saller_name' => Auth::user()->username,
            'sales_date' => $sales_date,
            // 'gst_rate' => $request['taxvalue'][$i],
             'created_at' => date('Y-m-d H:i:s'),
            
                    ));
        }
        
       
        $company=\DB::table('companies')->where('id', Auth::user()->company_id)->first();
        $report = Sale::join('products', 'products.id', '=', 'sales.product_id')
            ->select('sales.*', 'products.name', 'products.gst_percent')
            ->where('sales.invoice_id',$invoice_ids)
            ->where('sales.company_id',Auth::user()->company_id)->get();
        $invoice_details = DB::table('invoice_nos')->where('invoice_id',$invoice_ids)
        ->where('company_id',Auth::user()->company_id)->first();
           // dd($invoice_details,$report);
        DB::table('salescarts')->delete();
        return view('backend.pdfbill.salesbill2', compact('report','invoice_details','company'));
    }

    public function deletecart($id, $pid)
    {
        $product = Product::where('company_id',Auth::user()->company_id)->find($pid);
        $salescart = Salescart::where('company_id',Auth::user()->company_id)->find($id);
        $product->stock = $product->stock + $salescart->quantity;
        if ($product->update()) {
            $salescart->delete();
            return redirect()->back()->with('success_message', 'Seccessfully deleted Item');
        }else {
            return redirect()->back()->with('error_messsage', 'Failed To Delete Item');
        }
    }
}
