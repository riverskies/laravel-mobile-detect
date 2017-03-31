<?php

use Riverskies\Laravel\MobileDetect\Directives\AndroidBladeDirective;

class AndroidBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new AndroidBladeDirective);
    }

    /** @test */
    public function it_will_not_render_if_not_android()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_android()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_not_android()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_still_display_android_if_is_android_and_else_exists()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }
}
