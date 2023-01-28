<?php

/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

defined('_DOREW') or die('Access denied');

class FormURI extends \Twig\Extension\AbstractExtension
{

  public function __construct()
  {
      global $sjc_gold;
      $this->sjc_gold = $sjc_gold;
  }

  public function getFunctions()
  {
    return [
      new \Twig\TwigFunction('request_method', [$this, 'request_method']),
      new \Twig\TwigFunction('get_post', [$this, 'get_post']),
      new \Twig\TwigFunction('get_get', [$this, 'get_get']),
      new \Twig\TwigFunction('get_youtube_id', [$this, 'get_youtube_id']),
      new \Twig\TwigFunction('get_youtube_title', [$this, 'get_youtube_title']),

      new \Twig\TwigFunction('get_uri_segments', [$this, 'get_uri_segments']),
      new \Twig\TwigFunction('redirect', [$this, 'redirect']),
      new \Twig\TwigFunction('cancel_xss', [$this, 'cancel_xss']),
      new \Twig\TwigFunction('current_url', [$this, 'current_url']),
      new \Twig\TwigFunction('rwurl', [$this, 'rwurl']),
      
      new \Twig\TwigFunction('tygia_sjc', [$this, 'tygia_sjc']),
    ];
  }


  /* FORM HANDLING METHODS */

  function request_method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  function get_post($string, $check = null)
  {
    if (strtolower($check) == 'list') return $_POST;
    else return htmlspecialchars(addslashes($_POST[$string]));
  }

  function get_get($string)
  {
    return htmlspecialchars(addslashes($_GET[$string]));
  }

  function get_youtube_id($url)
  {
    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $url, $matches);
    return $matches[1];
  }

  function get_youtube_title($url)
  {
    $youtube_id = $this->get_youtube_id($url);
    $youtube_title = file_get_contents("https://www.youtube.com/watch?v=$youtube_id");
    preg_match("/<title>(.*)<\/title>/", $youtube_title, $matches);
    return str_replace(' - YouTube', '', $matches[1]);
  }

  /* URI */

  function get_uri_segments()
  {
    global $uri_path;
    $uri_segments = explode('/', $uri_path);
    return $uri_segments;
  }

  function redirect($url)
  {
    return header('Location: ' . $url);
  }

  function cancel_xss($string)
  {
    $string = preg_replace('/\\\\/', '\\', $string);
    $string = str_replace('\&quot;', '&quot;', $string);
    $string = str_replace('\"', '&quot;', $string);
    $string = str_replace("\'", '&#039;', $string);
    return $string;
  }

  function current_url()
  {
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
  }

  /* TỶ GIÁ VÀNG SJC */
  function tygia_sjc($get_city = null, $type = null)
  {
    $xml_url = $this->sjc_gold;
    $xml = simplexml_load_file($xml_url);
    $city = $xml->xpath('//city[@name="' . $get_city . '"]');
    $item = $city[0]->item;
    $buy = $item['buy'];
    $sell = $item['sell'];
    if ($get_city == 'Hồ Chí Minh') {
      switch ($type) {
        case '1':
          $type_name = 'Vàng SJC 1L - 10L';
          break;
        case '2':
          $type_name = 'Vàng nhẫn SJC 99,99 1 chỉ, 2 chỉ, 5 chỉ';
          break;
        case '3':
          $type_name = 'Vàng nhẫn SJC 99,99 0,5 chỉ';
          break;
        case '4':
          $type_name = 'Vàng nữ trang 99,99%';
          break;
        case '5':
          $type_name = 'Vàng nữ trang 99%';
          break;
        case '6':
          $type_name = 'Vàng nữ trang 75%';
          break;
        case '7':
          $type_name = 'Vàng nữ trang 58,3%';
          break;
        case '8':
          $type_name = 'Vàng nữ trang 41,7%';
          break;
      }
      $item = $city[0]->xpath('//item[@type="' . $type_name . '"]');
      $buy = $item[0]['buy'];
      $sell = $item[0]['sell'];
    }
    return [
      'buy' => $buy,
      'sell' => $sell
    ];
  }
}
