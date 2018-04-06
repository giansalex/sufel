<?php

namespace Tests\Functional;

use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * API TOKEN.
     * @var string
     */
    protected static $token = '';

    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * @var callable
     */
    protected $onBeforeRun;

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8090';
        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        if (!empty(self::$token)) {

            $request = $request->withAddedHeader('Authorization', 'Bearer ' . self::$token);
        }

        // Set up a response object
        $response = new Response();

        // Use the application settings
        $settings = require __DIR__ . '/../settings.php';

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        require __DIR__ . '/../../src/dependencies.php';

        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__ . '/../../src/middleware.php';
        }

        // Register routes
        require __DIR__ . '/../../src/routes.php';

        if ($this->onBeforeRun) {
            call_user_func_array($this->onBeforeRun, [$app]);
        }

        // Process the application
        $response = $app->process($request, $response);
        $this->checkServerError($response);

        // Return the response
        return $response;
    }

    /**
     * @param $body
     * @return mixed
     */
    public function getObject($body)
    {
        return json_decode((string)$body);
    }

    private function checkServerError(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 500) {
            return;
        }

        $content = (string)$response->getBody();

        if ($this->isWindows()) {
            $tmp = sys_get_temp_dir() . PATH_SEPARATOR . time() . '.html';
            file_put_contents($tmp, $content);
            exec('start ' . $tmp);
            return;
        }

        echo $content;
    }

    private function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
