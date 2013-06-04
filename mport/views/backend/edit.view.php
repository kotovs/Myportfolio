<div class="clearfix">
    <?php if (Notification::get('success')) Alert::success(Notification::get('success'));
        echo (
        Form::open(null, array('enctype' => 'multipart/form-data')).
        Form::file('image',array('class'=>'input-file')).
        Form::label('title', __('Title', 'mport')).
        Form::input('title', $title, array('class'=>'input-block-level')).Html::br(2).
        Form::label('info', __('Info', 'mport')).
        Form::textarea('info', $info, array('class'=>'input-block-level','rows'=>'5')).Html::br(1).
        Form::submit('editItem',__('Save','mport'),array('class'=>'btn btn-small')).
        Form::hidden('csrf', Security::token()).
        Form::close());
    ?>
</div>
