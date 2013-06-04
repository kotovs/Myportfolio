<?php

// Admin Navigation: add new item
Navigation::add(__('Portfolio', 'mport'), 'content', 'mport', 10);


/**
 * Mport admin class
 */
class mportAdmin extends Backend{

    public static $mport = null;

    /**
     * Main mport admin function
     */
    public static function main(){

        // Options resize
        $Resize = array(
            'width'   => __('Same width', 'mport'),
            'height'  => __('Same height', 'mport'),
            'crop'    => __('Crop images', 'mport'),
            'stretch' => __('Stretch images','mport'),
        );


        // Get url
        $siteurl = Option::get('siteurl');
        // Get Dir
        $dir = ROOT . DS . 'public' . DS . 'mport'. DS;
        // Get dir of small folder
        $small = $dir . 'small' . DS;
        // Get dir of small folder
        $large = $dir . 'large' . DS;
        // Scan images
        $files = File::scan($small, 'jpg');
        // Get menu table
        mportAdmin::$mport = new Table('mport');

        // Create Table
        $table = new Table('mport');

        // Select all in tables
        $mp = $table->select(null, 'all');



        // Check for get actions
        // -------------------------------------
        if (Request::get('action')) {
            switch (Request::get('action')) {
                // Add product
                case "add":
                    if(Request::post('addItem')){

                        if (Security::check(Request::post('csrf'))) {
                        if (Request::post('title')) $title = Request::post('title'); else $title = '--';
                        if (Request::post('info'))$info = Request::post('info'); else $info = '--';

                        // Random text for images
                        $name = Text::random('hexdec').'.jpg';

                        // Get function convert and apply
                        mportAdmin::conVert('image',$name);

                            // Insert in table mport.xml
                            $table->insert(array(
                                'title' => Html::toText($title),
                                'info'  => $info,
                                'img'   => $name
                            ));

                            // Select all
                            $mp = $table->select(null, 'all');
                            Notification::set('success', __('Your item has been added ', 'mport'));
                            Request::redirect('index.php?id=mport');
                        } else { die('csrf detected!'); }
                    }
                    // View layout add
                    View::factory('mport/views/backend/add')->display();
                break;


                // Edit product
                case "edit":

                    // Init vars
                    $item = mportAdmin::$mport->select('[uid="'.Request::get('uid').'"]', null);
                    $title          = $item['title'];
                    $info           = $item['info'];
                    $image          = $item['img'];

                    // Request
                    if(Request::post('editItem')){
                        if (Security::check(Request::post('csrf'))) {

                        if (Request::post('title')) $title = Request::post('title');else $title = '--';
                        if (Request::post('info')) $info = Request::post('info');else $info = '--';


                        // Not rename name
                        // only change photo
                        $name = $image;

                        // Use updateConvert to not show image no preview
                        mportAdmin::conVert('image',$name);

                        // Update database
                        $table->updateWhere('[uid="'.Request::get('uid').'"]',
                                array(
                                    'title'  => Html::toText($title),
                                    'info'   => $info,
                                    'img'    => $name
                                ));
                        $mp = $table->select(null, 'all');
                        Notification::set('success', __('Your item has been edit ', 'mport'));
                        Request::redirect('index.php?id=mport');
                        } else { die('csrf detected!'); }
                    }
                    // View layout
                    View::factory('mport/views/backend/edit')
                        ->assign('title', $title)
                        ->assign('info', $info)
                        ->assign('img', $image)
                        ->assign('mp', $mp)
                        ->display();
                break;

                // resize
                case 'resize':
                    if(Request::post('resizephotos')) {
                        $largeFiles = File::scan($large,'jpg');
                        $smallFiles = File::scan($small,'jpg');
                        // Update options
                        Option::update(array(
                                'mport_width'  => Request::post('wsmall'),
                                'mport_height' => Request::post('hsmall'),
                                'mport_wmax'   => Request::post('wlarge'),
                                'mport_hmax'   => Request::post('hlarge'),
                                'mport_resize' => Request::post('Resize')));
                        // If get images resize with ReSize function
                        if (count($largeFiles) > 0){
                            foreach($largeFiles as $item){
                                mportAdmin::ReSize($large.$item,$small.$item);
                            }
                            Notification::set('success', __('Your settings has been edit ', 'mport'));
                            Request::redirect('index.php?id=mport&action=resize');
                        }
                    }
                    // View layout
                    View::factory('mport/views/backend/resize')->assign('Resize', $Resize)->display();
                break;

            }
        } else {
                // Delete product database item and images from folder
                if (Request::get('delItem')) {
                    $id = Request::get('delItem');
                    $delete = mportAdmin::$mport->select('[id='.$id.']',null);
                    File::delete($small.$delete['img']);
                    File::delete($large.$delete['img']);
                    mportAdmin::$mport->delete((int)Request::get('delItem'));
                    Notification::set('success', __('Your item has been delete ', 'mport'));
                    Request::redirect('index.php?id=mport');
                }
            // Display view
            View::factory('mport/views/backend/index')->assign('mp', $mp)->display();
        }

    }





