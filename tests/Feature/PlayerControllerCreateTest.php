<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $data = [
            "name" => "test",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertNotNull($res);

        $res->assertStatus(201)
        ->assertJsonStructure(['id'])
        ->assertJson($data);
    }
    
    public function test_empty_request_should_fail() 
    {
        $this->postJson(self::REQ_URI, [])
        ->assertStatus(422);
    }
    
    /**
     * @dataProvider validationErrors
     */
    public function test_validate_request_errors($invalidData, $field, $invalidValue) 
    {
        $this->postJson(self::REQ_URI, $invalidData)
        ->assertJson([
            'message' => 'Invalid value for ' . $field .  ': ' . $invalidValue
        ]);
    }

    public function validationErrors()
    {
        $str = 'Test';
        $num = 123;
        $skill = 'attack';

        $player = collect([
            'name' => 'test',
            'position' => 'defender',
            'playerSkills' => [
                0 => [
                    'skill' => 'attack',
                    'value' => 60
                ],
                1 => [
                    'skill' => 'speed',
                    'value' => 80
                ]
            ]
        ]);

        $withName = $player->only('name')->all();
        $withNamePos = $player->only('name', 'position')->all();

        return [
            'name is required' => [[], 'name', 'empty'],
            'name should not be null' => [['name' => null], 'name', 'empty'],
            'name should not be empty' => [['name' => ''], 'name', 'empty'],
            'name should not be numeric' => [['name' => $num], 'name', $num],

            'position is required' => [$withName, 'position', 'empty'],
            'position should not be null' => [['position' => null] + $withName, 'position', 'empty'],
            'position should not be empty' => [['position' => ''] + $withName, 'position', 'empty'],
            'position should not be numeric' => [['position' => $num] + $withName, 'position', $num],
            'position should not be invalid' => [['position' => $str] + $withName, 'position', $str],

            'playerSkills is required' => [$withNamePos, 'playerSkills', 'empty'],
            'playerSkills should not be null' => [['playerSkills' => null] + $withNamePos, 'playerSkills', 'empty'],
            'playerSkills should not be empty' => [['playerSkills' => ''] + $withNamePos, 'playerSkills', 'empty'],
            'playerSkills should not be empty' => [['playerSkills' => []] + $withNamePos, 'playerSkills', 'empty'],
            'playerSkills should not be numeric' => [['playerSkills' => $num] + $withNamePos, 'playerSkills', $num],
            'playerSkills should not be string' => [['playerSkills' => $str] + $withNamePos, 'playerSkills', $str],

            'skill should not be empty' => [['playerSkills' => [[]]] + $withNamePos, 'skill', 'empty'],
            'skill should not be invalid' => [['playerSkills' => [['skill' => $str]]] + $withNamePos, 'skill', $str],

            'value should not be empty' => [['playerSkills' => [['skill' => $skill]]] + $withNamePos, 'value', 'empty'],
            'value should not be empty' => [['playerSkills' => [['skill' => $skill, 'value' => '']]] + $withNamePos, 'value', 'empty'],
            'value should not be invalid' => [['playerSkills' => [['skill' => $skill, 'value' => $str]]] + $withNamePos, 'value', $str],
        ];
    }
}
