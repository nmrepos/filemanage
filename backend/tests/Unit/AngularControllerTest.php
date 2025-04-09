<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AngularController;

class AngularControllerTest extends TestCase
{
    /**
     * Test that the index() method returns the "angular" view.
     *
     * @return void
     */
    public function testIndexReturnsAngularView()
    {
        $controller = new AngularController();
        $response = $controller->index();
        $this->assertTrue(method_exists($response, 'getName'), 'Returned response does not appear to be a view.');
        $this->assertEquals('angular', $response->getName());
    }
}
