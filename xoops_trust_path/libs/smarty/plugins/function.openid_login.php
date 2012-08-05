<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     openid_login
 * Version:  1.0
 * 
 * Examples: {openid_login}
 * -------------------------------------------------------------
 */
function smarty_function_openid_login($params, &$smarty)
{
    global $xoopsConfig;
    $fileName = XOOPS_ROOT_PATH . '/modules/openid/language/' . $xoopsConfig['language'] . '/blocks.php';
    if (file_exists($fileName)) {
        include_once $fileName;
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/openid/language/english/blocks.php';
    }
    require_once XOOPS_ROOT_PATH . '/modules/openid/blocks/openid_login.php';
    $block = b_openid_login_show(array(''));

    if ($block) {
        $script = '<script type="text/javascript" src="'.XOOPS_URL.'/modules/openid/resource/openid.js"></script>'."\n";
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $tpl = new XoopsTpl();
        $tpl->assign('block', $block);
        return $script . $tpl->fetch('db:openid_login.html');
    } else {
        return '';
    }
}