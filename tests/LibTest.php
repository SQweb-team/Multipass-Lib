<?php

namespace Multipass\Core;

class LibTest extends \PHPUnit\Framework\TestCase
{
    private $config;

    public function setUp()
    {
        $this->config = [
            'id_site'           => 1,
            'sitename'          => 'Multipass Testing',
            'message'           => null,
            'adblock_modal'     => null,
            'targeting'         => null,
            'beacon'            => null,
            'debug'             => null,
            'dwide'             => null,
            'autologin'         => null,
            'lang'              => null,
            'login'             => null,
            'login_tiny'        => null,
            'connected'         => null,
            'connected_s'       => null,
            'connected_tiny'    => null,
            'connected_support' => null,
            'btn_noads'         => null,
            'btn_unlimited'     => null,
            'support'           => null,
        ];
    }

    public function testGenerateScript()
    {

        // redo, check for specific, crucial part of the html -- no need to check everything because output is likely to change in the future

        $script = Lib::generateScript($this->config, 'Multipass/Tests');

        $this->assertEquals($script, '<script src="https://cdn.multipass.net/mltpss.min.js" type="text/javascript"></script>' . PHP_EOL . '<script type="text/javascript">/* Multipass/Tests */ var mltpss = new Multipass.default({"wsid":1,"sitename":"Multipass Testing","debug":null,"adblock_modal":null,"targeting":null,"beacon":null,"dwide":null,"locale":null,"msg":null,"autologin":null,"user_strings":{"login":null,"login_tiny":null,"connected":null,"connected_tiny":null,"connected_s":null,"connected_support":null,"btn_unlimited":null,"btn_noads":null,"support":null}})</script>');
    }

    public function testGenerateFadeOutText()
    {

        // redo, check for specific, crucial part of the html -- no need to check everything because output is likely to change in the future

        // Test with a normal string, 50%
        $text = 'The web premium masterkey! One subscription, to access exclusive content, browse without ads, support journalists and content creators. Valid on all our partner websites. No commitment, no installation.';

        $this->assertEquals(Lib::generateFadeOutText($text, 50), '<span style="opacity:1.00">The</span> <span style="opacity:0.93">web</span> <span style="opacity:0.87">premium</span> <span style="opacity:0.80">masterkey!</span> <span style="opacity:0.73">One</span> <span style="opacity:0.67">subscription,</span> <span style="opacity:0.60">to</span> <span style="opacity:0.53">access</span> <span style="opacity:0.47">exclusive</span> <span style="opacity:0.40">content,</span> <span style="opacity:0.33">browse</span> <span style="opacity:0.27">without</span> <span style="opacity:0.20">ads,</span> <span style="opacity:0.13">support</span> <span style="opacity:0.07">journalists</span>');

        // Test a string with html tags, 30%
        $text = '<span class="strong">The web premium masterkey!</span><p>One subscription, to access exclusive content, browse without ads, support journalists and content creators. Valid on all our partner websites. No commitment, no installation.</p>';

        $this->assertEquals(Lib::generateFadeOutText($text, 30), '<span class="strong"> <span style="opacity:1.00">The</span> <span style="opacity:0.90">web</span> <span style="opacity:0.80">premium</span> <span style="opacity:0.70">masterkey!</span> </span> <p> <span style="opacity:0.60">One</span> <span style="opacity:0.50">subscription,</span> <span style="opacity:0.40">to</span> <span style="opacity:0.30">access</span> <span style="opacity:0.20">exclusive</span> <span style="opacity:0.10">content,</span></p>');
    }

    public function testGetUserCredits()
    {
        // $this->assertEquals(Lib::getUserCredits('test-string', 1, 'Multipass/Unit'), 0);
        // how tf do i even check this bs
    }
}
