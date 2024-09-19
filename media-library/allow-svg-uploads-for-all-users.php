<?php
/** 
* Author: UnmaskWP.com
* GitHub Link: https://github.com/MayankMajeji/Wordpress-Code-Snippets
* Post Link: https://unmaskwp.com/snippets/allow-svg-uploads-in-wordpress/
*/ 

/**
 * Add Mime Types
 */
function maverick_svgs_upload_mimes($mimes = array())
{

    if (current_user_can('administrator')) {

        // allow SVG file upload
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';

        return $mimes;
    } else {

        return $mimes;
    }
}
add_filter('upload_mimes', 'maverick_svgs_upload_mimes', 99);

/**
 * Check Mime Types
 */
function maverick_svgs_upload_check($checked, $file, $filename, $mimes)
{

    if (!$checked['type']) {

        $check_filetype        = wp_check_filetype($filename, $mimes);
        $ext                = $check_filetype['ext'];
        $type                = $check_filetype['type'];
        $proper_filename    = $filename;

        if ($type && 0 === strpos($type, 'image/') && $ext !== 'svg') {
            $ext = $type = false;
        }

        $checked = compact('ext', 'type', 'proper_filename');
    }

    return $checked;
}

add_filter('wp_check_filetype_and_ext', 'maverick_svgs_upload_check', 10, 4);

/**
 * Mime Check fix for WP 4.7.1 / 4.7.2
 *
 * Fixes uploads for these 2 version of WordPress.
 * Issue was fixed in 4.7.3 core.
 */
function maverick_svgs_allow_svg_upload($data, $file, $filename, $mimes)
{

    global $wp_version;
    if ($wp_version !== '4.7.1' || $wp_version !== '4.7.2') {
        return $data;
    }

    $filetype = wp_check_filetype($filename, $mimes);

    return [
        'ext'                => $filetype['ext'],
        'type'                => $filetype['type'],
        'proper_filename'    => $data['proper_filename']
    ];
}

add_filter('wp_check_filetype_and_ext', 'maverick_svgs_allow_svg_upload', 10, 4);

