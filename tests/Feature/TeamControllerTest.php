<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


class TeamControllerTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $requirements =
            [
                'position' => "defender",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ];


        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $this->assertNotNull($res);
    }
    
    public function test_validate_status_code_and_error_message() 
    {
        $this->postJson(self::REQ_TEAM_URI, [])
        ->assertStatus(422)
        ->assertJson([
            'message' => 'Invalid value for requirements: empty'
        ]);
    }
    
    /**
     * @dataProvider validationErrors
     */
    public function test_validate_request_errors($invalidData, $field, $invalidValue) 
    {
        $this->postJson(self::REQ_TEAM_URI, $invalidData)
        ->assertJson([
            'message' => 'Invalid value for ' . $field .  ': ' . $invalidValue
        ]);
    }

    public function validationErrors()
    {
        $str = 'Test';
        $num = 100;
        $skill = 'attack';

        $requirement = collect([
            'numberOfPlayers' => 1,
            'position' => 'defender',
            'mainSkill' => 'attack'
        ]);

        $withSkill = $requirement->only('mainSkill')->all();
        $withSkillPos = $requirement->only('mainSkill', 'position')->all();

        return [
            'mainSkill should not be empty' => [[[]], 'mainSkill', 'empty'],
            'mainSkill should not be invalid' => [[['mainSkill' => $str]], 'mainSkill', $str],

            'position is required' => [[$withSkill], 'position', 'empty'],
            'position should not be null' => [[['position' => null] + $withSkill], 'position', 'empty'],
            'position should not be empty' => [[['position' => ''] + $withSkill], 'position', 'empty'],
            'position should not be numeric' => [[['position' => $num] + $withSkill], 'position', $num],
            'position should not be invalid' => [[['position' => $str] + $withSkill], 'position', $str],

            'numberOfPlayers should not be empty' => [[[] + $withSkillPos], 'numberOfPlayers', 'empty'],
            'numberOfPlayers should not be empty' => [[['numberOfPlayers' => ''] + $withSkillPos], 'numberOfPlayers', 'empty'],
            'numberOfPlayers should not be invalid' => [[['numberOfPlayers' => $str] + $withSkillPos], 'numberOfPlayers', $str],
        ];
    }
    
    /*
     * Check `setUp` method in PlayerControllerBaseTest to see sample data
     */
    public function test_validate_process_result() 
    {
        $this->postJson(
            self::REQ_TEAM_URI, 
            [
                [
                    'position' => 'midfielder',
                    'mainSkill' => 'speed',
                    'numberOfPlayers' => 2
                ]
            ]
        )
        ->assertStatus(200);
    }

    public function test_validate_insufficient_result() 
    {
        $this->postJson(
            self::REQ_TEAM_URI, 
            [
                [
                    'position' => 'midfielder',
                    'mainSkill' => 'speed',
                    'numberOfPlayers' => 4
                ]
            ]
        )
        ->assertStatus(422)
        ->assertJson([
            'message' => 'Insufficient number of players for position: midfielder'
        ]);
    }
    
    public function test_validate_exact_process_result() 
    {
        $resultShouldBe = [
            $this->samplePlayers[1][0]->getData(true), // player2, midfielder 1, highest attack
            $this->samplePlayers[3][0]->getData(true), // player4, midfielder 2, strength highest value
            $this->samplePlayers[0][0]->getData(true), // player4, defender 1, highest attack
        ];

        $this->postJson(
            self::REQ_TEAM_URI, 
            [
                [
                    'position' => 'midfielder',
                    'mainSkill' => 'attack',
                    'numberOfPlayers' => 2
                ],
                [
                    'position' => 'defender',
                    'mainSkill' => 'attack',
                    'numberOfPlayers' => 1
                ]
            ]
        )
        ->assertStatus(200)
        ->assertJson($resultShouldBe);
    }
}
