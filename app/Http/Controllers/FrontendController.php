<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Time;
use App\User;

class FrontendController extends Controller
{
    public function index()
    {
        if (request('date')) {
            $appointments = $this->findAvailableDoctors(request('date'));
            return view('welcome', compact('appointments'));
        }

        $date = '2020-12-30'; //date('Y-m-d')

        $appointments = Appointment::where('date', $date)->get();
        return view('welcome', compact('appointments'));
    }

    public function show(User $doctor, $date)
    {
        $appointment = Appointment::where('user_id', $doctor->id)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();

        return view('appointment', compact('doctor', 'appointment', 'times', 'date'));
    }

    public function findAvailableDoctors($date)
    {
        $doctors = Appointment::where('date', $date)->get();
        return $doctors;
    }
}
