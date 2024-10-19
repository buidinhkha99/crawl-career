<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class ClassroomController extends Controller
{
    /**
     * Get history attendance had user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyAttendance(Request $request)
    {
        $user = auth('api')->user();
        // get all attendances user existed
        $query = Attendance::with('attendees')->whereHas('attendees', function ($query) use ($user) {
            $query->where(['user_id' => $user->id]);
        });

        // search by name class and name lesson
        if (!empty($key = $request->get('search'))) {
            $query->where('name', 'LIKE', $key . '%')->orWhereHas('classroom', function ($query) use ($key){
                $query->where('name', 'LIKE', $key . '%');
            });
        }

        $data = $query->paginate($request->get('per_page', 10));
        $attendances = $data->getCollection();
        $historyAttendance = $attendances->map(fn($attendance) => [
            'classroom' => $attendance->classroom->name,
            'lesson' => $attendance->name,
            'date' => $attendance->date ?? null, // date start lesson
            'is_attended' => !empty($attendance->attendees->where('user_id', $user->id)->first()->created_at ?? null),
        ]);

        $data = $data->toArray();
        $data['data'] = $historyAttendance;

        return response()->json([
            'message' => 'Successfully',
            'data' => $data
        ]);
    }

    /**
     * Attending
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function attending(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $user = auth('api')->user();

        $attended = $attendance->attendees()
            ->whereNull('created_at')
            ->where(['attendance_id' => $attendance->id, 'user_id' => $user->id])
            ->first();

        if (is_null($attended)) {
            return response()->json([
                'message' => __('User already attended!'),
                'data' => [
                    'classroom' => $attendance->classroom->name,
                    'lesson' => $attendance->name,
                    'date' => $attendance->date,
                ]
            ]);
        }

        // check time attendance
        if (!empty($attendance->start_attendance) && Carbon::now()->lt($attendance->start_attendance)) {
            return response()->json([
                'message' => __("Attendance time hasn't started yet!"),
                'data' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        if (!empty($attendance->end_attendance) && Carbon::now()->gt($attendance->end_attendance)) {
            return response()->json([
                'message' => __('Attendance time has ended!'),
                'data' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $attended->created_at = now();
        $attended->updated_at = now();
        $attended->save();

        return response()->json([
            'message' => __('Attendance added successfully!'),
            'data' => [
                'classroom' => $attendance->classroom->name,
                'lesson' => $attendance->name,
                'date' => $attendance->date,
            ]
        ]);
    }
}
