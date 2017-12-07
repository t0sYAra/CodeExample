<?=$additionalJStext?>
<div class="content1">
<h1 class="blogzag"><?=$zag?></h1>
<div class="epigraf1"><div class="epigraf2"><?=$epigraf?></div></div>
<?=$text?>

<div class="published"></div><div class="published">Антон Павлов, <?=$published?></div>
<br clear="both">

<div class="razd2" name="comments" id="comments"></div>

<?php
if ((isset($comments))&&(!empty($comments))) {
    echo '
          <h2 class="h2comments">Комментарии</h2>
        ';

    if (count($comments)>5) {
        echo '<p class="abzac1"><a href="#postcomment" class="link1">оставить свой комментарий</a></p>
        ';
    }

    for ($i=0; $i < count($comments); $i++) {
        echo '
              <div class="'.$comments[$i]['cssStyle'].'" style="margin-left: '.$comments[$i]['margin'].'px;">
			  <div class="commentauthor">'.$comments[$i]['author'].'</div>
			  <div class="commenttext">'.$comments[$i]['text'].'</div>
			  <div class="commentdate">'.$comments[$i]['published'].'</div>
			  <div class="commentanswer"><a href="#postcomment" onclick="defineCommentsValues('.$comments[$i]['parentCommentsId'].',\''.$comments[$i]['author'].'\');" class="commentanswer">Ответить</a></div>
			  </div>
        ';
    }
}
?>

<h2 class="h2comments" name="postcomment" id="postcomment">Оставьте свой комментарий</h2>
<div id="otvet" name="otvet"></div>

<p class="abzac1"><?=$status?></p>
<div class="err"><?=$errors?></div>
    <form action="<?=$actionPage?>#postcomment" method="post">
    <input type="hidden" name="parentCommentsId" id="parentCommentsId" value="<?=$parentCommentsId?>">
    <div class="inpenvelop"><input name="commentsAuthor" id="commentsAuthor" type="text" class="inputtext" maxlength="100" value="<?=$commentsAuthor?>" placeholder="Ваше имя" required></div>
    <div class="inpenvelop"><textarea name="commentsText" id="commentsText" class="textarea" placeholder="Текст комментария" required><?=$commentsText?></textarea></div>
    <div class="inpenvelop">
        <input name="cnghtdbspc" id="cnghtdbspc" type="text" class="inputcaptcha" maxlength="5" value="" placeholder="Цифры с картинки справа ->" required pattern="[0-9]{5}">
        <img src="<?=$pathStart?>captcha" class="captcha">
    </div>
    <div class="inpenvelop"><input type="submit" value="добавить комментарий" class="formsubmit"></div>
</form>

</div>

<br clear="both">

<div class="razd2"></div>


</div>
