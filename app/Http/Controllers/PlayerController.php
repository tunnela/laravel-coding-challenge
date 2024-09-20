<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\Player;
use App\Http\Requests\PlayerIndexRequest;
use App\Http\Requests\PlayerStoreRequest;
use App\Http\Requests\PlayerUpdateRequest;
use App\Http\Requests\PlayerDestroyRequest;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index(PlayerIndexRequest $request)
    {
        $players = Player::orderBy('id', 'asc')->get();

        return PlayerResource::collection($players);
        // return response("Failed", 500);
    }

    public function show($id)
    {
        return new PlayerResource(Player::findOrFail($id));
        // return response("Failed", 500);
    }

    public function store(PlayerStoreRequest $request)
    {
        $player = null;

        try {
            DB::beginTransaction();

            $player = Player::create($request->except('playerSkills'));

            $player->skills()->createMany($request->playerSkills);
            
            $player->load(['skills']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        return new PlayerResource($player);
        // return response("Failed", 500);
    }

    public function update(PlayerUpdateRequest $request, $id)
    {
        $player = Player::findOrFail($id);

        try {
            DB::beginTransaction();

            $player->fill($request->all());
            $player->save();

            if ($request->playerSkills) {
                $newSkills = collect($request->playerSkills)->keyBy('skill');

                $player->skills->each(function($skill) use ($newSkills) {
                    $updateWith = $newSkills->get($skill->skill->value);

                    if ($updateWith === null) {
                        return;
                    }
                    $skill->fill($updateWith)->save();

                    $newSkills->forget($skill->skill);
                });
                
                $player->skills()->createMany($newSkills);

                $player->skills()
                ->whereNotIn('skill', $newSkills->pluck('skill'))
                ->delete();

                /*
                 * In the first commits I had a migration to 
                 * make player_id + skill combo unique in player_skills
                 * table. This would've allowed easy updating of 
                 * existing skills and inserting new ones with `upsert()`. 
                 * Leaving this code as an example in case you are interested.
                 *
                 *  $skillsWithPlayerId = collect($request->playerSkills)
                 *  ->map(function($skill) use ($player) {
                 *      return $skill + ['player_id' => $player->id];
                 *  });
                 *  
                 *  $player->skills()->upsert(
                 *      $skillsWithPlayerId->toArray(), 
                 *      ['player_id', 'skill'], 
                 *      ['value']
                 *  );
                 *  
                 *  // delete only the skills we didn't have in the request
                 *  $player->skills()
                 *  ->whereNotIn('skill', $skillsWithPlayerId->pluck('skill'))
                 *  ->delete();
                 *
                 */
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        $player->load(['skills']);

        return new PlayerResource($player);
        // return response("Failed", 500);
    }

    public function destroy(PlayerDestroyRequest $request, $id)
    {
        Player::destroy($id);

        return response()->json('', 204);
        // return response("Failed", 500);
    }
}
