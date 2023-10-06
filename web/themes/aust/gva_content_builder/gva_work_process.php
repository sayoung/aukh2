<?php 
if(!class_exists('element_gva_work_process')):
   class element_gva_work_process{
      public function render_form(){
         $fields = array(
            'type'      => 'gva_work_process',
            'title'  => t('Work Process'), 
            'fields' => array(
               array(
                  'id'     => 'title',
                  'type'   => 'text',
                  'title'  => t('Title'),
                  'admin'  => true
               ),
               array(
                  'id'     => 'el_class',
                  'type'      => 'text',
                  'title'  => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
               ),
              array(
                  'id'        => 'animate',
                  'type'      => 'select',
                  'title'     => t('Animation'),
                  'desc'      => t('Entrance animation for element'),
                  'options'   => gavias_content_builder_animate_aos(),
                  'class'     => 'width-1-2'
               ), 
               array(
                  'id'        => 'animate_delay',
                  'type'      => 'select',
                  'title'     => t('Animation Delay'),
                  'options'   => gavias_content_builder_delay_aos(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-2'
               ), 
            ),                                           
         );
         for($i=1; $i<=4; $i++){
            $fields['fields'][] = array(
               'id'     => "info_${i}",
               'type'   => 'info',
               'desc'   => "Information for item {$i}"
            );
            $fields['fields'][] = array(
               'id'        => "title_{$i}",
               'type'      => 'text',
               'title'     => t("Title {$i}")
            );
            $fields['fields'][] = array(
               'id'        => "icon_{$i}",
               'type'      => 'upload',
               'title'     => t("Icon {$i}")
            );
            $fields['fields'][] = array(
               'id'           => "content_{$i}",
               'type'         => 'textarea_without_html',
               'title'        => t("Content {$i}")
            );
            
         }
      return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         $default = array(
            'title'           => '',
            'el_class'        => '',
            'animate'         => '',
            'animate_delay'   => ''
         );        
         for($i=1; $i<=4; $i++){
            $default["title_{$i}"] = '';
            $default["icon_{$i}"] = '';
            $default["content_{$i}"] = '';
         }
         extract(gavias_merge_atts($default, $attr)); 

         if($animate) $class[] = 'wow ' . $animate; 

         $classes = $el_class;
         $columns = 0;
         for($i = 1; $i <= 4; $i++){ 
            $title = "title_{$i}";
            if(!empty($$title)){
               $columns++;
            }
         } 
         $classes .= ' columns-' . $columns;

         $class_columns = 'lg-block-grid-3 md-block-grid-3 sm-block-grid-3 lg-block-grid-1';
         switch ($columns) {
            case 4:
               $class_columns = 'lg-block-grid-4 md-block-grid-4 sm-block-grid-2 lg-block-grid-2';
               break;
            case 3:
               $class_columns = 'lg-block-grid-3 md-block-grid-3 sm-block-grid-3 lg-block-grid-1';
               break;
            case 2:
               $class_columns = 'lg-block-grid-2 md-block-grid-2 sm-block-grid-2 lg-block-grid-2';
               break;
            case 1:
               $class_columns = 'lg-block-grid-1 md-block-grid-1 sm-block-grid-1 lg-block-grid-1';
               break;
            default:
               $class_columns = 'lg-block-grid-3 md-block-grid-3 sm-block-grid-3 lg-block-grid-1';
               break;
         }
         ?>

         <?php ob_start() ?>
         <div class="gsc-workprocess <?php print $classes ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="box-content-inner">   
               <div class="<?php print $class_columns ?>">
                  <?php for($i=1; $i<=4; $i++){ ?>
                     <?php 
                        $title = "title_{$i}";
                        $icon = "icon_{$i}";
                        $content = "content_{$i}";
                        if($$icon) $$icon = $base_url . $$icon; 
                     ?>
                     <?php if(!empty($$title)){ ?>
                        <div class="box-item item-columns clearfix box-item-<?php echo $i; ?>">
                           <div class="box-item-content">
                              <?php if($$icon){ ?>
                                 <div class="icon"><img src="<?php print $$icon ?>" alt="<?php print $$title ?>"/></div>   
                              <?php } ?> 
                              <div class="content-inner">
                                 <h4 class="title"><?php print $$title ?></h4>
                                 <div class="content"><?php print $$content ?></div>
                              </div>  
                          </div> 
                        </div>
                     <?php } ?>   
                  <?php } ?>  
               </div>   
            </div>
         </div>   
         <?php return ob_get_clean() ?>
      <?php    
      }
   }
endif;