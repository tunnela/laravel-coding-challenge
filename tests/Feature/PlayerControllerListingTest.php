<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $res = $this->get(self::REQ_URI);

        $this->assertNotNull($res);
    }

    public function test_valid_response()
    {
        $res = $this->get(self::REQ_URI);

        $res->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'position',
                'playerSkills' => [
                    '*' => [
                        'id',
                        'skill',
                        'value',
                        'playerId'
                    ]
                ]
            ]
        ]);
    }
}
