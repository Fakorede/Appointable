<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $bookings = Booking::latest()
            ->where('date', date('Y-m-d'))
            ->where('doctor_id', auth()->id())
            ->where('status', 1)
            ->get();

        return view('prescription.index', compact('bookings'));
    }

    public function allPresriptions()
    {
        $prescriptions = Prescription::with(['user', 'doctor'])->get();
        return view('prescription.all', compact('prescriptions'));
    }

    public function myPrescriptions()
    {
        $prescriptions = Prescription::where('user_id', auth()->id())
            ->get();

        return view('prescription.my-prescription', compact('prescriptions'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['medicine'] = implode(',', $request->medicine);

        Prescription::create($data);

        return redirect()->back()->with('message', 'Prescription created successfully!');
    }

    public function show($userId, $date)
    {
        $prescription = Prescription::where('user_id', $userId)
            ->where('date', $date)
            ->first();

        return view('prescription.show', compact('prescription'));
    }
}
