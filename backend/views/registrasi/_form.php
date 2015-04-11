<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\builder\Form;
use kartik\widgets\Select2;
use backend\models\Pasien;
use backend\models\AsuransiProvider;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Registrasi */
/* @var $form yii\widgets\ActiveForm */

if ($model->asuransi_tgl_lahir) {
    $model->asuransi_tgl_lahir = Yii::$app->get('helper')->dateFormatingAppStrip($model->asuransi_tgl_lahir);
}

$style1 = "";
$style2 = "";
switch ($model->status_asuransi) {
    case '':
        $style1 = "display:none";
        $style2 = "display:none";
        break;
    case 'Umum':
        $style1 = "display:none";
        $style2 = "display:none";
        break;
    case 'BPJS Kesehatan':
        $style1 = "display:block";
        $style2 = "display:none";
        break;
    case 'BPJS Ketenagakerjaan':
        $style1 = "display:block";
        $style2 = "display:none";
        break;
    case 'Asuransi Lainnya':
        $style1 = "display:none";
        $style2 = "display:block";
        break;
}
?>

<div class="nav-tabs-custom">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#dataumum" aria-controls="dataumum" role="tab" data-toggle="tab">Data Umum</a></li>
        <li role="presentation"><a href="#statasur" aria-controls="profile" role="tab" data-toggle="tab">Status Asuransi</a></li>
    </ul>

    <?php
    $form = ActiveForm::begin([
                'id' => 'registrasi-form',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => [
                    'deviceSize' => ActiveForm::SIZE_SMALL
                ]
            ]);

    $model->pasienId = $pId;

    // The controller action that will render the list
    $url = \yii\helpers\Url::to(['pasien-list']);
    $urlIcdx = \yii\helpers\Url::to(['icdx-list']);
    $urlId = \yii\helpers\Url::to(['id-list']);

    // Script to initialize the selection based on the value of the select2 element
    $initScript = <<< SCRIPT
        function (element, callback) {
            var id=\$(element).val();
            if (id !== "") {
                \$.ajax("{$url}?id=" + id, {
                    dataType: "json"
                }).done(function(data) { callback(data.results);});
            }
        }
