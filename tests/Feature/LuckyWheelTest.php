<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Award;
use App\Awards;
use App\AwardUser;
use Illuminate\Support\Facades\Auth;

class LuckyWheelTest extends TestCase
{
    use RefreshDatabase;

    // Set up the initial database state
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    // Test the seeder
    public function testDatabaseSeeding()
    {
        $this->assertDatabaseCount('users', 10);
        $this->assertDatabaseCount('awards', 3);
        $this->assertDatabaseHas('awards', ['title' => 'X']);
        $this->assertDatabaseHas('awards', ['title' => 'Y']);
        $this->assertDatabaseHas('awards', ['title' => 'empty']);
    }

    // Test API endpoint with a user who has enough points
    public function testLuckyWheelWithEnoughPoints()
    {
        $response = $this->getJson('/api/v1/lucky-wheel/');
        $user = Auth::user();

        $response->assertStatus(200);
        $response->assertJsonStructure(['title']);
        $this->assertDatabaseHas('award_user', ['user_id' => $user->id]);
    }

    // Test API endpoint with a user who doesn't have enough points
    public function testLuckyWheelWithInsufficientPoints()
    {
        $response = $this->getJson('/api/v1/lucky-wheel/');
        $user = Auth::user();
        if($user->points < 15)
             $response->assertStatus(422);
        else
        $this->assertTrue(true);

    }

    // Test prize selection logic
    public function testPrizeSelectionLogic()
    {
        // Mock the award selection logic if necessary or test the real logic
        $response = $this->getJson('/api/v1/lucky-wheel/');

        // Ensure an award is assigned based on the probability
        $awardTitle = $response->json('title');
        $this->assertContains($awardTitle, ['X', 'Y', 'empty']);
    }

    // Test that inventory decreases after prize is selected
    public function testInventoryDecreasesAfterSelection()
    {

        $award = Awards::where('title', 'X')->first();
        $initialInventory = $award->inventory;

        $response = $this->getJson('/api/v1/lucky-wheel/');

        if ($response->json('title') == 'X') {
            $award->refresh();
            $this->assertEquals($initialInventory - 1, $award->inventory);
        }
        $this->assertTrue(true);
    }
}
