<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BackendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private string $email;
    private string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();
        $this->email = 'test_' . uniqid() . '@example.com';
    }

    /**
     * Test student registration through the backend API.
     */
    public function test_student_registration_and_login()
    {
        // 1. Register student
        $response = $this->post('/register', [
            'full_name' => 'Integration Tester',
            'phone' => '0812345678',
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect('/profile');
        $this->assertTrue(Auth::guard('student')->check());
        $this->assertEquals($this->email, Auth::guard('student')->user()->email);

        // 2. Logout
        $this->post('/logout');
        $this->assertFalse(Auth::guard('student')->check());

        // 3. Login
        $loginResponse = $this->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $loginResponse->assertRedirect('/profile');
        $this->assertTrue(Auth::guard('student')->check());
    }

    /**
     * Test retrieving courses from backend.
     */
    public function test_get_courses_list()
    {
        $response = $this->get('/courses');
        $response->assertStatus(200);
        $response->assertSee('English Beginner Course');
        $response->assertSee('German Beginner Course');
    }

    /**
     * Test course enrollment and fetching enrolled courses.
     */
    public function test_course_enrollment()
    {
        // 1. Register and login
        $this->post('/register', [
            'full_name' => 'Course Enroller',
            'phone' => '0812345679',
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // 2. Enroll in course ID 1 (English Beginner Course)
        $enrollResponse = $this->post('/courses/1/enroll');
        $enrollResponse->assertRedirect('/my-courses');

        // 3. Check my-courses page
        $myCoursesResponse = $this->get('/my-courses');
        $myCoursesResponse->assertStatus(200);
        $myCoursesResponse->assertSee('English Beginner Course');
        $myCoursesResponse->assertSee('English Basics and Introduction'); // class name
    }

    /**
     * Test placement tests retrieval and quiz submission.
     */
    public function test_placement_tests_and_quiz_submission()
    {
        // 1. Get placement tests
        $response = $this->get('/placement-tests');
        $response->assertStatus(200);
        $response->assertSee('English Placement Test');

        // 2. Register and login
        $this->post('/register', [
            'full_name' => 'Quiz Submitter',
            'phone' => '0812345680',
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // 3. Submit quiz ID 1
        $submitResponse = $this->postJson('/quizzes/1/submit', [
            'score' => 80,
            'answers' => [
                ['text' => 'Hello', 'is_correct' => true],
                ['text' => 'is', 'is_correct' => true],
                ['text' => 'Goodbye', 'is_correct' => true],
            ],
        ]);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJsonStructure(['success', 'result_id']);
    }

    /**
     * Test user creation and list (Admin/Teacher flow).
     */
    public function test_admin_user_creation()
    {
        $adminEmail = 'admin_' . uniqid() . '@example.com';

        // 1. Get users list
        $response = $this->get('/users');
        $response->assertStatus(200);

        // 2. Create a new user
        $createResponse = $this->post('/users', [
            'name' => 'New Instructor',
            'email' => $adminEmail,
            'level' => 'intermediate',
        ]);

        $createResponse->assertRedirect('/users');

        // 3. Check list again
        $checkResponse = $this->get('/users');
        $checkResponse->assertSee('New Instructor');
        $checkResponse->assertSee($adminEmail);
    }
}
