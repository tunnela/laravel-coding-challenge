<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerSkill;
use App\Http\Resources\PlayerResource;
use App\Http\Requests\TeamProcessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    public function process(TeamProcessRequest $request)
    {
        $usedPlayerIds = collect([]);

        $players = $request->collect()->map(function($requirement) use (&$usedPlayerIds) {
            [
                'position' => $position, 
                'mainSkill' => $mainSkill, 
                'numberOfPlayers' => $numberOfPlayers
            ] = $requirement;

            // We prioritize `skill` by keeping it as an int and by dividing
            // `value` by 1000, we get it to 0-0.255 range. This way MAX() + GROUP BY
            // combo will let us get top `skill` for each player, without non-wanted 
            // skill with higher `value` overriding wanted `skill` with lower value.
            $priority = '(CASE WHEN skill = ? THEN 1 ELSE 0 END) + value / 1000.000';

            $players = Player::with(['skills'])
            ->join('player_skills', function($join) {
                $join->on('player_id', '=', 'players.id');
            })
            ->selectRaw('players.*, MAX(' . $priority . ') as priority', [$mainSkill])
            ->where('position', $position)
            ->groupBy('player_id')
            ->orderBy('priority', 'desc')
            ->whereNotIn('player_id', $usedPlayerIds)
            ->limit($numberOfPlayers)
            ->get();

            if ($players->count() < $numberOfPlayers) {
                throw ValidationException::withMessages([
                    'position' => 'Insufficient number of players for position: ' . $position
                ]);
            }
            $usedPlayerIds = $usedPlayerIds->concat($players->pluck('id'));

            return $players;
        })->flatten();

        return PlayerResource::collection($players);
    }
}