    /**
     * function to convert
     * mportAdmin::conVert('file','name');
     * If not get image from folder get image no-preview
     * and resize this
     * @param  string $image, $name
     */
    private static function conVert($image, $name){

        $dir = ROOT . DS . 'public' . DS . 'mport'. DS;
        $small = $dir . 'small' . DS;
        $large = $dir . 'large' . DS;

        if ($_FILES[$image]['name']) {
            if($_FILES[$image]['type'] == 'image/jpeg' ||
                $_FILES[$image]['type'] == 'image/png' ||
                $_FILES[$image]['type'] == 'image/gif') {
                $wx  = Option::get('mport_wmax');
                $hx  = Option::get('mport_hmax');
                $w   = Option::get('mport_width');
                $h   = Option::get('mport_height');
                $re  = Option::get('mport_resize');
                $ra  = $w/$h;
                $img  = Image::factory($_FILES[$image]['tmp_name']);
                if ($img->width > $wx or $img->height > $hx) {
                    if ($img->height > $img->width) {$img->resize($wx, $hx, Image::HEIGHT);}
                    else {$img->resize($wx, $hx, Image::WIDTH);}}
                $img->save($large . $name);
                switch ($re) {
                    case 'width' :$img->resize($w, $h, Image::WIDTH);break;
                    case 'height' :$img->resize($w, $h, Image::HEIGHT);break;
                    case 'stretch' :$img->resize($w, $h);break;
                    case 'crop':
                        if (($img->width/$img->height) > $ra) {
                            $img->resize($w, $h, Image::HEIGHT)->crop($w, $h, round(($img->width-$w)/2),0);
                        } else { $img->resize($w, $h, Image::WIDTH)->crop($w, $h, 0, 0);}
                    break;
                }
                $img->save($small . $name);
            }

        }
    }


    /**
     * function to resize
     * mportAdmin::ReSize('largeImages','smallImages');
     * @param  string $largeImages, $smallImages
     */
    private static function ReSize($largeImages,$smallImages){
        $img = Image::factory($largeImages);
        $wx  = Option::get('mport_wmax');
        $hx  = Option::get('mport_hmax');
        $w   = Option::get('mport_width');
        $h   = Option::get('mport_height');
        $re  = Option::get('mport_resize');
        $ra  = $w/$h;
        if ($img->width > $wx or $img->height > $hx) {
            if ($img->height > $img->width) {
                $img->resize($wx, $hx, Image::HEIGHT);
            } else {
                $img->resize($wx, $hx, Image::WIDTH);
            }
        }
        $img->save($largeImages);
        switch ($re) {
            case 'width' :   $img->resize($w, $h, Image::WIDTH);  break;
            case 'height' :  $img->resize($w, $h, Image::HEIGHT); break;
            case 'stretch' : $img->resize($w, $h); break;
            case 'crop':
                if (($img->width/$img->height) > $ra) {
                    $img->resize($w, $h, Image::HEIGHT)->crop($w, $h, round(($img->width-$w)/2),0);
                } else {
                    $img->resize($w, $h, Image::WIDTH)->crop($w, $h, 0, 0);
                }
            break;
        }
        $img->save($smallImages);
    }


}
