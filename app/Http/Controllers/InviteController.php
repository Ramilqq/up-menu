<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\InviteUpdateRequest;
use App\Models\Invite;
use App\Policies\InvitePolicy;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function show($id)
    {
        if (!InvitePolicy::requestShow($id)) return response()->json([]);
        $invite = Invite::find($id) ?: ['success' => false, 'message' => 'Инвайт не найден.'];
        return response()->json($invite);
    }

    public function destroy($id)
    {
        if (!InvitePolicy::requestDelete($id)) return response()->json([]);
        return Invite::destroy($id);
    }

}
