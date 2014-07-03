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

        $html = "";

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
        //Responsive?
        $responsive = ($attr["width"] == "100%" || $attr["responsive"] == "on");

        if ($responsive) {
            //iframe
            $attr["height"] = "auto";
            $attr["width"] = "100%";
            $attr["style"].= implode(";", array(
                "position:absolute",
                "top:0",
                "left:0",
                "width:100%",
                "height:100%"
                ));
            //Wrapper
            $style_wrapper = implode(";", array(
                "width:100%",
                "position:relative",
                "padding-bottom:56.25%",
                "height:0",
                "overflow:hidden"
                ));

            $html .= "<div class='acast-embed-player-wrapper' style='".$style_wrapper."'>";
        }

        //Discard settings that are not attributes
        $discard = array("https");
        
        $html .= "<iframe src='".$src."'";
        foreach($attr as $key => $value ) {
            if (in_array($key, $discard)){
                continue;
            }
            $html .= " " . $key;
            $html .= ($value !== "") ? "='" . $value . "'" : "";
        }
        $html .= "></iframe>\n";

        if($responsive) {
            $html .= "</div>";
        }

        return $html;
    }
    
    add_shortcode("acast", "acast_embed_shortcode" );
    
endif; 