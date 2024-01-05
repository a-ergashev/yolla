<?php

use yii\helpers\Html;
?>

<h1>Vacancies</h1>
<ul>
    <?php foreach($vacancies as $vacancy): ?>
        <li>
            <?= Html::encode("{$vacancy->title} by {$vacancy->organization->name}") ?>
    </li>
    <?php endforeach; ?>
    </ul>
