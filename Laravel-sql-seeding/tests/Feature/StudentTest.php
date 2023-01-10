<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{

	use RefreshDatabase;

    public function testGetStudent()
    {
        $response = $this->get('/api/v1/students');
	    $response->assertStatus(200);
	    $response = $this->get('/api/v1/abracadabra');
	    $response->assertStatus(404);
    }

	public function testCreateStudent()
	{
		$response = $this->get('/api/v1/students/create',[]);
		$response->assertStatus(422);
		$response = $this->post('/api/v1/students/create',['first_name' => 'Bob', 'last_name' => 'Marley']);
		$response->assertStatus(201);
		$this->refreshDatabase();
	}

	public function testDeleteStudent() {

		$response = $this->delete('/api/v1/students',['first_name' => 'Bob', 'last_name' => 'Marley']);
		$response->assertStatus(201);
		$StudentId = $this->getFirstStudentId();
		$response = $this->delete('/api/v1/students/' . $StudentId);
		$response->assertStatus(204);
		$response = $this->delete('/api/v1/students/' . $StudentId);
		$response->assertStatus(404);
	}

	public function testSetStudentGroupAssigned() {
		$this->refreshDatabase();
		$response = $this->post('/api/v1/students',[
				'first_name' => 'Bob',
				'last_name' => 'Marley',
				'course' => 'math']
		);

		$response->assertStatus(201);
		$StudentId = $this->getFirstStudentId();
		$response = $this->put('/api/v1/students/' . $StudentId . '/adaptable');
		$response->assertStatus(200);
		$this->refreshDatabase();
	}

	public function testGetStudentsWithLimitAndOffset() {
		factory(Student::class, 3)->create();
		$response = $this->get('/api/v1/students');
		$content = json_decode($response->getContent(), true);
		$this->assertEquals(count($content['data']), 3);
		$response = $this->get('/api/v1/students?limit=1');
		$content = json_decode($response->getContent(), true);
		$this->assertEquals(count($content['data']), 1);
		$response = $this->get('/api/v1/students?offset=1');
		$content = json_decode($response->getContent(), true);
		$this->assertEquals(count($content['data']), 2);
	}

	private function getFirstStudentId() {
		$response = $this->get('/api/v1/students?limit=1');
		$content = json_decode($response->getContent(), true);
		return $content['data'][0]['Student_id'];
	}
}
