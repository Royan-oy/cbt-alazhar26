<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ujian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UjianTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function token_generator_menghasilkan_6_digit_karakter()
    {
        $token = Ujian::generateToken();

        $this->assertIsString($token);
        $this->assertEquals(6, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $token);
    }
}
