<div class="content1">
<h1 class="blogzag">Контакты</h1>
<p class="abzac1">Вы можете написать мне на email - <a href="mailto:mail@antonpavlov.ru" class="link1" target=_blank>mail@antonpavlov.ru</a> или воспользоваться формой ниже.<br>
Меня также можно найти в <a href="https://vk.com/antonpavlov" class="link1" target=_blank>вконтакте</a>. В других соц. сетях, твиттере, инстаграме и прочем меня нет.</p>

	<p class="abzac1"><?=$status?></p>
    <div class="err"><?=$errors?></div>
	<form action="<?=$pathStart?>kontakty" method="post">
	<div class="inpenvelop"><input name="name" id="name" type="text" class="inputtext" maxlength="100" value="<?=$name?>" placeholder="Ваше имя" required></div>
	<div class="inpenvelop"><input name="email" id="email" type="email" class="inputtext" maxlength="200" value="<?=$email?>" placeholder="Email для связи" required></div>
	<div class="inpenvelop"><textarea name="text" id="text" class="textarea" placeholder="Текст сообщения" required><?=$text?></textarea></div>
	<div class="inpenvelop">
		<input name="cnghtdbspc" id="cnghtdbspc" type="text" class="inputcaptcha" maxlength="5" value="" placeholder="Цифры с картинки справа ->" required pattern="[0-9]{5}">
		<img src="<?=$pathStart?>captcha" class="captcha">
		</div>
	<div class="inpenvelop"><input type="submit" value="отправить сообщение" class="formsubmit"></div>
	</form>
	
</div>