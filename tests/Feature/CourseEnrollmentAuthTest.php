<?php

namespace Tests\Feature;

use Tests\TestCase;

class CourseEnrollmentAuthTest extends TestCase
{
    /**
     * Test /course redirects to /courses.
     */
    public function test_course_singular_route_redirects_to_courses(): void
    {
        $response = $this->get('/course');
        $response->assertRedirect('/courses');
    }

    /**
     * Test that unauthenticated enrollment returns 401 JSON with redirect URL.
     */
    public function test_unauthenticated_enroll_returns_401_with_redirect(): void
    {
        $this->withoutMiddleware();

        $response = $this->postJson('/courses/1/enroll');

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Please login first to enroll courses',
            'redirect' => route('login'),
        ]);
    }
}
