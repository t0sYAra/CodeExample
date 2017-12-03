
function doSearch()
{
var searchtext=document.getElementById('searchtext').value;
var re1=new RegExp("^.{2,200}$");
if (!re1.test(searchtext))
{
	document.getElementById('err').innerHTML='Длина поискового запроса - от 2 до 200 символов';
	return;
}
var re2=new RegExp("^[-_ a-zа-яё0-9]+$");
if (!re2.test(searchtext))
{
	document.getElementById('err').innerHTML='Поисковый запрос может содержать только буквы, цифры, пробелы и тире';
	return;
}
document.getElementById("searchform").action=dir+"blog/search="+encodeURIComponent(searchtext);
document.getElementById("searchform").submit();
}


