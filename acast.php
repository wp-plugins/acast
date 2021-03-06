<?php
/*
Plugin Name: acast
Plugin URI: http://wordpress.org/plugins/acast/
Description: [acast src="http://www.acast.com/channel/acast" width="480" height="480"] shortcode 
Version: 0.4
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
            "width" => "480",
            "height" => "480",
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

        $re = "/^((https?:\/\/)?(www\.|embed\.)?acast\.com)?\/?([^\/]+)(\/([^\?\/]+))?/"; 
        if (!preg_match($re, $attr["src"], $matches)) {
            return "<strong style='color:#c00;'>[ACAST ERROR] No acast provided</strong>";
        }

        $http = $attr["https"] == "on" ? "https://" : "http://";
        
        //Create embed url
        $channel = $matches[4];
        $acast = $matches[6];
        if (empty($acast)) {
            $src = $http . "embed.acast.com/" . $channel;
        } else {
            $src = $http . "embed.acast.com/" . $channel . "/" . $acast;
            $rss = $http . "rss.acast.com/" . $channel . "/" . $acast . "/media.mp3";
        }
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
                "padding-bottom:100%",
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
        if(!empty($rss)) {
            $html .= "<audio preload=\"none\" src=\"".$rss."\" style=\"display:none;\"><a href=\"".$rss."\" style=\"display:none;\">Podcast link</a></audio>";
        }

        if($responsive) {
            $html .= "</div>";
        }

        return $html;
    }
    
    add_shortcode("acast", "acast_embed_shortcode" );
    
endif; 