SCRIPT;
    
    $initScriptIcdx = <<< SCRIPT
        function (element, callback) {
            var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$urlIcdx}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;

    $initScriptId = <<< SCRIPT
        function (element, callback) {
            var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$urlId}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
    ?>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="dataumum" style="padding:20px">

            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <?php
                        echo Form::widget([
                            'model' => $model,
                            'form' => $form,
                            'columns' => 1,
                            'attributes' => [
                                'address_detail' => [
                                    'label' => 'Id pasien',
                                    'labelSpan' => 6,
                                    //'columns' => 4,
                                    'attributes' => [
                                        'catatan' => [
                                            'type' => Form::INPUT_WIDGET,
                                            'widgetClass' => '\kartik\widgets\Select2',
                                            'options' => [
                                                //'options' => ['placeholder' => 'Cari dan pilih pasien berdasarkan id'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                        'minimumInputLength' => 1,
                                                        'ajax' => [
                                                            'url' => $urlId,
                                                            'dataType' => 'json',
                                                            'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                                            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                                        ],
                                                        'initSelection' => new JsExpression($initScriptId)
                                                    ],
                                                    'pluginEvents' => [
                                                         "change" => "function() { pasienInfo.getInfoByPasien($('#registrasi-catatan').select2(\"val\"), true); }",
                                                    ]
                                                ],
                                            //'columnOptions' => ['class' => 'col-sm-7'],
                                        ]
                                    ]
                                ]
                            ]
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-8">
                        <?php
                        echo Form::widget([
                            'model' => $model,
                            'form' => $form,
                            'columns' => 1,
                            'attributes' => [
                                            'address_detail' => [
                                                'label' => 'Nama pasien',
                                                'labelSpan' => 3,
                                                'columns' => 3,
                                                'attributes' => [
                                                    'pasienId' => [
                                                        'type' => Form::INPUT_WIDGET,
                                                        'widgetClass' => '\kartik\widgets\Select2',
                                                        'options' => [
                                                            'options' => ['placeholder' => 'Cari berdasarkan nama'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true,
                                                                    'minimumInputLength' => 3,
                                                                    'ajax' => [
                                                                        'url' => $url,
                                                                        'dataType' => 'json',
                                                                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                                                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                                                    ],
                                                                    'initSelection' => new JsExpression($initScript)
                                                                ],
                                                                'pluginEvents' => [
                                                                     "change" => "function(e) { pasienInfo.getInfoByPasien( $('#registrasi-pasienid').select2(\"val\")); $('#registrasi-catatan').select2(\"data\", { id: $('#registrasi-pasienid').select2(\"val\"), text: $('#registrasi-pasienid').select2(\"val\") }) }",
                                                                ]
                                                            ],
                                                        'columnOptions' => ['colspan' => 2, 'class' => 'col-sm-7'],
                                                    ],
                                                    'actions' => [
                                                        'type' => Form::INPUT_RAW,
                                                        'value' => '<div style="">' .
                                                        Html::button('<span class="glyphicon glyphicon-user"></span> Pasien', ['type' => 'button', 'id' => 'add_pasien', 'class' => 'btn btn-primary']) .
                                                        '</div>'
                                                    ],
                                                ]
                                            ]
                                         ]
                        ]);
                        ?>
                    </div> 
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 3,
                        'attributes' => [
                            'stat_pel' => [
                                'label' => 'Status Pelayanan',
                                'labelSpan' => 3,
                                'columns' => 3,
                                'attributes' => [
                                    'status_pelayanan' => [
                                        'type' => Form::INPUT_DROPDOWN_LIST,
                                        'items'=>[ 'Rawat Jalan' => 'Rawat Jalan', 'Rawat Inap' => 'Rawat Inap',],
                                        'options' => ['prompt' => '',],
                                        'columnOptions' => ['colspan' => 2, 'class' => 'col-sm-7'],
                                    ],
                                    'actions' => [
                                        'type' => Form::INPUT_RAW,
                                        'value' => '<div style="">' .
                                        Html::button('<span class="glyphicon glyphicon-envelope"></span> Surat Pengantar', ['type' => 'button', 'id' => 'sp_opname', 'class' => 'btn btn-primary', 'style' => 'display:none']) .
                                        '</div>',
                                    ],
                                ]
                            ]
                        ]
                    ]);

                    Modal::begin([
                        'id' => 'md_spo',
                        'header' => '<h4>Surat Pengantar Opname</h4>',
                    ]);
                    echo $form->field($model, 'status_rawat')->dropDownList([ 'Biasa' => 'Biasa', 'Persalinan' => 'Persalinan',], ['prompt' => '']);
                    echo $form->field($model, 'dr_penanggung_jawab')->textInput(['maxlength' => 25]);
                    echo $form->field($model, 'icdx_id')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Select ICDX ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'ajax' => [
                                'url' => $urlIcdx,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                            ],
                            'initSelection' => new JsExpression($initScriptIcdx)
                        ],
                    ]);
                    echo $form->field($model, 'catatan')->textarea(['rows' => 6]);
                    Modal::end();
                    ?>
                </div>
            </div>
            
        </div>
        <div role="tabpanel" class="tab-pane" id="statasur" style="padding:20px;min-height:158px;">
            <?= $form->field($model, 'status_asuransi')->radioList([ 'Umum' => 'Umum', 'BPJS Kesehatan' => 'BPJS Kesehatan', 'BPJS Ketenagakerjaan' => 'BPJS Ketenagakerjaan', 'Asuransi Lainnya' => 'Asuransi Lainnya',], ['inline' => true]) ?>
            <div id="el-1" style="<?= $style1 ?>">
                <?= $form->field($model, 'asuransi_noreg')->textInput(['maxlength' => 15]) ?>
                <?= $form->field($model, 'asuransi_nama')->textInput(['maxlength' => 25]) ?>
                <?php
                echo $form->field($model, 'asuransi_tgl_lahir')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter Tgl Lahir ...'],
                    'pluginOptions' => [
                        'autoclose' => true, 'format' => 'dd-mm-yyyy'
                    ]
                ]);
                ?>
                <?= $form->field($model, 'asuransi_status_jaminan')->textInput(['maxlength' => 30]) ?>
            </div>
            <div id="el-2" style="<?= $style2 ?>">
                <?php //$form->field($model, 'asuransi_noreg_other')->textInput(['maxlength' => 15]) ?>
                <?= $form->field($model, 'asuransi_provider_id')->dropDownList(ArrayHelper::map(AsuransiProvider::find()->asArray()->all(), 'id', 'nama'), ['prompt' => 'Select Asuransi', 'style' => 'width:70%;']) ?>
                <?= $form->field($model, 'asuransi_penanggung_jawab')->textInput(['maxlength' => 30]) ?>
                <?= $form->field($model, 'asuransi_alamat')->textInput(['maxlength' => 30]) ?>
                <?= $form->field($model, 'asuransi_notelp')->textInput(['maxlength' => 15]) ?>
            </div>
        </div>
    </div>
    <?php  echo $form->errorSummary($model); ?>
    <div class="row form-group col-sm-offset-4" style=";padding-bottom:20px;">
        <div class="col-sm-4 col-sm-offset-4">
            <?= Html::resetButton('<span class="fa fa-refresh"></span> Reset', ['class' => 'btn btn-reset']) ?>
            <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Daftar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'md_add_pasien',
    'header' => '<h7>Tambah Pasien</h7>'
]);
Modal::end();
?>
<?php ActiveForm::end(); ?>

