<?php if (Notification::get('success')) Alert::success(Notification::get('success'));
echo (
Form::open(null).
'<div class="btn-group">'.
  Form::submit('resizephotos', __('Save', 'mport'), array('class' => 'btn btn-small')).
  Html::anchor( __('Back', 'mport'),'index.php?id=mport',array('class' => 'btn btn-small')).
'</div>'.
Html::br(2).
'<div class="span6">
  <div class="row-fluid">
      <div class="span6">'.
      Form::label('wsmall', __('Insert width in px', 'mport')).
      Form::input('wsmall', Option::get('mport_width'), array('class'=>'input-block-level')).Html::br(2).
      '</div>
      <div class="span6">'.
      Form::label('hsmall', __('Insert height in px', 'mport')).
      Form::input('hsmall', Option::get('mport_height'), array('class'=>'input-block-level')).Html::br(2).
     '</div>
  </div>

  <div class="row-fluid">
      <div class="span6">'.
      Form::label('wlarge', __('Insert max width in px', 'mport')).
      Form::input('wlarge', Option::get('mport_wmax'), array('class'=>'input-block-level')).Html::br(2).
      '</div>
      <div class="span6">'.
      Form::label('hlarge', __('Insert max height in px', 'mport')).
      Form::input('hlarge', Option::get('mport_hmax'), array('class'=>'input-block-level')).Html::br(2).
      '</div>
  </div>'.
      Form::label('Resize', __('Resize options', 'mport')).Html::nbsp(2).
      Form::select('Resize', $Resize, Option::get('mport_resize')).Html::Br().Html::br(2).
      Form::hidden('csrf', Security::token()).
      Form::close().
'</div>');
?>
