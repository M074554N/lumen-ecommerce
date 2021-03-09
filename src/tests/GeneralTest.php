<?php

class GeneralTest extends TestCase
{
    public function testMainEndpoint()
    {
        $this->get('/');
        $this->assertResponseOk();
    }

    public function testDocsEndpoint()
    {
        $response = $this->get('/v1/docs');
        $response->assertResponseOk();
    }

    public function testSwaggerUiEndpoint()
    {
        $response = $this->get('/docs-ui');
        $response->assertResponseOk();
    }

    public function testNotfoundEndpoint()
    {
        $this->get('/sldksjdlsjd');
        $this->assertResponseStatus(404);
    }
}
