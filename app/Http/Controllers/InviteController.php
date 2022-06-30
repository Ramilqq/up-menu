<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\InviteUpdateRequest;
use App\Models\Invite;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function show($id)
    {
        $invite = Invite::find($id) ?: ['success' => false, 'message' => 'Инвайт не найден.'];
        return response()->json($invite);
    }

    public function destroy($id)
    {
        return Invite::destroy($id);
    }

}
