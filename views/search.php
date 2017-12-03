<div class="content1">
<h1 class="blogzag">Поиск по сайту</h1>
<div class="err" id="err" name="err"></div>
<form method="post" id="searchform" name="searchform">
<div class="inpenvelop"><input name="searchtext" id="searchtext" type="text" class="inputtext" maxlength="200" value="" placeholder="Поисковый запрос" required></div>
<div class="inpenvelop"><input type="button" value="поиск" class="formsubmit" onClick="doSearch();"></div>
</form>

<div class="razd2"></div>

<?php
if ((isset($tagsArr))&&(!empty($tagsArr))) {
    echo '<div class="hashtags1">'.PHP_EOL;
    for ($i=0; $i<count($tagsArr); $i++) {
        echo '<div class="hashtags2"><a href="'.$pathStart.'blog/tags='.$tagsArr[$i]['url'].'" class="link1" style="font-size:'.$tagsArr[$i]['fontSize'].'%;">#'.$tagsArr[$i]['name'].'</a></div>'.PHP_EOL;
    }
    echo '</div>';
}
?>

</div>