<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    public function testGetHomepage()
    {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('SUFEL API', (string)$response->getBody());
        $this->assertNotContains('Hello', (string)$response->getBody());
    }

    public function testGetApiNotAuthorize()
    {
        $response = $this->runApp('GET', '/api');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testPostHomepageNotAllowed()
    {
        $response = $this->runApp('POST', '/');

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }
}