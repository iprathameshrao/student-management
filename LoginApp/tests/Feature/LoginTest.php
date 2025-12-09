<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User can view login form
     */
    public function test_user_can_view_login_form(): void
    {
        $response = $this->get(route('login.page'));

        $response->assertStatus(200);
        $response->assertViewIs('LoginPage');
    }

    /**
     * Test: Login successful with valid credentials
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.check'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Login fails with invalid email
     */
    public function test_login_fails_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.check'), [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Login fails with incorrect password
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.check'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Login fails with empty email
     */
    public function test_login_fails_with_empty_email(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Login fails with empty password
     */
    public function test_login_fails_with_empty_password(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * Test: Login fails with invalid email format
     */
    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Login fails with both fields empty
     */
    public function test_login_fails_with_both_fields_empty(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test: Session is regenerated after successful login
     */
    public function test_session_is_regenerated_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $oldSessionId = session()->getId();

        $this->post(route('login.check'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }

    /**
     * Test: Teacher data is stored in session after login
     */
    public function test_teacher_data_stored_in_session_after_login(): void
{
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'teacher_id' => 123,
        'name' => 'John Doe',
    ]);

    // Send login request
    $response = $this->post(route('login.check'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    // Assert session values
    $response->assertSessionHas('teacher_id', 123);
    $response->assertSessionHas('teacher_name', 'John Doe');
}


    /**
     * Test: Authenticated user cannot view login page (optional - redirect to dashboard)
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /**
     * Test: Unauthenticated user cannot access protected routes
     */
    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login.page'));
    }

    /**
     * Test: Multiple failed login attempts
     */
    public function test_multiple_failed_login_attempts(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        for ($i = 0; $i < 3; $i++) {
            $response = $this->post(route('login.check'), [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);

            $response->assertSessionHasErrors('email');
            $this->assertGuest();
        }
    }

    /**
     * Test: Case sensitivity of email (emails should be case-insensitive)
     */
    public function test_login_with_different_email_case(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.check'), [
            'email' => 'TEST@EXAMPLE.COM',
            'password' => 'password123',
        ]);

        // This depends on your application's database collation
        // Most databases are case-insensitive by default
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Login with extra whitespace in email
     */
    public function test_login_fails_with_whitespace_in_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.check'), [
            'email' => '  test@example.com  ',
            'password' => 'password123',
        ]);

        // Expect the application to trim the email and log the user in
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test: SQL injection attempt is blocked
     */
    public function test_sql_injection_attempt_is_blocked(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => "' OR '1'='1",
            'password' => "' OR '1'='1",
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test: XSS attempt in login form
     */
    public function test_xss_attempt_is_handled(): void
    {
        $response = $this->post(route('login.check'), [
            'email' => '<script>alert("xss")</script>@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: User logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login.page'));

        $this->assertGuest();
    }

    /**
     * Test: Session is invalidated after logout
     */
    public function test_session_is_invalidated_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $this->assertNull(session('teacher_id'));
        $this->assertNull(session('teacher_name'));
    }
}
