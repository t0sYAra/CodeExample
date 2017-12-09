<div class="content1">
<h1 class="blogzag">Новая запись в блоге</h1>
<p class="abzac1">Вы можете добавить новую запись в блог, если знаете код доступа :)</p>

	<p class="abzac1"><?=$status?></p>
    <div class="err"><?=$errors?></div>
	<form action="<?=$pathStart?>add" method="post">
	<div class="inpenvelop"><input name="heading" id="heading" type="text" class="inputtext heading" maxlength="250" value="<?=$heading?>" placeholder="Заголовок записи" required></div>
	<div class="inpenvelop"><textarea name="epigraph" id="epigraph" class="textarea" placeholder="Эпиграф"><?=$epigraph?></textarea></div>
    <div class="inpenvelop"><textarea name="text" id="text" class="textarea entrytext" placeholder="Текст записи" required><?=$text?></textarea></div>
	<div class="inpenvelop"><textarea name="tags" id="tags" class="textarea entrytags" placeholder="Теги - каждый на новой строчке" required><?=$tags?></textarea></div>
	<div class="inpenvelop"><input name="accessCode" id="accessCode" type="password" class="inputtext" maxlength="16" value="" placeholder="Код доступа" required></div>
	<div class="inpenvelop"><input type="submit" value="Опубликовать запись" class="formsubmit"></div>
	</form>
	
</div>