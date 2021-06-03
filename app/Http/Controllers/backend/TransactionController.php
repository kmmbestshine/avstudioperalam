<?php

namespace App\Http\Controllers\backend;

use App\Models\Transaction;
use PDF;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkpermission('transaction-list');
        $transaction = Transaction::where('company_id',Auth::user()->company_id)->orderBy('created_at', 'DEC')->where('remainingamount', '>', 0)->get();
        $finaltransaction = Transaction::where('company_id',Auth::user()->company_id)->orderBy('created_at', 'DEC')->where('remainingamount', '<=', 0)->get();
        return view('backend.transaction.list', compact('transaction', 'finaltransaction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkpermission('transaction-create');
        return view('backend.transaction.create');
    }

public function createdepositaccount()
    {
       // dd('jjjjjj');
        return view('backend.transaction.online.depositcreate');
    }
    public function createfundtransfer()
    {
       // 
        $acc_list = \DB::table('deposits')->where('company_id', Auth::user()->company_id)->get();
       // dd('jjjjjj',$acc_list);
        return view('backend.transaction.online.createfundtransfer',compact('acc_list'));
    }
    public function fundtransferamountverify(Request $request)
    {
      $input = \Request::all();
    //dd($input);
     $from_account_id =$request->account_id;
      $name = $request->name;
      $mobile = $request->mobile;
      $bankname = $request->bankname;
      $accountno = $request->accountno;
      $branchname = $request->branchname;
      $amount = $request->amount;
      $ifsccode = $request->ifsccode;
     
        return view('backend.transaction.online.fundtransferverify', compact('from_account_id','name','mobile','bankname','accountno','branchname','amount','ifsccode'));
    }

    public function fundtransferamountstore(Request $request)
    {
          $input = \Request::all();
         
         // Product Code No Exist Return
       
        $message=DB::table('fund_transfer')->insert([
            
            'from_account_id' => $request->from_account_id,
            'name' => $request->name,
            'company_id' => Auth::user()->company_id,
            'bankname' => $request->bankname,
            'accountno' => $request->accountno,
            'mobile' => $request->mobile,
            'amount' => $request->amount,
            'branchname' => $request->branchname,
            'ifsccode' => $request->ifsccode,
            'date' => date('Y-m-d'),
            'commision' => $request->commision,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
       // dd($input,Auth::user()->company_id);
        if ($message) {
            return redirect()->route('online.fund.transfer')->with('success_message', 'successfully created ');
        } else {
            return redirect()->route('product.create')->with('error_message', 'Failed To create');
        }
    }
    public function viewfundtransferamount()
    {
        $amt_list = \DB::table('fund_transfer')->where('company_id', Auth::user()->company_id)->get();
      //dd('list',$amt_list);
        return view('backend.transaction.online.fundtransferlist',compact('amt_list'));
    }
    public function depositamountstore(Request $request)
    {
          $input = \Request::all();
          
        $this->validate($request, [
           
            'name' => 'required',
            'bankname' => 'required',
            'accountno' => 'required',
            'branchname' => 'required',
            'amount' => 'required',
            'ifsccode' => 'required',
        ]);

         // Product Code No Exist Return
       
        $message=DB::table('deposits')->insert([
            
            'name' => $request->name,
            'company_id' => Auth::user()->company_id,
            'bankname' => $request->bankname,
            'account_no' => $request->accountno,
            'deposit_amt' => $request->amount,
            'available_amt' => $request->amount,
            'branchname' => $request->branchname,
            'ifsc' => $request->ifsccode,
            'date' => date('Y-m-d'),
            'status' => $request->status,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
       // dd($input,Auth::user()->company_id);
        if ($message) {
            return redirect()->route('online.deposit.account')->with('success_message', 'successfully created ');
        } else {
            return redirect()->route('product.create')->with('error_message', 'Failed To create');
        }
    }

    public function viewdepositamount()
    {
        $amt_list = \DB::table('deposits')->where('company_id', Auth::user()->company_id)->get();
      //dd('list',$amt_list);
        return view('backend.transaction.online.list',compact('amt_list'));
    }
    public function depositupdate($id)
    {
        $amt_list = \DB::table('deposits')->where('company_id',Auth::user()->company_id)->find($id);
        //dd($amt_list);
        return view('backend.transaction.online.depositupdate', compact('amt_list'));
    }
    public function postdepositupdate(Request $request, $id)
    {
        $this->validate($request, [
            'deposit' => 'required',
        ]);
        $pc = \DB::table('deposits')->where('company_id',Auth::user()->company_id)->find($id);
       // dd($pc);
        $available_amts = $pc->available_amt + $request->deposit;
        $deposit_amts = $pc->deposit_amt + $request->deposit;

        $message =  DB::table('deposits')->where('id',$id)->update(
                array(
            
            'available_amt' => $available_amts,
            'deposit_amt' => $deposit_amts,
            
                ));
        if ($message) {
            return redirect()->route('deposit.amount.list')->with('success_message', 'successfully updated Your Deposit Amount');
        } else {
            return redirect()->route('deposit.amount.edit')->with('error_message', 'failed to  update');
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
        $this->validate($request, [
            'totalamount' => 'required',
            'depositeamount' => 'required',
            'deposite_by' => 'required',
            'deposite_date' => 'required',
            'bank_name' => 'required',
        ]);
        $message = Transaction::create([
            'totalamount' => $request->totalamount,
            'depositeamount' => $request->depositeamount,
            'remainingamount' => $request->totalamount - $request->depositeamount,
            'deposite_by' => $request->deposite_by,
            'company_id' => Auth::user()->company_id,
            'deposite_date' => $request->deposite_date,
            'bank_name' => $request->bank_name,
            'created_by' => Auth::user()->username,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($message) {
            return back()->with('success_message', 'Success Fully created');
        } else {
            return back()->with('error_message', 'Failed To create');
        }
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
        $check = $this->checkpermission('transaction-update');
        if ($check) {
            $this->checkpermission('purchase-update');
        } else {
            $purchase = Transaction::where('company_id',Auth::user()->company_id)->find($id);
            $purchase->remainingamount = 0;
            $purchase->depositeamount = $purchase->totalamount;
            $purchase->update();
            return redirect()->back()->with('success_message', 'successfully paid Remining Balance');
        }
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

    public function export()
    {
        $alltransaction = Transaction::where('company_id',Auth::user()->company_id)->orderBy('deposite_date', 'DEC')->get();
        $pdf = PDF::loadview('backend.pdfbill.transaction', compact('alltransaction'));
        return $pdf->download('transaction.pdf');
    }
}
