<?php

namespace App\Policies\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class AppPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addNonceForDirective(Directive::SCRIPT)

            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addNonceForDirective(Directive::STYLE)

            ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'data:',
            ])

            ->addDirective(Directive::FONT, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::FRAME_ANCESTORS, Keyword::NONE)
            ->addDirective(Directive::BASE_URI, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF);
    }
}