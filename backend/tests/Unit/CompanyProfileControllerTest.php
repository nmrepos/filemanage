<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\CompanyProfileController;
use App\Repositories\Contracts\CompanyProfileRepositoryInterface;

class CompanyProfileControllerTest extends TestCase
{
    /**
     * Test that getCompanyProfile() returns the expected JSON response.
     */
    public function testGetCompanyProfile()
    {
        // Expected data returned by the repository.
        $expected = ['company' => 'Test Company', 'address' => '123 Testing Lane'];

        // Create a mock of the repository.
        $repositoryMock = $this->createMock(CompanyProfileRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getCompanyProfile')
            ->willReturn($expected);

        // Instantiate the controller.
        $controller = new CompanyProfileController($repositoryMock);
        $response = $controller->getCompanyProfile();

        // Assert that the response content matches the expected JSON.
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * Test that updateCompanyProfile() returns the expected JSON response.
     */
    public function testUpdateCompanyProfile()
    {
        // Input data for updating company profile.
        $inputData = ['company' => 'Updated Company'];
        $expectedOutput = ['company' => 'Updated Company', 'status' => 'updated'];

        // Create a Request instance with the input data.
        $request = Request::create('/update-company', 'POST', $inputData);

        // Create a mock repository.
        $repositoryMock = $this->createMock(CompanyProfileRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('updateCompanyProfile')
            ->with($inputData)
            ->willReturn($expectedOutput);

        // Instantiate the controller.
        $controller = new CompanyProfileController($repositoryMock);
        $response = $controller->updateCompanyProfile($request);

        // Assert that a 200 status (default) response contains the expected JSON.
        $this->assertEquals(json_encode($expectedOutput), $response->getContent());
    }

    /**
     * Test that updateStorage() returns the repository's output.
     *
     * Note: In this action the repository result is returned directly
     * without wrapping it in a JSON response.
     */
    public function testUpdateStorage()
    {
        $inputData = ['storageLimit' => '500GB'];
        $expectedOutput = 'Storage updated successfully';

        $request = Request::create('/update-storage', 'POST', $inputData);

        $repositoryMock = $this->createMock(CompanyProfileRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('updateStorage')
            ->with($inputData)
            ->willReturn($expectedOutput);

        $controller = new CompanyProfileController($repositoryMock);
        $response = $controller->updateStorage($request);

        // Since updateStorage() returns the output directly, we assert equality.
        $this->assertEquals($expectedOutput, $response);
    }

    /**
     * Test that getStorage() returns the expected result.
     */
    public function testGetStorage()
    {
        $expectedOutput = ['storageUsed' => '250GB', 'storageLimit' => '500GB'];

        $repositoryMock = $this->createMock(CompanyProfileRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getStorage')
            ->willReturn($expectedOutput);

        $controller = new CompanyProfileController($repositoryMock);
        $response = $controller->getStorage();

        // Here we assume getStorage() returns the value directly.
        $this->assertEquals($expectedOutput, $response);
    }
}
