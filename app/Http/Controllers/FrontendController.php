<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Booking;
use App\Time;
use App\User;
use Illuminate\Http\Request;

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

    public function showAppointment(User $doctor, $date)
    {
        $appointment = Appointment::where('user_id', $doctor->id)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();

        return view('appointment', compact('doctor', 'times', 'date'));
    }

    public function storeAppointment(Request $request)
    {
        $this->validate($request, [
            'time' => 'required',
        ]);

        if ($this->checkBookingTimeInterval()) {
            return redirect()->back()->with('error_message', 'You already made an appointment!');
        }

        Booking::create([
            'user_id' => auth()->id(),
            'doctor_id' => $request->doctorId,
            'time' => $request->time,
            'date' => $request->date,
        ]);

        Time::where('appointment_id', $request->appointmentId)
            ->where('time', $request->time)
            ->update(['status' => 1]);

        return redirect()->back()->with('message', 'Your appointment has been booked!');
    }

    private function findAvailableDoctors($date)
    {
        $doctors = Appointment::where('date', $date)->get();
        return $doctors;
    }

    private function checkBookingTimeInterval()
    {
        return Booking::latest()
            ->where('user_id', auth()->id())
            ->whereDate('created_at', date('Y-m-d'))
            ->exists();
    }
}
