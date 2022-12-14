<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBasicRequest()
    {
        $response = $this->get(route('employee.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $company = Company::factory()->createOne();
        $data = Employee::factory()->make(['company_id' => $company->id])->toArray();

        $response = $this->postJson(route('employee.store'), $data);

        $response->assertStatus(201)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'first_name', 'email', 'last_name', 'phone', 'company_id', 'created_at', 'updated_at']
                     )
                 );
    }

    public function testStoreFailure()
    {
        $response = $this->postJson(route('employee.store'), []);

        $response->assertStatus(422);
    }

    public function testUpdateSuccess()
    {
        $company = Company::factory()->createOne();
        $employee = Employee::factory()->createOne(['company_id' => $company->id]);

        $response = $this->patchJson(route('employee.update', $employee->id), Employee::factory()->make()->toArray());

        $response->assertStatus(201)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'first_name', 'email', 'last_name', 'phone', 'company_id', 'created_at', 'updated_at']
                     )
                 );
    }

    public function testUpdateFailure()
    {
        $company = Company::factory()->createOne();
        $employee = Employee::factory()->createOne(['company_id' => $company->id]);

        $response = $this->patchJson(route('employee.update', $employee->id), []);

        $response->assertStatus(422);
    }

    public function testShow()
    {
        $company = Company::factory()->createOne();
        $employee = Employee::factory()->createOne(['company_id' => $company->id]);

        $response = $this->get(route('employee.show', $employee->id));

        $response->assertStatus(200)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'first_name', 'email', 'last_name', 'phone', 'company_id', 'created_at', 'updated_at', 'company']
                     )
                 );
    }

    public function testDelete()
    {
        $company = Company::factory()->createOne();
        $employee = Employee::factory()->createOne(['company_id' => $company->id]);

        $id = $employee->id;

        $response = $this->delete(route('employee.destroy', $id));

        $response->assertStatus(204);

        $response = $this->get(route('employee.show', $id));

        $response->assertStatus(404);
    }
}
