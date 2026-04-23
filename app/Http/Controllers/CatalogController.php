<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TrainerProfile;

class CatalogController extends Controller
{
    /**
     * Display a listing of Personal Trainers.
     * Paranoid Rule: Eager load relations to prevent N+1 and strictly paginate.
     */
    public function index(Request $request)
    {
        $query = TrainerProfile::with(['user' => function($q) {
            $q->select('id', 'name', 'gender', 'email');
        }]);

        // Optional filter
        if ($request->filled('gender') && $request->gender !== 'all') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        $trainers = $query->paginate(10)->withQueryString();

        return Inertia::render('Member/Catalog', [
            'trainers' => $trainers,
            'filters' => $request->only(['gender']),
        ]);
    }
}
