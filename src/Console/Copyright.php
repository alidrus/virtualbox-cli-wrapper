<?php

namespace XDMS\Console;

/**
 * Class Copyright
 * @author Abu Bakar Al-Idrus
 */
class Copyright
{
    /**
     * Get copyright message.
     */
    public function get()
    {
        return 'Copyright (c) 2016 '.( @date('Y') > '2016' ? '- '.@date('Y') : '' ).', XDMS Sdn Bhd. All rights reserved.';
    }
}
