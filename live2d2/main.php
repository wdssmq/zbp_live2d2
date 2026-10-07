<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('live2d2')) {
  $zbp->ShowError(48);
  die();
}

$blogtitle = '看板娘';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

$act = GetVars('act', 'GET');
$suc = GetVars('suc', 'GET');

if (GetVars('act', 'GET') == 'save') {
  CheckIsRefererValid();
  foreach ($_POST as $key => $val) {
    // $_POST[$key] = trim($val);
    $zbp->Config('Live2D2')->$key = trim($val);
  }
  $zbp->SaveConfig('Live2D2');
  $zbp->SetHint('good');
  Redirect('./main.php' . ($suc === null ? '' : "?act=$suc"));
} else {
  InstallPlugin_live2d2();
}
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?> <small><a href="main.php" title="刷新">刷新</a></small></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require_once "about.php"; ?>
  </div>
  <div id="divMain2">
    <form action="<?php echo BuildSafeURL("main.php?act=save"); ?>" method="post">
      <table width="100%" class="tableBorder">
        <tr>
          <th width="10%">项目</th>
          <th>内容</th>
          <th width="45%">说明</th>
        </tr>
        <tr>
          <td>人物选择</td>
          <td><?php echo zbpform::select('model', array('histoire' => '伊斯特瓦尔', 'nep' => '涅普迪努'), $zbp->Config("Live2D2")->model); ?></td>
          <td></td>
        </tr>
        <tr>
          <td>音乐</td>
          <td><?php echo zbpform::text("music", $zbp->Config("Live2D2")->music, "90%"); ?></td>
          <td></td>
        </tr>
        <tr>
          <td><input type="submit" value="提交" /></td>
          <td colspan="2"></td>
        </tr>
      </table>
    </form>
    ---------
    <p>注：需要将.moc和.mtn两个后缀的MIME类型设置为：application/octet-stream</p>
    <p>基于如下代码实现:</p>
    <p>https://github.com/eeg1412/Live2dHistoire</p>
    <p>本项目源码库：</p>
    <p>https://github.com/wdssmq/Live2D-For-Z-BlogPHP</p>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
