<?php

use yii\grid\GridView;

?>
<div class="col-md-9">
    <div class="policy-index common__board">
        <div class="user-table">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered'],
            ]);
            ?>
        </div>
    </div>
</div>