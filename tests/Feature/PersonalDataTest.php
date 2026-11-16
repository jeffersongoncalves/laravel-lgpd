<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Lgpd\Facades\Lgpd;
use JeffersonGoncalves\Lgpd\Tests\Fixtures\Customer;

beforeEach(function () {
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('email')->unique()->nullable();
        $table->text('health_notes')->nullable();
        $table->string('tax_id')->nullable();
        $table->string('plan')->nullable();
    });

    config()->set('lgpd.models', [Customer::class]);
});

function customer(): Customer
{
    return Customer::create([
        'name' => 'Maria Silva',
        'email' => 'maria@example.com',
        'health_notes' => 'diabetes',
        'tax_id' => '123.456.789-09',
        'plan' => 'pro',
    ]);
}

it('builds the inventory from attributes', function () {
    $inventory = Lgpd::inventory();

    expect($inventory)->toHaveCount(4)
        ->and($inventory[0])->toBe([
            'model' => Customer::class,
            'table' => 'customers',
            'column' => 'name',
            'category' => 'personal',
            'purpose' => 'Identificação',
            'legal_basis' => 'contract',
            'retention' => null,
            'anonymize' => 'mask',
        ])
        ->and($inventory[2]['category'])->toBe('sensitive');
});

it('exports only declared columns, decrypted', function () {
    expect(customer()->exportPersonalData())->toBe([
        'name' => 'Maria Silva',
        'email' => 'maria@example.com',
        'health_notes' => 'diabetes',
        'tax_id' => '123.456.789-09',
    ]);
});

it('anonymizes each column with its strategy', function () {
    $customer = customer();
    $customer->anonymizePersonalData();
    $fresh = $customer->fresh();

    expect($fresh->name)->toBe('M**********')
        ->and($fresh->email)->toBe(hash_hmac('sha256', 'maria@example.com', config('app.key')))
        ->and($fresh->health_notes)->toBeNull()
        ->and($fresh->tax_id)->toBe('123.456.789-09')
        ->and($fresh->plan)->toBe('pro');
});

it('rejects models that do not implement the contract', function () {
    config()->set('lgpd.models', [stdClass::class]);

    Lgpd::models();
})->throws(InvalidArgumentException::class);

it('prints the inventory as json', function () {
    Artisan::call('lgpd:inventory', ['--json' => true]);

    expect(json_decode(Artisan::output(), true))->toHaveCount(4);
});
