<?php
use Lite\I18N\Lang;

$LITE_PATH = __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
$NAMESPACE = 'Lite';

//注册自动加载库文件
spl_autoload_register(function($className) use ($LITE_PATH, $NAMESPACE){
	if(strpos($className, $NAMESPACE.'\\') === 0){
		$file = str_replace($NAMESPACE.'\\', $LITE_PATH, $className);
		$file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
		$file = $file.'.php';
		if(is_file($file)){
			require_once $file;
		}
	}
});

//绑定LitePHP翻译
if(function_exists('gettext')){
	Lang::addDomain(Lang::DOMAIN_LITEPHP, $LITE_PATH.'/I18N/litephp_lang', ['en_US', 'zh_CN'], 'en_US');
}
