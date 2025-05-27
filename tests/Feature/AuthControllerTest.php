<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_the_login_form()
    {
        // Memastikan halaman login dapat diakses
        $response = $this->get('/login');
        $response->assertStatus(200); // Status 200 berarti sukses
        $response->assertViewIs('auth.login'); // Pastikan view login yang ditampilkan
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // Membuat pengguna dummy
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
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
