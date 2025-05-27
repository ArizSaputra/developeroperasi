<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
{
    // Membuat pengguna dummy dengan hak akses yang sesuai
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
        'hak_akses' => 'Bidan', // Pastikan sesuai dengan hak akses yang diterima
    ]);

    // Melakukan login dengan kredensial yang benar
    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    // Memastikan bahwa setelah login, pengguna diarahkan ke dashboard
    $response->assertRedirect('/dashboard'); // Ubah '/dashboard' sesuai dengan URL yang tepat
    $this->assertAuthenticatedAs($user); // Memastikan pengguna terautentikasi
}
/** @test */
public function user_cannot_login_with_invalid_credentials()
{
    // Mengirimkan login dengan kredensial yang salah
    $response = $this->post('/login', [
        'email' => 'wronguser@example.com',
        'password' => 'wrongpassword',
    ]);

    // Memastikan login gagal dan kembali ke halaman login dengan error
    $response->assertSessionHasErrors(['email']);
}

}
