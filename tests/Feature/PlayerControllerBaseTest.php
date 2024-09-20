<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class PlayerControllerBaseTest extends TestCase
{
    use RefreshDatabase;

    final const REQ_URI = '/api/player/';
    final const REQ_TEAM_URI = '/api/team/process';
 
    protected function setUp(): void
    {
        parent::setUp();

        $this->samplePlayer = $this->createSamplePlayer();
        $this->samplePlayers = $this->createManySamplePlayers();
    }

    protected function log($data){
        fwrite(STDERR, print_r($data, TRUE));
    }

    protected function createSamplePlayer($data = [])
    {
        $data = array_merge([
            'name' => 'test',
            'position' => 'attacker',
            'playerSkills' => [
                [
                    'skill' => 'attack',
                    'value' => 100
                ]
            ]
        ], $data);

        return [$this->postJson(self::REQ_URI, $data), $data];
    }

    protected function createManySamplePlayers() 
    {
        return [
            $this->createSamplePlayer([
                'name' => 'player1',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'skill' => 'attack',
                        'value' => 60
                    ],
                    [
                        'skill' => 'speed',
                        'value' => 80
                    ]
                ]
            ]),

            $this->createSamplePlayer([
                'name' => 'player2',
                'position' => 'midfielder',
                'playerSkills' => [
                    [
                        'skill' => 'attack',
                        'value' => 100
                    ],
                    [
                        'skill' => 'speed',
                        'value' => 50
                    ]
                ]
            ]),

            $this->createSamplePlayer([
                'name' => 'player3',
                'position' => 'midfielder',
                'playerSkills' => [
                    [
                        'skill' => 'stamina',
                        'value' => 15
                    ]
                ]
            ]),

            $this->createSamplePlayer([
                'name' => 'player4',
                'position' => 'midfielder',
                'playerSkills' => [
                    [
                        'skill' => 'strength',
                        'value' => 99
                    ]
                ]
            ])
        ];
    }
}
