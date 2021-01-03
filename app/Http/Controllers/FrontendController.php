<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Booking;
use App\Mail\AppointmentMail;
use App\Time;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function index()
    {
        if (request('date')) {
            $appointments = $this->findAvailableDoctors(request('date'));
            return view('welcome', compact('appointments'));
        }

        $date = date('Y-m-d');

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

        $doctor = User::where('id', $request->doctorId)->first();

        $data = [
            'name' => auth()->user()->name,
            'date' => $request->date,
            'time' => $request->time,
            'doctor' => $doctor->name,
        ];

        $this->sendEmailNotification($data);

        return redirect()->back()->with('message', 'Your appointment has been booked!');
    }

    public function myBookings()
    {
        $appointments = Booking::latest()
            ->where('user_id', auth()->id())
            ->get();

        return view('booking.index', compact('appointments'));
    }

    public function availableDoctors(Request $request)
    {
        return Appointment::with('doctor')->whereDate('date', date('Y-m-d'))
            ->get();
    }

    public function findDoctors(Request $request)
    {
        return Appointment::with('doctor')->whereDate('date', $request->date)
            ->get();
    }

    private function sendEmailNotification($data)
    {
        try {
            Mail::to(auth()->user()->email)->send(new AppointmentMail($data));
        } catch (\Exception $e) {

        }
    }

    private function findAvailableDoctors($date)
    {
        return Appointment::where('date', $date)->get();
    }

    private function checkBookingTimeInterval()
    {
        return Booking::latest()
            ->where('user_id', auth()->id())
            ->whereDate('created_at', date('Y-m-d'))
            ->exists();
    }
}
