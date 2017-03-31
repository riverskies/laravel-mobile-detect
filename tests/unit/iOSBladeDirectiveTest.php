<?php

use Riverskies\Laravel\MobileDetect\Directives\iOSBladeDirective;

class iOSBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new iOSBladeDirective);
    }

    /** @test */
    public function it_will_not_render_if_not_ios()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_ios()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_not_ios()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_still_display_ios_if_is_ios_and_else_exists()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }
}
