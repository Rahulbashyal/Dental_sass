<?php

namespace Modules\Financials\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['patient', 'invoice'])
            ->orderBy('paid_at', 'desc')
            ->paginate(15);
            
        return view('financials::payments.index', compact('payments'));
    }
}
