<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MentorResource;
use App\Models\Mentor;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function index(Request $request)
    {
        $mentors = Mentor::query()
            ->with('region')
            ->where('is_active', true)
            ->when($request->filled('region_id'), fn ($query) => $query->where('region_id', $request->integer('region_id')))
            ->paginate(12);

        return ApiResponse::success(MentorResource::collection($mentors), 'Mentors loaded.');
    }
}
