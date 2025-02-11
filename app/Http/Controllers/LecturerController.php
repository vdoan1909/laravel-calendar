<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    const PATH_VIEW = 'lecturer.';

    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role == RoleEnum::LECTURER->value) {
            $data = Schedule::where('lecturer_id', auth()->user()->id)->get();
        } else {
            $data = $user->schedules()->with('lecturer')->get();
        }

        // dd($data);
        $data = $data->map(function ($event) {
            return [
                'title' => $event->title,
                'lecturerName' => $event->lecturer->name,
                'day' => $event->start,
                'startTime' => $event->start_time,
                'endTime' => $event->end_time,
                'description' => $event->description,
            ];
        })->sortBy('day')->values();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
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
            // return response()->json(['success' => false];
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
                $enrolledUsers = $schedule->students()->pluck('users.id')->toArray();

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

                $newScheduleIds = Schedule::where('lecturer_id', auth()->id())
                    ->whereMonth('start', $selectedDate->month)
                    ->whereYear('start', $selectedDate->year)
                    ->where('day_of_week', $selectedDate->dayOfWeek)
                    ->pluck('id')
                    ->toArray();

                DB::table('enrollments')->whereIn('schedule_id', [$id])->delete();
                foreach ($enrolledUsers as $userId) {
                    foreach ($newScheduleIds as $scheduleId) {
                        DB::table('enrollments')->insert([
                            'student_id' => $userId,
                            'schedule_id' => $scheduleId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

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
            return response()->json(['success' => false]);
        }
    }

    public function delete($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            DB::table('enrollments')->where('schedule_id', $id)->delete();

            $schedule->delete();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
            ]);
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
            return response()->json(['success' => false]);
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

            return response()->json(['success' => false]);
        }
    }

    public function joinSchedule(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login');
            }

            $scheduleId = $request->id;
            // \Log::info($scheduleId);

            $schedule = Schedule::findOrFail($scheduleId);

            $scheduleIds = Schedule::where('lecturer_id', $schedule->lecturer_id)
                ->whereMonth('start', Carbon::parse($schedule->start)->month)
                ->whereYear('start', Carbon::parse($schedule->start)->year)
                ->where('day_of_week', $schedule->day_of_week)
                ->pluck('id')
                ->toArray();
            // \Log::info($scheduleIds);

            $scheduleIds[] = $scheduleId;

            $user->schedules()->sync($scheduleIds);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
            ], 500);
        }
    }

}
