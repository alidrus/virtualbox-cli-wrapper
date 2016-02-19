<?php

namespace XDMS;

/**
 * Class Copyright
 * @author Abu Bakar Al-Idrus
 */
class Copyright
{
    /**
     * Get copyright message.
     */
    public static function get()
    {
        $years = '2016'.( date('Y') > '2016' ? ' - '.@date('Y') : '' );

        return 'Copyright (c) '.$years.', XDMS Sdn Bhd. All rights reserved.';
    }
}
