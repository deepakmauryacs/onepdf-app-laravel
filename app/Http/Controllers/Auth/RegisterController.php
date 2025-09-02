<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Inline validation (no FormRequest class needed)
        $validated = $request->validate([
            'first_name'   => ['required','string','max:255'],
            'last_name'    => ['required','string','max:255'],
            'country'      => ['required','string','max:255'],
            'company'      => ['required','string','max:255'],
            'plan_id'      => ['required','in:1,2,3,4,5'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:6'],
            'agreed_terms' => ['accepted'], // checkbox must be checked
        ], [
            'agreed_terms.accepted' => 'Terms must be accepted',
        ]);

        try {
            DB::beginTransaction();

            // Generate use_id (16 digits)
            $useId = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT)
                   . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

            $user = User::create([
                'use_id'       => $useId,
                'country'      => $validated['country'],
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'company'      => $validated['company'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']),
                'agreed_terms' => true,
            ]);

            $planId = (int) $validated['plan_id'];
            $start  = Carbon::today();
            $end    = null;

            if (in_array($planId, [2,4], true)) {
                $end = $start->copy()->addMonth()->toDateString();
            } elseif (in_array($planId, [3,5], true)) {
                $end = $start->copy()->addYear()->toDateString();
            }

            DB::table('user_plans')->insert([
                'user_id'    => $user->id,
                'plan_id'    => $planId,
                'start_date' => $start->toDateString(),
                'end_date'   => $end,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            DB::rollBack();
            throw $ve;
        } catch (\Throwable $e) {
            DB::rollBack();

            // Map unique email error to your old API message
            if ($e instanceof \Illuminate\Database\QueryException &&
                str_contains(strtolower($e->getMessage()), 'unique') &&
                str_contains(strtolower($e->getMessage()), 'users_email_unique')) {
                return response()->json(['error' => 'Email already exists'], 422);
            }

            return response()->json(['error' => 'Registration failed'], 500);
        }
    }
}
