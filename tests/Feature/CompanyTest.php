<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBasicRequest()
    {
        $response = $this->get(route('company.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $response = $this->postJson(route('company.store'), Company::factory()->make()->toArray());

        $response->assertStatus(201)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'name', 'tax_number', 'address', 'city', 'postal_code', 'created_at', 'updated_at']
                     )
                 );
    }

    public function testStoreFailure()
    {
        $response = $this->postJson(route('company.store'), []);

        $response->assertStatus(422);
    }

    public function testUpdateSuccess()
    {
        $company = Company::factory()->createOne();

        $response = $this->patchJson(route('company.update', $company->id), Company::factory()->make()->toArray());

        $response->assertStatus(201)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'name', 'tax_number', 'address', 'city', 'postal_code', 'created_at', 'updated_at']
                     )
                 );
    }

    public function testUpdateFailure()
    {
        $company = Company::factory()->createOne();

        $response = $this->patchJson(route('company.update', $company->id), []);

        $response->assertStatus(422);
    }

    public function testShow()
    {
        $company = Company::factory()->createOne();

        $response = $this->get(route('company.show', $company->id));

        $response->assertStatus(200)
                 ->assertJson(
                     fn(AssertableJson $json) => $json->hasAll(
                         ['id', 'name', 'tax_number', 'address', 'city', 'postal_code', 'created_at', 'updated_at', 'employees']
                     )
                 );
    }

    public function testDelete()
    {
        $company = Company::factory()->createOne();
        $id = $company->id;

        $response = $this->delete(route('company.destroy', $id));

        $response->assertStatus(204);

        $response = $this->get(route('company.show', $id));

        $response->assertStatus(404);
    }
}
