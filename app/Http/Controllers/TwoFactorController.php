<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    private $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function setup()
    {
        $user = Auth::user();
        $secret = $this->twoFactorService->generateSecretKey();
        $user->update(['two_factor_secret' => $secret]);
        
        $qrCode = $this->twoFactorService->getQRCodeUrl($user, $secret);
        
        return view('auth.2fa-setup', compact('qrCode', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        
        if ($this->twoFactorService->enable2FA(Auth::user(), $request->code)) {
            return redirect()->route('dashboard')->with('success', '2FA enabled successfully');
        }
        
        return back()->withErrors(['code' => 'Invalid verification code']);
    }

    public function verify()
    {
        return view('auth.2fa-verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $user = Auth::user();
        
        if ($this->twoFactorService->verifyCode($user->two_factor_secret, $request->code) ||
            $this->twoFactorService->verifyRecoveryCode($user, $request->code)) {
            session(['2fa_verified' => true]);
            return redirect()->intended('/dashboard');
        }
        
        return back()->withErrors(['code' => 'Invalid code']);
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);
        
        $this->twoFactorService->disable2FA(Auth::user());
        
        return back()->with('success', '2FA disabled successfully');
    }
}