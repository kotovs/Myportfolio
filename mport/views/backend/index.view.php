<div id="mainshop" class="clearfix">

    <div class="btn-group">
      <?php echo Html::anchor(__('Add item', 'mport'), 'index.php?id=mport&action=add',array('class' => 'btn btn-small'));?>
      <a href="#snippet" data-toggle="modal" class="btn btn-small"><?php echo __('Codes','mport');?></a>
      <?php echo Html::anchor( __('Settings', 'mport'),'index.php?id=mport&action=resize',array('class' => 'btn btn-small')); ?>
    </div>
    <?php echo Html::br(2); ?>
    <?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

    <table  id="mport"  class="table table-bordered">
    <thead>
        <tr>
            <th><?php echo __('Image','mport'); ?></th>
            <th><?php echo __('Title','mport'); ?></th>
            <th><?php echo __('Info','mport'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody  id="ProcessBody">
        <?php if (count($mp) > 0) foreach ($mp as $row) { ?>
                <tr>
                    <td  class="image">
                        <a class="image" href="#" rel="<?php echo Site::url().'public/mport/large/'.$row['img']; ?>" title="<?php echo Html::toText($row['title']); ?>">
                            <img width="90" src="<?php echo Site::url().'public/mport/small/'.$row['img']; ?>" alt="<?php echo Html::toText($row['title']); ?>"/>
                        </a>
                    </td>
                    <td><?php echo $row['title']; ?></td>
                    <td>
                        <?php
                            $str = $row['info'];
                            $count = preg_replace('[^\s]', '', $str);
                            if(strlen($count) > 100){echo Text::cut($count, 100 , '.....');}
                            else{echo $row['info'];};
                        ?>
                    </td>
                    <td></td>

                <td>
                <div class="pull-right">
                    <div class="btn-group">
            <?php echo Html::anchor(__('Edit', 'mport'), 'index.php?id=mport&action=edit&uid='.$row['uid'], array('class' => 'btn btn-small')); ?>
            <?php echo Html::anchor(__('Delete', 'mport'), 'index.php?id=mport&delItem='.$row['id'], array('class' => 'btn btn-small', 'onClick'=>'return confirmDelete(\''.__('Are you sure', 'mport').'\')')); ?>
                    </div>
                </div>
            </td>
         </tr>
        <?php } ?>
    </tbody>
    </table>
     <div id="pageNavPosition"></div>
</div>







<div class="modal fade hide" id="snippet">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3><?php echo __('Portafolio','mport');?></h3>
    </div>
    <div class="modal-body">
        <h3>Php</h3>
        <code>
            &lt;?php echo mport::getPortfolio(); ?&gt;
        </code>
        <br>
        <h3>Shortcode</h3>
        <code>
                &#123;Portfolio&#125;
        </code>
    </div>
</div>



<div id="mportPreview" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class='lightbox-header'>
        <button type="button" class="close" data-dismiss="lightbox" aria-hidden="true">&times;</button>
    </div>
    <div class='lightbox-content'>
        <img />
    </div>
</div>



<!-- To prevend change others styles in admin theme -->
<style type="text/css" media="screen">
@media only screen and (max-width: 800px) {
.cf:after{visibility:hidden;display:block;font-size:0;content:" ";clear:both;height:0;}* html .cf{zoom:1;}*:first-child+html .cf{zoom:1;}table{width:100%;border-collapse:collapse;border-spacing:0;}th,td{margin:0;vertical-align:top;}th{text-align:left;}table{display:block;position:relative;width:100%;}thead{display:block;float:left;}tbody{display:block;width:auto;position:relative;overflow-x:auto;white-space:nowrap;}thead tr{display:block;}th{display:block;text-align:right;}tbody tr{display:inline-block;vertical-align:top;}td{display:block;min-height:1.25em;text-align:left;}th{border-bottom:0;border-left:0;}td{border-left:0;border-right:0;border-bottom:0;}tbody tr{border-left:1px solid #babcbf;}th:last-child,td:last-child{border-bottom:1px solid #babcbf;}
}
</style>

<script type="text/javascript">

$(document).ready(function(){
    $('.image').find('a').on('click', function() {
        $('#mportPreview').lightbox('show').find('img').attr('src', $(this).attr('rel'));
    });
});





/*    Pagination
=======================================*/
function Pager(tableName, itemsPerPage) {
  this.tableName = tableName;
  this.itemsPerPage = itemsPerPage;
  this.currentPage = 1;
  this.pages = 0;
  this.inited = false;

  this.showRecords = function(from, to) {
    var rows = document.getElementById(tableName).rows;
    // i starts from 1 to skip table header row
    for (var i = 1; i < rows.length; i++) {
      if (i < from || i > to)
        rows[i].style.display = 'none';
      else
        rows[i].style.display = '';
    }
  };

  this.showPage = function(pageNumber) {
    if (! this.inited) {
      alert("not inited");
      return;
    }

    var oldPageAnchor = document.getElementById('pg'+this.currentPage);
    oldPageAnchor.className = 'pg-normal';

    this.currentPage = pageNumber;
    var newPageAnchor = document.getElementById('pg'+this.currentPage);
    newPageAnchor.className = 'pg-selected';

    var from = (pageNumber - 1) * itemsPerPage + 1;
    var to = from + itemsPerPage - 1;
    this.showRecords(from, to);
  };

  this.prev = function() {
    if (this.currentPage > 1)
      this.showPage(this.currentPage - 1);
  };

  this.next = function() {
    if (this.currentPage < this.pages) {
      this.showPage(this.currentPage + 1);
    }
  };

  this.init = function() {
    var rows = document.getElementById(tableName).rows;
    var records = (rows.length - 1);
    this.pages = Math.ceil(records / itemsPerPage);
    this.inited = true;
  };

  this.showPageNav = function(pagerName, positionId) {
    if (! this.inited) {
      alert("not inited");
      return;
    }
    var element = document.getElementById(positionId);
    var pagerHtml = '<div class="pagination"><ul><li><a onclick="' + pagerName + '.prev();" class="pg-normal">Prev</a></li>';
    for (var page = 1; page <= this.pages; page++)
      pagerHtml += '<li><a id="pg' + page + '" class="pg-normal" onclick="' + pagerName + '.showPage(' + page + ');"> ' + page + ' </a></li> ';
      pagerHtml += '<li><a onclick="'+pagerName+'.next();" class="pg-normal">Next</li></ul></div>';
      element.innerHTML = pagerHtml;
  };
}
</script>

<script type="text/javascript"><!--
    // Paginate function
    var pager = new Pager('mport', 5);
    pager.init();
    pager.showPageNav('pager', 'pageNavPosition');
    pager.showPage(1);
//--></script>