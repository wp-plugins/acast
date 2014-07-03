<?php
/*
Plugin Name: acast
Plugin URI: http://wordpress.org/plugins/acast/
Description: [acast src="http://www.acast.com/channel/acast" width="640" height="360"] shortcode 
Version: 1.0
Author: acast.com
Author URI: http://www.acast.com
License: GPLv3
*/

if (!function_exists("acast_embed_shortcode")):

    function acast_embed_shortcode($attr, $content = null ) {

        $defaults = array(
            "src" => "",
            "https" => "off",
            "width" => "640",
            "height" => "360",
            "scrolling" => "no",
            "class" => "acast-embed-player",
            "style" => "border:none; overflow:hidden;",
            "frameborder" => "0"
        );

        foreach ($defaults as $key => $value) {
            if (!@array_key_exists($key, $attr)) {
                $attr[$key] = $value;
            }
        }

        $re = "/^((https?:\/\/)?(www\.|embed\.)?acast\.com)?\/?([^\/]+)\/([^\?\/]+)/"; 
        if (!preg_match($re, $attr["src"], $matches)) {
            return "<strong style='color:#c00;'>[ACAST ERROR] No acast provided</strong>";
        }

        $http = $attr["https"] == "on" ? "https://" : "http://";
        $host = "embed.acast.com";
        
        //Create embed url
        $src = $http . $host . "/" . $matches[4] . "/" . $matches[5];

        //Discard settings that are not attributes
        $discard = array("https");
        
        $html = "<iframe src='".$src."'";
        foreach($attr as $key => $value ) {
            if (in_array($key, $discard)){
                continue;
            }
            $html .= " " . $key;
            $html .= ($value !== "") ? "='" . $value . "'" : "";
        }
        $html .= "></iframe>\n";

        return $html;
    }
    
    add_shortcode("acast", "acast_embed_shortcode" );
    
endif; 