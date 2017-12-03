<?=$additionalJStext?>
<div class="content1">

<?php
for ($i=0; $i < count($allEntries); $i++) {
echo '<h2 class="h2text"><a href="'.$pathStart.'blog/'.$allEntries[$i]['translit'].'" class="blogzag">'.$allEntries[$i]['zag'].'</a><div class="h2orangeline"></div></h2>
';
if ($allEntries[$i]['picid'] !== '') {
    echo '<div class="picblog"><img src="'.$pathStart.'pics/0/'.$allEntries[$i]['entryid'].'/'.$allEntries[$i]['picid'].'.'.$allEntries[$i]['pictype'].'" class="picblogimg"></div>
    ';
}
echo ''.$allEntries[$i]['text'].'
<br clear="both">
<div class="kolcomments"></div><div class="published">'.$allEntries[$i]['published'].'</div>
';
}
echo $navField;
?>
<br clear="both">

</div>
