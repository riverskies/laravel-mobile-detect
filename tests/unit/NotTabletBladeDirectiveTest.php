<?php

use Riverskies\Laravel\MobileDetect\NotTabletBladeDirective;

class NotTabletBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new NotTabletBladeDirective);
    }

    /** @test */
    public function it_will_not_render_if_tablet()
    {
        $this->expectReturn(false, true);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_mobile()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_desktop()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_tablet()
    {
        $this->expectReturn(true, true);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }
}
