<?php
use Lite\I18N\Lang;

//Bind default lang for Litephp
if(function_exists('gettext')){
	Lang::addDomain(Lang::DOMAIN_LITEPHP, __DIR__.'/I18N/litephp_lang', ['en_US', 'zh_CN'], 'zh_CN');
}