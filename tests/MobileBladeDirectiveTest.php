<?php

use Detection\MobileDetect;
use Philo\Blade\Blade;
use Riverskies\Laravel\MobileDetect\Contracts\BladeDirectiveInterface;
use Riverskies\Laravel\MobileDetect\MobileBladeDirective;

class MobileBladeDirectiveTest extends PHPUnit_Framework_TestCase
{
    /**
     * Blade template engine instance.
     * @var Blade
     */
    protected $blade;

    /**
     * @param bool $returnMobile
     * @param bool $returnTablet
     */
    private function expectReturn($returnMobile = false, $returnTablet = false)
    {
        $mobileDetect = $this->prophesize(MobileDetect::class);
        $mobileDetect->isMobile()->willReturn($returnMobile);
        $mobileDetect->isTablet()->willReturn($returnTablet);

        $this->blade = $this->setUpTemplateEngine(
            new MobileBladeDirective($mobileDetect->reveal())
        );
    }

    /** @test */
    public function it_will_not_render_if_not_mobile()
    {
        $this->expectReturn(false, false);

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
    public function it_will_display_else_if_exist_and_not_mobile()
    {
        $this->expectReturn(false, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_still_display_mobile_if_is_mobile_and_else_exists()
    {
        $this->expectReturn(true, false);

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /**
     * Minifying HTML content.
     *
     * @link http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter#answer-5324014
     *
     * @param $data
     * @return mixed
     */
    private function clean($data)
    {
        $regexp = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespaces other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        return preg_replace($regexp, "", $data);
    }

    /**
     * Creates the context.
     *
     * @return array
     */
    private function createTestWorld()
    {
        list($resource, $view, $cache) = $this->getDirectories();

        @mkdir($resource);
        @mkdir($cache);
        @mkdir($view);

        @file_put_contents($view . '/test.blade.php', '
            @mobile
                <h1>Test</h1>
            @endmobile
        ');

        @file_put_contents($view . '/test-else.blade.php', '
            @mobile
                <h1>Test</h1>
            @elsemobile
                <h1>Else</h1>
            @endmobile
        ');

        return [$view, $cache];
    }

    /**
     * Sets up template engine to mimic Laravel.
     *
     * @param BladeDirectiveInterface $directive
     * @return Blade
     */
    private function setUpTemplateEngine(BladeDirectiveInterface $directive)
    {
        list($views, $cache) = $this->createTestWorld();
        $blade = new Blade($views, $cache);

        $blade->getCompiler()->directive(
            $directive->openingTag(), [$directive, 'openingHandler']
        );

        $blade->getCompiler()->directive(
            $directive->closingTag(), [$directive, 'closingHandler']
        );

        $blade->getCompiler()->directive(
            $directive->alternatingTag(), [$directive, 'alternatingHandler']
        );

        return $blade;
    }

    /**
     * Tear down function.
     */
    public function tearDown()
    {
        list($resource, $view, $cache) = $this->getDirectories();

        $this->deleteDirectory($view);
        $this->deleteDirectory($cache);
        $this->deleteDirectory($resource);
    }

    /**
     * Helper to set the directories.
     *
     * @return array
     */
    private function getDirectories()
    {
        $resource = __DIR__ . '/../resources';
        $view =     __DIR__ . '/../resources/views';
        $cache =    __DIR__ . '/../resources/cache';

        return array($resource, $view, $cache);
    }

    /**
     * Delete a directory with recursive check.
     *
     * @param $dir
     * @return bool
     */
    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
}
