<?=$additionalJStext?>
<div class="content1">

<p class="abzac1"><?=$errors?></p>

<?php

if ((isset($errors))&&($errorType == 1)) {
    echo '<div class="err" id="err" name="err"></div>
          <form method="post" id="searchform" name="searchform">
          <div class="inpenvelop"><input name="searchtext" id="searchtext" type="text" class="inputtext" maxlength="200" value="" placeholder="Поисковый запрос" required></div>
          <div class="inpenvelop"><input type="button" value="поиск" class="formsubmit" onClick="doSearch();"></div>
          </form>
';
}

if (isset($allEntries)) {
    for ($i=0; $i < count($allEntries); $i++) {
        echo '<h2 class="h2text"><a href="'.$pathStart.'blog/'.$allEntries[$i]['translit'].'" class="blogzag">'.$allEntries[$i]['zag'].'</a><div class="h2orangeline"></div></h2>'.PHP_EOL;
        if ($allEntries[$i]['picid'] !== '') {
            echo '<div class="picblog"><img src="'.$pathStart.'pics/0/'.$allEntries[$i]['entryid'].'/'.$allEntries[$i]['picid'].'.'.$allEntries[$i]['pictype'].'" class="picblogimg"></div>'.PHP_EOL;
        }
        echo ''.$allEntries[$i]['text'].'
        <br clear="both">
        <div class="kolcomments"><a href="'.$pathStart.'blog/'.$allEntries[$i]['translit'].'#comments" class="link1">'.$allEntries[$i]['commentsAmmount'].'</a></div><div class="published">'.$allEntries[$i]['published'].'</div>
        ';
    }
}
if (isset($navField)) {
    echo $navField;
}
?>
<br clear="both">

</div>
