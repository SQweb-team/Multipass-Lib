<?php

namespace Multipass\Core;

use HtmlGenerator\HtmlTag as Html;

class Lib
{
    /**
     *  Generates HTML to output the script based on user configuration.
     *
     *  @param array $config User settings
     *  @return string
     */
    public static function generateScript($config)
    {
        $cdn = 'https://cdn.multipass.net/mltpss.min.js';

        $settings = json_encode([
            'wsid' => $config['id_site'],
            'sitename' => $config['sitename'],
            'debug' => $config['debug'],
            'adblock_modal' => $config['adblock_modal'],
            'targeting' => $config['targeting'],
            'beacon' => $config['beacon'],
            'dwide' => $config['dwide'],
            'locale' => $config['lang'],
            'msg' => $config['message'],
            'autologin' => $config['autologin'],
            // User's custom strings for button customization
            'user_strings' => [
                'login' => $config['login'],
                'login_tiny' => $config['login_tiny'],
                'connected' => $config['connected'],
                'connected_tiny' => $config['connected_tiny'],
                'connected_s' => $config['connected_s'],
                'connected_support' => $config['connected_support'],
                'btn_unlimited' => $config['btn_unlimited'],
                'btn_noads' => $config['btn_noads'],
                'support' => $config['support'],
            ],
        ]);

        $tag = Html::createElement('script')
            ->set('src', $cdn)
            ->set('type', 'text/javascript');
        $tag2 = Html::createElement('script')
            ->text($settings);

        return $tag . PHP_EOL . $tag2;
    }

    public static function generateLockingBlock($locale)
    {
    }

    public static function generateSupportBlock($locale)
    {
        $tr = new Translator($locale);
        echo $tr->get('support_block.sentence1');
    }

    /**
     *  Generates HTML to output a Multipass button.
     *
     *  @param string $size
     *  @return HtmlTag instance
     */
    public static function generateButton($size)
    {
        $button = Html::createElement('div')->addClass('sqweb-button');

        switch($size) {
            case 'tiny':
                return $button->addClass('multipass-tiny');
            case 'slim':
                return $button->addClass('multipass-slim');
            case 'large':
                return $button->addClass('multipass-large');
            case 'support':
                return $button->removeClass('sqweb-button')->addClass('sqweb-support');
            default:
                return $button;
        }
    }
}
