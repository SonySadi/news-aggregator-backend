<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserPreferenceController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $validatedData = $request->validate([
            'preferred_sources' => 'nullable|array',
            'preferred_authors' => 'nullable|array',
        ]);

        $user->update($validatedData);

        return response()->json($user->only(['preferred_sources', 'preferred_authors']));
    }
}
