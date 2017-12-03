function mnhighlight(ifhighlight,id)
{
	if (ifhighlight==1) {document.getElementById(id).style.visibility='visible';}
	if (ifhighlight==0) {document.getElementById(id).style.visibility='hidden';}
}

function chPic(ifhighlight,id)
{
	if (ifhighlight==1) {document.getElementById(id).src=dir+'img/'+id+'1.jpg';}
	if (ifhighlight==0) {document.getElementById(id).src=dir+'img/'+id+'0.jpg';}
}

//window.onresize=checkmobmenu;

function checkmobmenu()
{
	if (ifShowMobMenu==0)
	{
		var width = document.body.clientWidth;
		if (width>600)
		{
		document.getElementById('mobmenu').style.display='none';
		document.getElementById('submobmenu').style.display='block';
		ifShowMobMenu=1;
		return;
		}
	}
}

function showMobMenu()
{
	if (ifShowMobMenu==1)
	{
		document.getElementById('mobmenu').style.display='block';
		document.getElementById('submobmenu').style.display='none';
		ifShowMobMenu=0;
		return;
	}
	if (ifShowMobMenu==0)
	{
		document.getElementById('mobmenu').style.display='none';
		document.getElementById('submobmenu').style.display='block';
		ifShowMobMenu=1;
		return;
	}
}

function loadQualitivePics()
{
	document.getElementById('body').style.backgroundImage='url('+dir+'img/bg.jpg'+')';
	document.getElementById('mnline').style.backgroundImage='url('+dir+'img/mnline.png'+')';
	document.getElementById('mnline').style.backgroundColor='transparent';
	document.getElementById('mnline2').style.backgroundImage='url('+dir+'img/mnline.png'+')';
	document.getElementById('mnline2').style.backgroundColor='transparent';
	
	var brauserwidth=document.body.clientWidth;
	
	// загружаем видео
	var vidarr=document.getElementsByClassName('youtubevideo1');
	if (vidarr.length>0)
	{
		for (i=0;i<vidarr.length;i++)
		{
			vidarr[i].innerHTML='<iframe id="ytplayer" type="text/html" class="ytplayer" src="https://www.youtube.com/embed/'+vidarr[i].innerHTML+'" frameborder="0" allowfullscreen></iframe>';
		}
	}
	
	// для страницы просмотра одной записи
	var imgarr1=document.getElementsByClassName('pictext');
	if (imgarr1.length>0)
	{
		for (i=0;i<imgarr1.length;i++)
		{
			if (brauserwidth>1133) {imgarr1[i].src=dir+'pics/d/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
			if ((brauserwidth>1000)&&(brauserwidth<=1133)) {imgarr1[i].src=dir+'pics/c/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
			if ((brauserwidth>800)&&(brauserwidth<=1000)) {imgarr1[i].src=dir+'pics/b/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
			if ((brauserwidth>600)&&(brauserwidth<=800)) {imgarr1[i].src=dir+'pics/a/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
			if ((brauserwidth>400)&&(brauserwidth<=600)) {imgarr1[i].src=dir+'pics/c/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
			if (brauserwidth<=400) {imgarr1[i].src=dir+'pics/a/'+imgarr1[i].src.substring(imgarr1[i].src.indexOf('pics/')+7);}
		}
	}
		
	// для страницы просмотра многих записей
	var imgarr2=document.getElementsByClassName('picblogimg');
	if (imgarr2.length>0)
	{
		for (i=0;i<imgarr2.length;i++)
		{
			if (brauserwidth>400) {imgarr2[i].src=dir+'pics/b/'+imgarr2[i].src.substring(imgarr2[i].src.indexOf('pics/')+7);}
			if (brauserwidth<=400) {imgarr2[i].src=dir+'pics/a/'+imgarr2[i].src.substring(imgarr2[i].src.indexOf('pics/')+7);}
		}
	}
}