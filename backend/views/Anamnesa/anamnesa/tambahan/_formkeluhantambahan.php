<?php
  
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use backend\models\Lookup;
use yii\bootstrap\Modal;
?>

<?php $form = ActiveForm::begin([
                'id' => 'keluhantambahan-form', 
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 1, 'spanSize' => ActiveForm::SIZE_SMALL,'showLabels'=>false]
                ]); 
    ?>
<?php

                        $list = ['Sakit' => 'Sakit', 'Batuk' => 'Batuk', 'Luka' => 'Luka', 'Lemah'=>'Lemah','Demam'=>'Demam','Gangguan_Makan/Minum'=>'Gangguan Makan/Minum',
                                 'Gangguan_Buang_Air_Besar'=>'Gangguan Buang Air Besar','Gangguan_Buang_Air_Kecil'=>'Gangguan Buang Air Kecil','Gangguan_Tenggorokan'=>'Gangguan Tenggorokan',
                                 'Gangguan_pada_Sendi'=>'Gangguan pada Sendi','Masalah_pada_Kulit'=>'Masalah pada Kulit'
                            ];
                        $list2 = ['Masalah_pada_Mata' => 'Masalah pada Mata', 'gangguan_penglihatan' => 'gangguan penglihatan', 'Masalah_pada_Telinga' => 'Masalah pada Telinga', 'Masalah pada Mulut'=>'Masalah pada Mulut','Kelainan Suhu Tubuh'=>'Kelainan Suhu Tubuh',
                                 'Masalah_pada_Hidung/Pernapasan'=>'Masalah pada Hidung/Pernapasan',
                                 'Masalah pada_Jantung'=>'Masalah pada Jantung','Masalah_pada_Perut'=>'Masalah pada Perut','Masalah_Kewanitaan'=>'Masalah Kewanitaan',
                                 'Masalah_Reproduksi_Pria'=>'Masalah Reproduksi Pria','Lainnya'=>'Lainnya'
                            ]; 
                    ?>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'keluhan')->checkboxList($list,['itemOptions'=>['class' => 'test']]); ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'keluhan')->checkboxList($list2); ?>
                    </div>

<?php ActiveForm::end(); ?>



  <?php
//$this->registerJsFile('/admin/js/popupLokasi.js');
$this->registerJs("$(document).ready(function () {
   
     
    
$(\"input[type='checkbox']\").click(

    function() {
    if($(this).is(':checked')){

 var dataSakit = $(this).val();
     $.ajax({
        type     :'POST',
        cache    : false,
        dataType : 'json',
        data    : 'data='+$(this).val(),
        url  : 'save-keluhantambahan?id='+".$model->id.",
            success  : function(response) {
        $('#m_keluhanDetail').html('');
        $('#m_keluhanDetail').load(baseurl + '/Anamnesa/anamnesa/popup-keluhan?id='+response+'&param='+dataSakit);
        $('#m_keluhanDetail').modal('show');

              
    }
    }); 
    
}else{
 
   $.ajax({
        type     :'POST',
        cache    : false,
        dataType : 'json',
        data    : 'data='+$(this).val(),
        url  : 'delete-keluhantambahan?id='+".$model->id.",
            success  : function(response) {
             
    }
    });
}
}
);

    
 
 

   
   

    });");
?>