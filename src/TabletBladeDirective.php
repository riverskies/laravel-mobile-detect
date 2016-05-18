<?php

namespace Riverskies\Laravel\MobileDetect;

use Detection\MobileDetect;
use Riverskies\Laravel\MobileDetect\Contracts\BladeDirectiveInterface;

class TabletBladeDirective implements BladeDirectiveInterface
{
    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * TabletBladeDirective constructor.
     *
     * @param MobileDetect $mobileDetect
     */
    public function __construct(MobileDetect $mobileDetect)
    {
        $this->mobileDetect = $mobileDetect;
    }

    /**
     * Returns the Blade opening tag.
     *
     * @return string
     */
    public function openingTag()
    {
        return 'tablet';
    }

    /**
     * Compiles the Blade opening.
     *
     * @param $expression
     * @return mixed
     */
    public function openingHandler($expression)
    {
        $shouldDisplay = $this->shouldDisplayForTablet() ? "true" : "false";

        return "<?php if ({$shouldDisplay}) : ?>";
    }

    /**
     * Returns the Blade closing tag.
     *
     * @return mixed
     */
    public function closingTag()
    {
        return 'endtablet';
    }

    /**
     * Compiles the Blade closing.
     *
     * @param $expression
     * @return mixed
     */
    public function closingHandler($expression)
    {
        return "<?php endif; ?>";
    }

    /**
     * Returns the Blade alternating tag.
     *
     * @return mixed
     */
    public function alternatingTag()
    {
        return 'elsetablet';
    }

    /**
     * Compiles the Blade alternating tag.
     *
     * @param $expression
     * @return mixed
     */
    public function alternatingHandler($expression)
    {
        return "<?php else: ?>";
    }

    /**
     * @return bool
     */
    private function shouldDisplayForTablet()
    {
        return $this->mobileDetect->isTablet();
    }
}