<style>
    .form-horizontal .control-label {
        margin-bottom: 0;
        padding-top: 7px;
        text-align: left;
    }
</style>

<script>
    $(document).ready(function () {
        $('input[name="Registrasi[status_asuransi]"]').change(function () {
            if ($(this).val() == 'Umum') {
                $('#el-1').css('display', 'none');
                $('#el-2').css('display', 'none');
                $("#el-1 input").val(null);
                $("#el-2 input").val(null);
            }
            if ($(this).val() == 'BPJS Kesehatan') {
                $('#el-1').css('display', 'block');
                $('#el-2').css('display', 'none');
                $("#el-1 input").val(null);
                $("#el-2 input").val(null);
            }
            if ($(this).val() == 'BPJS Ketenagakerjaan') {
                $('#el-1').css('display', 'block');
                $('#el-2').css('display', 'none');
                $("#el-1 input").val(null);
                $("#el-2 input").val(null);
            }
            if ($(this).val() == 'Asuransi Lainnya') {
                $('#el-2').css('display', 'block');
                $('#el-1').css('display', 'none');
                $("#el-1 input").val(null);
                $("#el-2 input").val(null);
            }
        });

        $('#add_pasien').click(function () {
            $('#md_add_pasien .modal-body').html();
            $('#md_add_pasien .modal-body').load(baseurl + '/registrasi/mdaddpasien');
            $('#md_add_pasien').modal('show');
            $("#md_pasien .modal-content").css({
                "width": "750px",
                "margin": "0px 0 0 -10%"
            });
        });
        
        $('#sp_opname').click(function () {
            $('#md_spo').modal('show');
            $("#md_spo .modal-content").css({
                "width": "750px","height":"450px",
                "margin": "0px 0 0 -10%"
            });
        });

        $('#registrasi-status_pelayanan').change(function() {
            console.log($(this).val());

            var sp_opname = $('#sp_opname');

            if($(this).val() == "Rawat Inap") {
                sp_opname.show();
            }
            else {
                sp_opname.hide();
            }

        });

    });
</script>