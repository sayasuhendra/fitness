<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TrainerResource;
use App\Models\PersonalTrainer;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;

class TrainerController extends Controller
{
    public function index(): JsonResponse
    {
        $trainers = PersonalTrainer::query()
            ->with('user')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return ApiResponder::success(TrainerResource::collection($trainers), 'Trainers retrieved');
    }
}
