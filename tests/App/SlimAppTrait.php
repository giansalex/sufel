<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 11/04/2018
 * Time: 09:53
 */

namespace Tests\App;

use Slim\App;

trait SlimAppTrait
{
    /**
     * @return App
     */
    public function createApp()
    {
        $settings = require __DIR__ . '/../settings.php';

        // Instantiate the application
        $app = new App($settings);
        require __DIR__ . '/../../src/dependencies.php';

        return $app;
    }
}