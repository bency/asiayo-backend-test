<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CurrencyTest extends TestCase
{
    /**
     * A basic test example.
     */
    #[DataProvider('dataProvider')]
    public function testCurrency($source, $target, $amount, $expected): void
    {
        $this->get('/api/currency?source=' . $source . '&target=' . $target . '&amount=' . $amount)
            ->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => $expected]);
    }

    public static function dataProvider()
    {
        return [
            ['USD', 'JPY', '$999,999,999', '$111,800,999,888.2'],
            ['TWD', 'JPY', '$1525', '$5,595.23'],
            ['JPY', 'USD', '$1525', '$13.5'],
            ['TWD', 'USD', '$1525', '$50.04'],
            ['JPY', 'TWD', '$1525', '$411.08'],
            ['USD', 'TWD', '$999,999,999', '$30,443,999,969.56'],
        ];
    }
}
