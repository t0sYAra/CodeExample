<?php ini_set('display_errors', '1'); ?>
<!DOCTYPE html>
<html>
<head>
<title><?=$title?></title>
<meta charset="utf-8">
<meta name="description" content="<?=$description?>"> 
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--[if lt IE 9]>
<script>
var e = ("article,aside,figcaption,figure,footer,header,hgroup,nav,section,time").split(',');
for (var i = 0; i < e.length; i++) {
	document.createElement(e[i]);
}
</script>
<![endif]-->
<script>
var page='<?=$page?>';
var dir='<?=$pathStart?>';
var ifShowMobMenu=1;
</script>
<?=$jsFiles?>
<?=$prefetchFiles?>
<link href="<?=$pathStart?>css/styles.css" rel="stylesheet" type="text/css">
<link rel="apple-touch-icon" sizes="180x180" href="<?=$pathStart?>img/favicon/apple-touch-icon.png?v=kPxM4w57bz">
<link rel="icon" type="image/png" sizes="32x32" href="<?=$pathStart?>img/favicon/favicon-32x32.png?v=kPxM4w57bz">
<link rel="icon" type="image/png" sizes="192x192" href="<?=$pathStart?>img/favicon/android-chrome-192x192.png?v=kPxM4w57bz">
<link rel="icon" type="image/png" sizes="16x16" href="<?=$pathStart?>img/favicon/favicon-16x16.png?v=kPxM4w57bz">
<link rel="manifest" href="<?=$pathStart?>img/favicon/manifest.json?v=kPxM4w57bz">
<link rel="mask-icon" href="<?=$pathStart?>img/favicon/safari-pinned-tab.svg?v=kPxM4w57bz" color="#fc973d">
<link rel="shortcut icon" href="<?=$pathStart?>favicon.ico?v=kPxM4w57bz">
<meta name="theme-color" content="#ffffff">
</head>

<body onLoad="loadQualitivePics();" id="body" name="body">
<div class="envelop1">

<div class="header">
<div class="ap">
<a href="<?php echo $pathStart == '' ? '/' : $pathStart?>" class="ap">Антон Павлов</a>
<div class="orangeline"></div>
</div>
<div class="mn" onMouseOver="mnhighlight(1,'mn3');" onMouseOut="mnhighlight(0,'mn3');"><a href="<?=$pathStart?>kontakty" class="amn">контакты</a><div class="submn" id="mn3"></div></div>
<div class="mn" onMouseOver="mnhighlight(1,'mn2');" onMouseOut="mnhighlight(0,'mn2');"><a href="<?=$pathStart?>blog" class="amn">блог</a><div class="submn" id="mn2"></div></div>
<div class="mn" onMouseOver="mnhighlight(1,'mn1');" onMouseOut="mnhighlight(0,'mn1');"><a href="<?=$pathStart?>blog/tags=%D1%87%D1%82%D0%BE%20%D1%8F%20%D0%BC%D0%BE%D0%B3%D1%83" class="amn">что я могу</a><div class="submn" id="mn1"></div></div>

<div class="picmn" onClick="showMobMenu();"></div>
</div>

<div class="mnline" id="mnline" name="mnline"></div>

<div class="mobmenu" id="mobmenu" name="mobmenu">
<a href="<?php echo $pathStart == '' ? '/' : $pathStart?>" class="amn"><div class="submobmenu">на главную</div></a>
<a href="<?=$pathStart?>kontakty" class="amn"><div class="submobmenu">контакты</div></a>
<a href="<?=$pathStart?>blog" class="amn"><div class="submobmenu">блог</div></a>
<a href="<?=$pathStart?>blog/tags=%D1%87%D1%82%D0%BE%20%D1%8F%20%D0%BC%D0%BE%D0%B3%D1%83" class="amn"><div class="submobmenu">что я могу</div></a>
</div>

<div id="submobmenu" name="submobmenu">

<?php include_once __DIR__.'/'.$contentViewFile; ?>

<br clear="both">

<div class="razd2"></div>

<div class="footermn">
<div class="nizmn0">
<div class="nizmnzag">Карта сайта</div>
<div class="nizmntext">
<a href="<?php echo $pathStart == '' ? '/' : $pathStart?>" class="anizmn">главная</a><br>
<a href="<?=$pathStart?>blog/tags=%D1%87%D1%82%D0%BE%20%D1%8F%20%D0%BC%D0%BE%D0%B3%D1%83" class="anizmn">что я могу</a><br>
<a href="<?=$pathStart?>blog" class="anizmn">блог</a><br>
<a href="<?=$pathStart?>kontakty" class="anizmn">контакты</a>
</div>
</div>
<div class="nizmn1">
<div class="nizmnzag">Блог</div>
<div class="nizmntext">
<a href="<?=$pathStart?>blog" class="anizmn">последние записи</a><br>
<a href="<?=$pathStart?>blog/best" class="anizmn">лучшие записи</a><br>
<a href="<?=$pathStart?>poisk" class="anizmn">поиск</a>
</div>
</div>
<div class="nizmn1">
<div class="nizmnzag">Резюме</div>
<div class="nizmntext">
<a href="<?=$pathStart?>blog/karera_rezyume" class="anizmn">менеджер интернет-проектов</a>
</div>
</div>
<div class="nizmn1">
<div class="nizmnzag">Email</div>
<div class="nizmntext">
<a href="mailto:mail@antonpavlov.ru" class="anizmn">mail@antonpavlov.ru</a>
</div>

</div>
</div>

<div class="mnline2" id="mnline2" name="mnline2"></div>

<div class="copyright">© t0sYAra 2000-<?=date('Y')?></div>
<div class="copyright"></div>

</div>
</div>
<div class="showpic" id="showpic" name="showpic">
<div class="showpic-header">
<img src="<?=$pathStart?>img/close.png" class="closepic" onClick="closePic();">
</div>
<div class="showpic-content"><!--
--><div class="showpic-left"><img src="<?=$pathStart?>img/l.png" class="leftpic" id="lpic" name="lpic" onClick="leftpic();"></div><!--
--><div class="showpic-pic"><img src="<?=$pathStart?>img/0.png" class="showpic" id="pictoshow" name="pictoshow"></div><!--
--><div class="showpic-right"><img src="<?=$pathStart?>img/r.png" class="rightpic" id="rpic" name="rpic" onClick="rightpic();"></div><!--
--></div>
<div class="showpic-footer"></div>
</div>
</body>
</html>