<?php

//注册插件
RegisterPlugin('live2d2', 'ActivePlugin_live2d2');

function ActivePlugin_live2d2()
{
    Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'live2d2_include');
}

function live2d2_include(&$templates)
{
    // global $zbp;
    $templates['header'] = str_replace('{$header}', '{$header}' . '<link rel="stylesheet" href="' . live2d2_Path('css', 'host') . '" />', $templates['header']);
    $templates['footer'] = str_replace('{$footer}', '{$footer}' . live2d2_GetHTML(), $templates['footer']);
}
function live2d2_GetHTML()
{
    global $zbp;

    $message_Path = $zbp->host . 'zb_users/plugin/live2d2/usr/';
    $model_Name = $zbp->Config('Live2D2')->model ?: 'nep';
    $model_Path = $zbp->host . "zb_users/plugin/live2d2/var/model/{$model_Name}/";
    $model_File = $zbp->path . "zb_users/plugin/live2d2/var/model/{$model_Name}/model.json";
    $model_textures = json_decode(file_get_contents($model_File))->textures;
    $model_textures = json_encode($model_textures);

    $js1 = live2d2_Path('js-live2d', 'host');
    $js2 = live2d2_Path('js-message', 'host');
    $music = $zbp->Config('Live2D2')->music;
    if (!empty($music)) {
        $music = htmlspecialchars($music, ENT_QUOTES);
        $musicHTML = '<audio src="" style="display:none;" id="live2d_bgm" data-bgm="0" preload="none"></audio>';
        $musicHTML .= "<input name=\"live2dBGM\" value=\"{$music}\" type=\"hidden\">";
    } else {
        $musicHTML = '';
    }
    $home_Path = $zbp->host;
    $str = file_get_contents(live2d2_Path('html'));
    if (false === $str) {
        return '';
    }
    $str = str_replace(
        ['{$message_Path}', '{$model_Name}', '{$model_Path}', '{$model_textures}', '{$home_Path}', '{$musicHTML}', '{$js1}', '{$js2}'],
        [$message_Path, $model_Name, $model_Path, $model_textures, $home_Path, $musicHTML, $js1, $js2],
        $str,
    );

    return $str;
}

function live2d2_Path($file, $t = 'path')
{
    global $zbp;
    $result = $zbp->{$t} . 'zb_users/plugin/live2d2/';

    switch ($file) {
    case 'css':
      return $result . 'var/css/live2d.css?v=20230624101';

      break;

    case 'js-live2d':
      return $result . 'var/js/live2d.js?v=20230624101';

      break;

    case 'js-message':
      return $result . 'var/js/message.js?v=20230624101';

      break;

    case 'u-json':
      return $result . 'usr/message.json';

      break;

    case 'v-json':
      return $result . 'var/message.json';

      break;

    case 'html':
      return $result . 'var/template.html';

      break;

    case 'main':
      return $result . 'main.php';

      break;

    default:
      return $result . $file;
  }
}

function InstallPlugin_live2d2()
{
    global $zbp;
    $filesList = ['json'];
    foreach ($filesList as $key => $value) {
        $uFile = live2d2_Path("u-{$value}");
        $vFile = live2d2_Path("v-{$value}");
        if (!is_file($uFile)) {
            @mkdir(dirname($uFile));
            copy($vFile, $uFile);
        }
    }
    if (!$zbp->HasConfig('Live2D2') || !$zbp->Config('Live2D2')->HasKey('model')) {
        $zbp->Config('Live2D2')->model = 'histoire';
        $zbp->SaveConfig('Live2D2');
    }
    $zbp->BuildTemplate();
}

function UninstallPlugin_live2d2()
{
    global $zbp;
    Remove_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'live2d2_include');
    $zbp->BuildTemplate();
}
