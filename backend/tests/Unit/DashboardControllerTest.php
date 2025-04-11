<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardControllerTest extends TestCase
{
    /**
     * Test that getReminders() returns the expected JSON response.
     */
    public function testGetReminders()
    {
        // Define test parameters for month and year.
        $month = 7;
        $year = 2023;

        // Define the expected data that should be returned by the repository.
        $expectedData = [
            ['id' => 1, 'reminder' => 'Task 1', 'date' => '2023-07-10'],
            ['id' => 2, 'reminder' => 'Task 2', 'date' => '2023-07-15']
        ];

        // Create a mock of the DashboardRepositoryInterface.
        $repositoryMock = $this->createMock(DashboardRepositoryInterface::class);

        // Expect the getReminders() method to be called once with the provided month and year.
        $repositoryMock->expects($this->once())
            ->method('getReminders')
            ->with($month, $year)
            ->willReturn($expectedData);

        // Instantiate the controller with the mocked repository.
        $controller = new DashboardController($repositoryMock);

        // Call the controller method.
        $response = $controller->getReminders($month, $year);

        // Assert that the response is a JSON response with the expected content.
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }
}
