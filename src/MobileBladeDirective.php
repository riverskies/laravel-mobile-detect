<?php

namespace Riverskies\Laravel\MobileDetect;

use Detection\MobileDetect;
use Riverskies\Laravel\MobileDetect\Contracts\BladeDirectiveInterface;

class MobileBladeDirective implements BladeDirectiveInterface
{
    /**
     * @var MobileDetect
     */
    private $mobileDetect;

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
        return 'mobile';
    }

    /**
     * Compiles the Blade opening.
     *
     * @param $expression
     * @return mixed
     */
    public function openingHandler($expression)
    {
        $shouldDisplay = $this->shouldDisplayForMobile() ? "true" : "false";

        return "<?php if ({$shouldDisplay}) : ?>";
    }

    /**
     * Returns the Blade closing tag.
     *
     * @return mixed
     */
    public function closingTag()
    {
        return 'endmobile';
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
        return 'elsemobile';
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
    private function shouldDisplayForMobile()
    {
        return $this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet();
    }
}
