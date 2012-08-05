<?php
/**
 * Functions for block
 * @version $Rev: 255 $
 * @link $URL: https://ajax-discuss.svn.sourceforge.net/svnroot/ajax-discuss/openid/trunk/openid/blocks/openidurl.php $
 */
function b_openid_login_show($options)
{
    global $xoopsUser, $xoopsModule, $xoopsTpl;
    if (is_object(@$xoopsUser)) {
        return false;
    }
    if (is_object(@$xoopsModule) && $xoopsModule->getVar('dirname') == 'openid') {
        return false;
    }
    if (defined('OPENID_LOGIN_FORM_RENDERED')) {
        return false;
    }
    define('OPENID_LOGIN_FORM_RENDERED', true);
    $label = empty($options[0]) ? '' : trim($options[0]);

    $moduleHandler = xoops_gethandler('module');
    $module = $moduleHandler->getByDirname('openid');
    $configHandler = xoops_gethandler('config');
    $config = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    include_once XOOPS_ROOT_PATH . '/modules/openid/class/handler/buttons.php';
    $handler_buttons = new Openid_Handler_Buttons();
    $buttons =& $handler_buttons->getObjects();

    $frompage = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES);

    $script = '<script type="text/javascript" src="'.XOOPS_URL.'/modules/openid/resource/openid.js"></script>'."\n";
    $xoopsTpl->assign('xoops_module_header', $script . $xoopsTpl->get_template_vars('xoops_module_header'));

    return array(
        'label' => $label,
        'buttons' => $buttons,
        'allowInput' => $config['show_free_input_box'],
        'frompage' => $frompage,
        'login' => _MB_OPENID_LOGIN,
        'loading' => _MB_OPENID_LOADING,
        'input_id' => _MB_OPENID_INPUT_ID
    );
}

function b_openid_login_edit($options)
{
    $label = empty($options[0]) ? '' : trim($options[0]);

    $form = '
     <table width="100%">
      <tr>
       <td width="40%">' . _MB_OPENID_DESCRIPTION . '</td>
       <td><input type="text" name="options[0]" value="' . $label . '" /></td>
      </tr>
     </table>
    ';
    return $form;
}
?>