<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $res = $this->delete(self::REQ_URI . '1');

        $this->assertNotNull($res);
    }

    public function test_request_with_valid_bearer_should_be_ok()
    {
        $res = $this->delete(self::REQ_URI . '1', [], [
            'Authorization' => 'Bearer ' . config('auth.bearer_token')
        ]);

        // Since deleting, result should be "204 No Content"
        $res->assertStatus(204);
    }

    /*
     * REST API is statless, so multiple DELETE calls to 
     * the same resource should return the same HTTP code 
     * "204 No Content".
     */
    public function test_repeated_requests_should_be_ok()
    {
        $res = $this->delete(self::REQ_URI . '1', [], [
            'Authorization' => 'Bearer ' . config('auth.bearer_token')
        ]);

        $res->assertStatus(204);

        $res = $this->delete(self::REQ_URI . '1', [], [
            'Authorization' => 'Bearer ' . config('auth.bearer_token')
        ]);

        $res->assertStatus(204);
    }

    public function test_request_with_invalid_bearer_should_be_forbidden()
    {
        $res = $this->delete(self::REQ_URI . '1', [], [
            'Authorization' => 'Bearer INVALID_BEARER'
        ]);

        $res->assertStatus(403);
    }
}
