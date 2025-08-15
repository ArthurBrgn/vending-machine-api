<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Classification;
use App\Models\ClassificationLimit;
use App\Models\Employee;
use App\Models\EmployeeDailyProductPurchase;
use App\Models\Machine;
use App\Models\ProductCategory;
use App\Models\Slot;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->classification = Classification::factory()
        ->manager()
        ->create();

    $this->employee = Employee::factory()
        ->for($this->classification)
        ->create(['current_points' => 500]);

    $this->productCategory = ProductCategory::factory()->create();

    $this->card = Card::factory()
        ->for($this->employee)
        ->create(['is_active' => true, 'expired_date' => null]);

    $this->machine = Machine::factory()->create();

    $this->slot = Slot::factory()
        ->for($this->machine)
        ->for($this->productCategory)
        ->create([
            'number' => 1,
            'is_available' => true,
            'price' => 50,
        ]);

    $this->classificationLimit = ClassificationLimit::factory()
        ->create([
            'classification_id' => $this->classification->id,
            'product_category_id' => $this->productCategory->id,
            'daily_limit' => 1,
        ]);

    $this->basePayloadData = [
        'card_number' => $this->card->number,
        'machine_id' => $this->machine->id,
        'slot_number' => $this->slot->number,
        'product_price' => $this->slot->price,
    ];
});

test('transaction successful', function () {
    $response = $this->postJson(route('purchase'), $this->basePayloadData);

    $response
        ->assertOk()
        ->assertJsonIsObject()
        ->assertJson([
            'success' => true,
            'message' => 'Purchase successful',
            'data' => [
                'points_deducted' => $this->slot->price,
                'remaining_points' => $this->employee->current_points - $this->slot->price,
            ],
        ]);

    $this->assertDatabaseCount('transactions', 1);

    $this->assertDatabaseHas('transactions', [
        'points_deducted' => $this->slot->price,
        'employee_id' => $this->employee->id,
        'card_id' => $this->card->id,
        'machine_id' => $this->machine->id,
        'slot_id' => $this->slot->id,
        'product_category_id' => $this->productCategory->id,
    ]);

    $this->assertDatabaseHas('employee_daily_product_purchases', [
        'date' => today(),
        'daily_count' => 1,
        'employee_id' => $this->employee->id,
        'product_category_id' => $this->productCategory->id,
    ]);

    $this->employee->refresh();
    expect($this->employee->current_points)->toBe(450);
});

test('employee card is invalid', function () {
    $payload = array_merge($this->basePayloadData, ['card_number' => 'INVALID CARD NUMBER']);

    $this->postJson(route('purchase'), $payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'This card is invalid',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('employee card is expired', function () {
    $card = Card::factory()
        ->for($this->employee)
        ->create([
            'expired_date' => now()->subDay(),
        ]);

    $payload = array_merge($this->basePayloadData, ['card_number' => $card->number]);

    $this->postJson(route('purchase'), $payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'This card is invalid',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('slot is invalid', function () {
    $payload = array_merge($this->basePayloadData, ['slot_number' => 999]);

    $this->postJson(route('purchase'), $payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'This slot is invalid',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('product price mismatch', function () {
    $payload = array_merge($this->basePayloadData, ['product_price' => 999999]);

    $this->postJson(route('purchase'), $payload)
        ->assertStatus(Response::HTTP_CONFLICT)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'The product price is invalid',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('employee does not have enough points', function () {
    $this->employee->update(['current_points' => 10]);

    $this->postJson(route('purchase'), $this->basePayloadData)
        ->assertStatus(Response::HTTP_PAYMENT_REQUIRED)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'You have not enough points to buy this product',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('classification limit not found', function () {
    ClassificationLimit::query()->delete();

    $this->postJson(route('purchase'), $this->basePayloadData)
        ->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'This product category does not exists',
        ]);

    $this->assertDatabaseEmpty('transactions');
    $this->assertDatabaseEmpty('employee_daily_product_purchases');
});

test('daily quota exceeded', function () {
    EmployeeDailyProductPurchase::factory()->create([
        'employee_id' => $this->employee->id,
        'product_category_id' => $this->productCategory->id,
        'date' => today(),
        'daily_count' => $this->classificationLimit->daily_limit,
    ]);

    $this->postJson(route('purchase'), $this->basePayloadData)
        ->assertStatus(Response::HTTP_FORBIDDEN)
        ->assertJsonIsObject()
        ->assertJson([
            'success' => false,
            'message' => 'Quota exceeded for this product category',
        ]);

    $this->assertDatabaseEmpty('transactions');
});
