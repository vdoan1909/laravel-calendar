<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    const PATH_VIEW = 'lecturer.';

    public function index()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(Request $request)
    {
        try {
            $dayOfWeek = Carbon::parse($request->start)->dayOfWeek;
            $selectedDate = Carbon::parse($request->start);

            $title = $request->title;
            $startTime = $request->startTime;
            $endTime = $request->endTime;
            $description = $request->description;

            $now = Carbon::now();
            $lastDay = $now->copy()->endOfMonth();

            $schedules = [];

            // \Log::info($dayOfWeek);
            // \Log::info($selectedDate);

            $firstOccurrence = $selectedDate->copy();
            if ($selectedDate->dayOfWeek !== $dayOfWeek) {
                $firstOccurrence = $selectedDate->copy()->next($dayOfWeek);
            }

            for ($day = $firstOccurrence; $day->lte($lastDay); $day->addWeek()) {
                $schedules[] = [
                    'title' => $title,
                    'lecturer_id' => auth()->id(),
                    'start' => $day->toDateString(),
                    'day_of_week' => $day->dayOfWeek,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Schedule::insert($schedules);

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            // return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $selectedDate = Carbon::parse($request->start);

            $title = $request->title;
            $startTime = $request->startTime;
            $endTime = $request->endTime;
            $description = $request->description;

            // \Log::info($schedule->start);
            // \Log::info($selectedDate->toDateString());

            if ($schedule->start !== $selectedDate->toDateString()) {
                Schedule::where('lecturer_id', auth()->id())
                    ->whereMonth('start', Carbon::parse($schedule->start)->month)
                    ->whereYear('start', Carbon::parse($schedule->start)->year)
                    ->where('day_of_week', $schedule->day_of_week)
                    ->delete();


                $newSchedules = [];
                $now = Carbon::now();
                $lastDay = $now->copy()->endOfMonth();

                for ($day = $selectedDate->copy(); $day->lte($lastDay); $day->addWeek()) {
                    $newSchedules[] = [
                        'title' => $title,
                        'lecturer_id' => auth()->id(),
                        'start' => $day->toDateString(),
                        'day_of_week' => $day->dayOfWeek,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'description' => $description,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Schedule::insert($newSchedules);
                return response()->json(['success' => true, 'schedules' => $newSchedules]);
            } else {
                $schedule->update([
                    'title' => $title,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'description' => $description,
                    'updated_at' => now(),
                ]);

                return response()->json(['success' => true, 'schedule' => $schedule]);
            }
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function schedules()
    {
        try {
            $schedules = Schedule::where('lecturer_id', auth()->user()->id)->get();
            $schedules = $schedules->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start,
                    'startTime' => $event->start_time,
                    'endTime' => $event->end_time,
                    'description' => $event->description,
                ];
            });

            // \Log::info($schedules->toArray());
            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function scheduleListForStudent()
    {
        try {
            $schedules = Schedule::with('lecturer')->get();
            // \Log::info($schedules->toArray());

            $schedules = $schedules->map(function ($event) {
                return [
                    'id' => $event->id,
                    'lecturer' => $event->lecturer->name,
                    'title' => $event->title,
                    'start' => $event->start,
                    'startTime' => $event->start_time,
                    'endTime' => $event->end_time,
                    'description' => $event->description,
                ];
            });

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function joinSchedule(Request $request)
    {
        try {
            $user = auth()->user();
            $scheduleId = $request->id;
            \Log::info($scheduleId);
            if (!$user) {
                return redirect()->route('login');
            }

            $user->schedules()->sync([$scheduleId]);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
