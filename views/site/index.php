<?php

use yii\helpers\Html;
$this->registerCssFile("@web/css/job-snippet.css");
$this->registerJsFile("@web/js/apply.js")
?>

<?php foreach($vacancies as $vacancy): ?>
    <div class="snippet">
		<h3><?= $vacancy->title ?></h3>
		<p id="org">by <?= $vacancy->organization->name ?></p>
		<p><?= $vacancy->description ?></p>
		<div class="skills">
			<?php foreach($vacancy->skills as $skill): ?>
				<span><?= $skill->name ?></span>
			<?php endforeach; ?>
		</div>
		<?php $s = ""; if(in_array($vacancy->id, $applieds)) $s = "disabled"; ?>
		<button id="B<?=$vacancy->id?>" class="apply" type="submit" onclick="apply(<?= $vacancy->id ?>)" <?=$s?>>Apply</button>
	</div>
<?php endforeach; ?>