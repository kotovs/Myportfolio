    <div  id="mport">
        <section>
            <ul id="gallery">
                <li id="fullPreview"></li>
                <?php if(count($items > 0)); foreach ($items as $item) { ?>
                <li>
                    <a href="<?php echo Site::url().'public/mport/large/'.$item['img'];?>"></a>
                    <img
                    data-original="<?php echo Site::url().'public/mport/small/'.$item['img'];?>"
                    src="<?php echo Site::url();?>plugins/mport/lib/img/white.gif" width="240" height="150" alt="<?php echo $item['title'];?>" />
                    <div class="overLayer"></div>
                    <div class="infoLayer">
                        <ul>
                            <li><h2><?php echo $item['title'];?></h2></li>
                            <li><p><?php echo __('View Picture','mport');?></p></li>
                        </ul>
                    </div>
                    <div class="projectInfo"><?php echo $item['info'];?></div>
                </li>
                <?php }; ?>
            </ul>
      </section>
    </div>