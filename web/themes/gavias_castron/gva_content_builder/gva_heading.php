<?php 
if(!class_exists('element_gva_heading')):
   class element_gva_heading{
      public function render_form(){
         $fields = array(
            'type'      => 'gsc_heading',
            'title'     => t('Heading'), 
            'fields'    => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'sub_title',
                  'type'      => 'text',
                  'title'     => t('Sub Title'),
               ),
               array(
                  'id'        => 'desc',
                  'type'      => 'textarea',
                  'title'     => t('Description'),
               ),
               array(
                  'id'        => 'align',
                  'type'      => 'select',
                  'title'     => t('Align text for heading'),
                  'options'   => array(
                        'align-center'    => 'Align Center',
                        'align-left'      => 'Align Left',
                        'align-left-2'    => 'Align Left 2: Description float right',
                        'align-right'     => 'Align Right'
                  ),
                  'std'       => 'align-center'
               ),
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'title'     => t('Style display'),
                  'options'   => array(
                        'style-1'   => 'Style 1',
                        'style-2'   => 'Style 2',
                        'style-3'   => 'Style 3: Background Theme, Color White',
                        'style-4'   => 'Style 4: Small Heading',
                  )
               ),
               array(
                  'id'        => 'font_size',
                  'type'      => 'select',
                  'title'     => t('Font Size'),
                  'options'   => array(
                     '00'   => '--Default--',
                     '18'   => '18',
                     '20'   => '20',
                     '22'   => '22',
                     '24'   => '24',
                     '26'   => '26',
                     '28'   => '28',
                     '30'   => '30',
                     '32'   => '32',
                     '34'   => '34',
                     '36'   => '36',
                     '38'   => '38',
                     '40'   => '40',
                     '42'   => '42',
                     '44'   => '44',
                     '46'   => '46',
                     '48'   => '48',
                     '50'   => '50'
                  ),
                  'default'   => '00'
               ),
               array(
                  'id'        => 'style_text',
                  'type'      => 'select',
                  'title'     => t('Skin Text for box'),
                  'options'   => array(
                        'text-dark'   => 'Text dark',
                        'text-light'   => 'Text light'
                  )
               ),
               array(
                  'id'        => 'remove_padding',
                  'type'      => 'select',
                  'title'     => t('Remove Padding'),
                  'options'   => array(
                        ''                   => 'Default',   
                        'padding-top-0'      => 'Remove padding top',
                        'padding-bottom-0'    => 'Remove padding bottom',
                        'padding-bottom-0 padding-top-0'   => 'Remove padding top & bottom'
                  ),
                  'std'       => '',
                  'desc'      => 'Default heading padding top & bottom: 30px'
               ),
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
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
               )
            ),                                       
         );
         return $fields;
      } 
      public static function render_content( $attr = array(), $content = '' ){
         extract(gavias_merge_atts(array(
            'title'           => '',
            'desc'            => '',
            'sub_title'       => '',
            'align'           => '',
            'style'           => 'style-1',
            'font_size'       => 'default',
            'icon'            => '',
            'style_text'      => 'text-dark',
            'el_class'        => '',
            'remove_padding'  => '',
            'animate'         => '',
            'animate_delay'   => ''
         ), $attr));
         
         $class = array();
         $class[] = $el_class;
         $class[] = $align;
         $class[] = $style;
         $class[] = $style_text;
         $class[] = $remove_padding;
         if($animate) $class[] = 'wow ' . $animate;
         ?>
         <?php ob_start() ?>
         <div class="widget gsc-heading <?php print implode(' ',$class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="heading-top">
               <?php if($sub_title){ ?><div class="sub-title"><span><?php print t($sub_title); ?></span></div><?php } ?>
               <?php if($title){ ?><h2 class="title fsize-<?php print $font_size ?>"><span><?php print t($title); ?></span></h2><?php } ?>
            </div>
            <?php if($desc){ ?><div class="heading-bottom"><div class="title-desc"><?php print t($desc); ?></div></div><?php } ?>
         </div>
         <div class="clearfix"></div>
         <?php return ob_get_clean() ?>
         <?php
      }

   }
endif;

