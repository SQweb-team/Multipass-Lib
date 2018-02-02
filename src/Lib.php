<?php

namespace Multipass\Core;

use Carbon\Carbon;
use HtmlGenerator\HtmlTag as Html;

class Lib
{
    /**
     * Generates HTML to output the script based on user configuration.
     *
     * @param   array   $config     User settings
     * @param   string  $version    The related SDK version
     * @return  string
     */
    public static function generateScript($config, $version)
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
            ->set('type', 'text/javascript')
            ->text("/* $version */ var mltpss = new Multipass.default($settings)");

        return $tag . PHP_EOL . $tag2;
    }

    /**
     * Generates HTML to output an article lock.
     *
     * @param   string  $locale
     * @param   sting   $type   Type of lock ('locking' or 'support')
     * @return  \HtmlGenerator\HtmlTag
     */
    public static function generateArticleLock($locale, $type = 'locking')
    {
        $tr = new Translator($locale);

        // Main structure of the generated HTML
        $html = Html::createElement('div')
            ->addClass('footer__mp__normalize')
            ->addClass('footer__mp__button_container')
            ->addClass('sqw-paywall-button-container');
        $header = $html->addElement('div')->addClass('footer__mp__button_header');
        $body = $html->addElement('div')
            ->addClass('footer__mp__normalize')
            ->addClass('footer__mp__button_cta')
            ->set('onclick', 'mltpss.modal_first(event)');
        $footer = $html->addElement('div')
            ->addClass('footer__mp__normalize')
            ->addClass('footer__mp__button_footer');
        // Internals
        $header->addElement('div')
            ->addClass('footer__mp__button_header_title')
            ->text($tr->get("$type.warning"));
        $header->addElement('div')
            ->addClass('footer__mp__button_signin')
            ->set('onclick', 'mltpss.modal_first(event)')
            ->text($tr->get('common.already_sub') . ' ')
            ->addElement('span')
            ->addClass('footer__mp__button_login')
            ->addClass('footer__mp__button_strong')
            ->text($tr->get('common.login'));
        $body->addElement('a')
            ->addClass('footer__mp__cta_fresh')
            ->set('href', '#')
            ->text($tr->get("$type.unlock"));
        $footer->addElement('p')
            ->addClass('footer__mp__normalize')
            ->addClass('footer__mp__button_p')
            ->text($tr->get('common.desc'));
        $footer->addElement('a')
            ->addClass('footer__mp__button_discover')
            ->addClass('footer__mp__button_strong')
            ->set('href', $tr->get('common.href'))
            ->set('target', '_blank')
            ->addElement('span')
            ->addClass('footer__mp__button_footer_txt')
            ->text($tr->get('common.discover'));

        return $html;
    }

    /**
     * Generates HTML to output an End-of-Article block.
     *
     * @param   string  $locale
     * @return  \HtmlGenerator\HtmlTag
     */
    public static function generateEndOfArticle($locale)
    {
        $tr = new Translator($locale);

        // Main structure of the generated HTML
        $html = Html::createElement('div')->addClass('sqw-article-footer-container');
        $body = $html->addElement('div')->addClass('sqw-article-footer-body');
        $footer = $html->addElement('div')
            ->addClass('sqw-article-footer-footer')
            ->set('onclick', 'mltpss.modal_first(event)');
        // Internals
        $body->addElement('div')
            ->addClass('sqw-article-footer-body-title')
            ->text($tr->get('end_of_article.title'));
        $body->addElement('div')
            ->addClass('sqw-article-footer-body-content1')
            ->text($tr->get('end_of_article.sentence_1'));
        $body->addElement('div')
            ->addClass('sqw-article-footer-body-content2')
            ->text($tr->get('end_of_article.sentence_2'));
        $body->addElement('div')
            ->addClass('sqw-article-footer-body-content3')
            ->text($tr->get('end_of_article.sentence_3'));
        $footer->addElement('div')
            ->addClass('sqw-article-footer-footer-text')
            ->text($tr->get('end_of_article.support'));
        $footer->addElement('div')
            ->addClass('sqw-article-footer-footer-logo-container');

        return $html;
    }

    /**
     * Generates HTML to output a Multipass button.
     *
     * @param   string  $size
     * @return  \HtmlGenerator\HtmlTag
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

    /**
     * Cuts a string based on a percentage, and applies HTML tags to
     * give a fade-out effect.
     *
     * @param   string  $text       The string to manipulate
     * @param   string  $percentage The percentage of text to display
     * @return  string
     */
    public static function generateFadeOutText($text, $percentage)
    {
        $words = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach (array_keys($words, ' ', true) as $key) {
            unset($words[$key]);
        }

        $words = array_values($words);
        $count = ceil(count($words) / 100 * $percentage);
        $alpha = 1;
        $lambda = (1 / $count);
        $tags = [];

        for ($i = 0; $i < $count; $i++) {
            if (!preg_match('/<.+?>/', $words[$i])) {
                $opacity = number_format($alpha, 2);
                $tags[] = "<span style=\"opacity:$opacity\">$words[$i]</span>";
                $alpha -= $lambda;
            } else {
                $tags[] = $words[$i];
                $count++;
            }
        }

        return (new self)->closeTags(implode(' ', $tags));
    }

    /**
     * Get user subscription status.
     *
     * @param   string  $api_token  The user token
     * @param   int     $website_id The website ID
     * @param   string  $user_agent The SDK user agent
     * @return  int
     */
    public static function getUserCredits($api_token, $website_id, $user_agent)
    {
        $response = (new self)->apiCall($api_token, $website_id, $user_agent);

        return $response ? $response->credit : 0;
    }

    /**
     * Determines whether a date is greater than or equal to now.
     *
     * @param   string  $origin The date to compare. Format must be ISO (YYYY-MM-DD).
     * @param   int     $delay  A number of days to add to the original date.
     * @return  bool
     */
    public static function isDateGreaterThanNow($origin, $delay)
    {
        $date = Carbon::createFromFormat('Y-m-d', $origin)
            ->addDay($delay);

        return Carbon::now()->gte($date);
    }

    /**
     * Determines whether a URI should be limited.
     *
     * @param   int     $limit  The number of maximum URIs.
     * @return  bool
     */
    public static function isUriLimited($limit)
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (!isset($_COOKIE['sqwBlob'])) {
            $expires_at = time() + 24 * 60 * 60;
            $cookie = [
                [$uri],
                $expires_at
            ];

            (new self)->makeCookie('sqwBlob', serialize($cookie), $expires_at);

            return true;
        }

        $cookie = unserialize($_COOKIE['sqwBlob']);

        if (!in_array($uri, $cookie[0])) {
            if (count($cookie[0]) > $limit - 1) {
                return false;
            }

            $cookie[0][] = $uri;
            (new self)->makeCookie('sqwBlob', serialize($cookie), $cookie[1]);
        }

        return true;
    }

    /**
     * Queries the Multipass API.
     *
     * @param   string  $api_token  The user token
     * @param   int     $website_id The website ID
     * @param   string  $user_agent The SDK user agent
     * @return  int
     */
    private function apiCall($api_token, $website_id, $user_agent)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.multipass.net/token/check',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 1000,
            CURLOPT_TIMEOUT_MS => 1000,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_POSTFIELDS => [
                'token' => $api_token,
                'site_id' => $website_id,
            ],
        ]);

        $response = json_decode(curl_exec($ch));

        if ($response === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($response->status !== true) {
            throw new \Exception('There was a problem while querying the Multipass API, please try again later.');
        }

        return $response;
    }

    /**
     * Closes unclosed tags in an html string.
     *
     * @param   string  $html   The html string to manipulate
     * @return  string
     */
    private function closeTags($html)
    {
        $dom = new \DOMDocument;
        $dom->loadHTML($html);
        $mock = new \DOMDocument;
        $body = $dom->getElementsByTagName('body')->item(0);

        foreach ($body->childNodes as $child) {
            $mock->appendChild($mock->importNode($child, true));
        }

        $fixed = trim($mock->saveHTML());

        return $fixed;
    }

    /**
     * Create a cookie with some defaults.
     *
     * @param   string  $name   The name of the cookie
     * @param   string  $value  The value of the cookie
     * @param   int     $life   A Unix timestamp representing the time the cookie expires
     * @return  null
     */
    private function makeCookie($name, $value, $life)
    {
        setcookie($name, $value, $life, '', '', isset($_SERVER['HTTPS']), true);
    }
}
