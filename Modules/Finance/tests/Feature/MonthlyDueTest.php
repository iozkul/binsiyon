<?php

namespace Modules\Finance\tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Modules\Finance\app\Models\MonthlyDue;

class MonthlyDueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create roles
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'accountant']);
    }

    public function test_accountant_can_view_monthly_dues_page()
    {
        $user = User::factory()->create();
        $user->assignRole('accountant');

        MonthlyDue::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('finance.monthly-dues.index'));

        $response->assertStatus(200);
        $response->assertViewIs('finance::monthly_dues.index');
        $response->assertViewHas('monthlyDues');
    }
}
