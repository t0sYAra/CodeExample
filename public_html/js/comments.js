
function definecommentsvalues(parentcommentsid,levelid,author)
{
//alert(parentcommentsid);
document.getElementById('parentcommentsid').value=parentcommentsid;
document.getElementById('levelid').value=levelid;
document.getElementById('otvet').innerHTML='<p class="abzac1">Вы собираетесь ответить на комментарий пользователя '+author+' (<a href="#postcomment" class="commentanswer" onClick="clearcommentsvalues();">отменить</a>)</p>';
}

function clearcommentsvalues()
{
document.getElementById('otvet').innerHTML='';
document.getElementById('parentcommentsid').value=0;
document.getElementById('levelid').value=0;
}

function showpic(entryid,picid,type)
{
	var re1=new RegExp("^[1-9][0-9]{0,10}$");
	if (!re1.test(entryid)) return;
	var re2=new RegExp("^[1-9][0-9]{0,3}$");
	if (!re2.test(picid)) return;
	var re3=new RegExp("^(jpg|gif|png)$");
	if (!re3.test(type)) return;
	
	document.getElementById('showpic').style.display='block';
	document.getElementById('pictoshow').src=dir+'pics/1/'+entryid+'/'+picid+'.'+type;
	
	tekpic=picid;
	beforepic="0";
	afterpic="0";
	for (i=0;i<pics.length;i++)
	{
		if (pics[i]==tekpic)
		{
			arrindex=i;
			if (i>0) {beforepic=pics[i-1];}
			if (i<pics.length-1) {afterpic=pics[i+1];}
		}
	}
		if (beforepic=="0") {document.getElementById('lpic').style.visibility='hidden';} else {document.getElementById('lpic').style.visibility='visible';}
		if (afterpic=="0") {document.getElementById('rpic').style.visibility='hidden';} else {document.getElementById('rpic').style.visibility='visible';}
}

function leftpic()
{
	if (beforepic!="0")
	{
		document.getElementById('pictoshow').src=dir+'pics/1/'+entryid+'/'+beforepic+'.jpg';
		tekpic=pics[arrindex-1];
		if (arrindex==1) {beforepic=0;}
		for (i=0;i<pics.length;i++)
		{
			if (pics[i]==tekpic)
			{
				arrindex=i;
				if (i>0) {beforepic=pics[i-1];}
				if (i<pics.length-1) {afterpic=pics[i+1];}
			}
		}
		if (beforepic=="0") {document.getElementById('lpic').style.visibility='hidden';} else {document.getElementById('lpic').style.visibility='visible';}
		if (afterpic=="0") {document.getElementById('rpic').style.visibility='hidden';} else {document.getElementById('rpic').style.visibility='visible';}
	}
}

function rightpic()
{
	if (afterpic!="0")
	{
		document.getElementById('pictoshow').src=dir+'pics/1/'+entryid+'/'+afterpic+'.jpg';
		tekpic=pics[arrindex+1];
		if (arrindex==pics.length-2) {afterpic=0;}
		for (i=0;i<pics.length;i++)
		{
			if (pics[i]==tekpic)
			{
				arrindex=i;
				if (i>0) {beforepic=pics[i-1];}
				if (i<pics.length-1) {afterpic=pics[i+1];}
			}
		}
		if (beforepic=="0") {document.getElementById('lpic').style.visibility='hidden';} else {document.getElementById('lpic').style.visibility='visible';}
		if (afterpic=="0") {document.getElementById('rpic').style.visibility='hidden';} else {document.getElementById('rpic').style.visibility='visible';}
	}
}

function closePic()
{
	document.getElementById('pictoshow').src=dir+'img/0.png';
	document.getElementById('showpic').style.display='none';
}

/*
window.onload = function () {
document.body.onclick = function (e) {
e = e || event;
target = e.target || e.srcElement;
if (target.tagName == "DIV" && target.id == "showpic") {
alert("клик по нужному div-у");
} else {
alert("клик вне нужного div-a");
}
}
}
*/