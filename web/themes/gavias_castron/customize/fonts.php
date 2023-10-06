<?php
function gavias_castron_font_size(){
    $size = array();
    $size[''] = '-- Default --';
    for ($i=12; $i <= 50 ; $i = $i+1) { 
        $size[$i] = $i;
    }
    return $size;
}

function gavias_castron_fonts(){
$tmp = array(
        '---',
        "'Arial', Helvetica, sans-serif",
        "'Arial Black', Gadget, sans-serif",
        "'Bookman Old Style', serif",
        "'Comic Sans MS', cursive",
        "'Courier', monospace",
        "'Garamond', serif",
        "'Georgia', serif",
        "'Impact', Charcoal, sans-serif",
        "'Lucida Console', Monaco, monospace",
        "'Lucida Sans Unicode', Lucida Grande, sans-serif",
        "'MS Sans Serif', Geneva, sans-serif",
        "'MS Serif', New York, sans-serif",
        "'Palatino Linotype', Book Antiqua, Palatino, serif",
        "'Tahoma',Geneva, sans-serif",
        "'Times New Roman', Times, serif",
        "'Trebuchet MS', Helvetica, sans-serif",
        "'Verdana', Geneva, sans-serif",

        'ABeeZee',
        'Abel',
        'Abril Fatface',
        'Aclonica',
        'Acme',
        'Actor',
        'Adamina',
        'Advent Pro',
        'Aguafina Script',
        'Akronim',
        'Aladin',
        'Aldrich',
        'Alef',
        'Alegreya',
        'Alegreya SC',
        'Alex Brush',
        'Alfa Slab One',
        'Alice',
        'Alike',
        'Alike Angular',
        'Allan',
        'Allerta',
        'Allerta Stencil',
        'Allura',
        'Almendra',
        'Almendra Display',
        'Almendra SC',
        'Amarante',
        'Amaranth',
        'Amatic SC',
        'Amethysta',
        'Anaheim',
        'Andada',
        'Andika',
        'Angkor',
        'Annie Use Your Telescope',
        'Anonymous Pro',
        'Antic',
        'Antic Didone',
        'Antic Slab',
        'Anton',
        'Arapey',
        'Arbutus',
        'Arbutus Slab',
        'Architects Daughter',
        'Archivo Black',
        'Archivo Narrow',
        'Arimo',
        'Arizonia',
        'Armata',
        'Artifika',
        'Arvo',
        'Asap',
        'Asset',
        'Astloch',
        'Asul',
        'Atomic Age',
        'Aubrey',
        'Audiowide',
        'Autour One',
        'Average',
        'Average Sans',
        'Averia Gruesa Libre',
        'Averia Libre',
        'Averia Sans Libre',
        'Averia Serif Libre',
        'Bad Script',
        'Balthazar',
        'Bangers',
        'Basic',
        'Battambang',
        'Baumans',
        'Bayon',
        'Belgrano',
        'Belleza',
        'BenchNine',
        'Bentham',
        'Berkshire Swash',
        'Bevan',
        'Bigelow Rules',
        'Bigshot One',
        'Bilbo',
        'Bilbo Swash Caps',
        'Bitter',
        'Black Ops One',
        'Bokor',
        'Bonbon',
        'Boogaloo',
        'Bowlby One',
        'Bowlby One SC',
        'Brawler',
        'Bree Serif',
        'Bubblegum Sans',
        'Bubbler One',
        'Buda',
        'Buenard',
        'Butcherman',
        'Butterfly Kids',
        'Cabin',
        'Cabin Condensed',
        'Cabin Sketch',
        'Caesar Dressing',
        'Cagliostro',
        'Calligraffitti',
        'Cambo',
        'Candal',
        'Cantarell',
        'Cantata One',
        'Cantora One',
        'Capriola',
        'Cardo',
        'Carme',
        'Carrois Gothic',
        'Carrois Gothic SC',
        'Carter One',
        'Caudex',
        'Cedarville Cursive',
        'Ceviche One',
        'Changa One',
        'Chango',
        'Chau Philomene One',
        'Chela One',
        'Chelsea Market',
        'Chenla',
        'Cherry Cream Soda',
        'Cherry Swash',
        'Chewy',
        'Chicle',
        'Chivo',
        'Cinzel',
        'Cinzel Decorative',
        'Clicker Script',
        'Coda',
        'Coda Caption',
        'Codystar',
        'Combo',
        'Comfortaa',
        'Coming Soon',
        'Concert One',
        'Condiment',
        'Content',
        'Contrail One',
        'Convergence',
        'Cookie',
        'Copse',
        'Corben',
        'Courgette',
        'Cousine',
        'Coustard',
        'Covered By Your Grace',
        'Crafty Girls',
        'Creepster',
        'Crete Round',
        'Crimson Text',
        'Croissant One',
        'Crushed',
        'Cuprum',
        'Cutive',
        'Cutive Mono',
        'Datico',
        'Dancing Script',
        'Dangrek',
        'Dawning of a New Day',
        'Days One',
        'Delius',
        'Delius Swash Caps',
        'Delius Unicase',
        'Della Respira',
        'Denk One',
        'Devonshire',
        'Didact Gothic',
        'Diplomata',
        'Diplomata SC',
        'Domine',
        'Donegal One',
        'Doppio One',
        'Dorsa',
        'Dosis',
        'Dr Sugiyama',
        'Droid Sans',
        'Droid Sans Mono',
        'Droid Serif',
        'Duru Sans',
        'Dynalight',
        'EB Garamond',
        'Eagle Lake',
        'Eater',
        'Economica',
        'Electrolize',
        'Elsie',
        'Elsie Swash Caps',
        'Emblema One',
        'Emilys Candy',
        'Engagement',
        'Englebert',
        'Enriqueta',
        'Erica One',
        'Esteban',
        'Euphoria Script',
        'Ewert',
        'Exo',
        'Expletus Sans',
        'Fanwood Text',
        'Fascinate',
        'Fascinate Inline',
        'Faster One',
        'Fasthand',
        'Fauna One',
        'Federant',
        'Federo',
        'Felipa',
        'Fenix',
        'Finger Paint',
        'Fjalla One',
        'Fjord One',
        'Flamenco',
        'Flavors',
        'Fondamento',
        'Fontdiner Swanky',
        'Forum',
        'Francois One',
        'Freckle Face',
        'Fredericka the Great',
        'Fredoka One',
        'Freehand',
        'Fresca',
        'Frijole',
        'Fruktur',
        'Fugaz One',
        'GFS Didot',
        'GFS Neohellenic',
        'Gabriela',
        'Gafata',
        'Galdeano',
        'Galindo',
        'Gentium Basic',
        'Gentium Book Basic',
        'Geo',
        'Geostar',
        'Geostar Fill',
        'Germania One',
        'Gilda Display',
        'Give You Glory',
        'Glass Antiqua',
        'Glegoo',
        'Gloria Hallelujah',
        'Goblin One',
        'Gochi Hand',
        'Gorditas',
        'Goudy Bookletter 1911',
        'Graduate',
        'Grand Hotel',
        'Gravitas One',
        'Great Vibes',
        'Griffy',
        'Gruppo',
        'Gudea',
        'Habibi',
        'Hammersmith One',
        'Hanalei',
        'Hanalei Fill',
        'Handlee',
        'Hanuman',
        'Happy Monkey',
        'Headland One',
        'Henny Penny',
        'Herr Von Muellerhoff',
        'Holtwood One SC',
        'Homemade Apple',
        'Homenaje',
        'IM Fell DW Pica',
        'IM Fell DW Pica SC',
        'IM Fell Double Pica',
        'IM Fell Double Pica SC',
        'IM Fell English',
        'IM Fell English SC',
        'IM Fell French Canon',
        'IM Fell French Canon SC',
        'IM Fell Great Primer',
        'IM Fell Great Primer SC',
        'Iceberg',
        'Iceland',
        'Imprima',
        'Inconsolata',
        'Inder',
        'Indie Flower',
        'Inika',
        'Irish Grover',
        'Istok Web',
        'Italiana',
        'Italianno',
        'Jacques Francois',
        'Jacques Francois Shadow',
        'Jim Nightshade',
        'Jockey One',
        'Jolly Lodger',
        'Josefin Sans',
        'Josefin Slab',
        'Joti One',
        'Judson',
        'Julee',
        'Julius Sans One',
        'Junge',
        'Jura',
        'Just Another Hand',
        'Just Me Again Down Here',
        'Kameron',
        'Karla',
        'Kaushan Script',
        'Kavoon',
        'Keania One',
        'Kelly Slab',
        'Kenia',
        'Khmer',
        'Kite One',
        'Knewave',
        'Kotta One',
        'Koulen',
        'Kranky',
        'Kreon',
        'Kristi',
        'Krona One',
        'Laila',
        'La Belle Aurore',
        'Lancelot',
        'Lato',
        'League Script',
        'Leckerli One',
        'Ledger',
        'Lekton',
        'Lemon',
        'Libre Baskerville',
        'Life Savers',
        'Lilita One',
        'Lily Script One',
        'Limelight',
        'Linden Hill',
        'Lobster',
        'Lobster Two',
        'Londrina Outline',
        'Londrina Shadow',
        'Londrina Sketch',
        'Londrina Solid',
        'Lora',
        'Love Ya Like A Sister',
        'Loved by the King',
        'Lovers Quarrel',
        'Luckiest Guy',
        'Lusitana',
        'Lustria',
        'Macondo',
        'Macondo Swash Caps',
        'Magra',
        'Maiden Orange',
        'Mako',
        'Marcellus',
        'Marcellus SC',
        'Marck Script',
        'Margarine',
        'Marko One',
        'Marmelad',
        'Marvel',
        'Mate',
        'Mate SC',
        'Maven Pro',
        'McLaren',
        'Meddon',
        'MedievalSharp',
        'Medula One',
        'Megrim',
        'Meie Script',
        'Merienda',
        'Merienda One',
        'Merriweather',
        'Merriweather Sans',
        'Metal',
        'Metal Mania',
        'Metamorphous',
        'Metrophobic',
        'Michroma',
        'Milonga',
        'Miltonian',
        'Miltonian Tattoo',
        'Miniver',
        'Miss Fajardose',
        'Modern Antiqua',
        'Molengo',
        'Molle',
        'Monda',
        'Monofett',
        'Monoton',
        'Monsieur La Doulaise',
        'Montaga',
        'Montez',
        'Montserrat',
        'Montserrat Alternates',
        'Montserrat Subrayada',
        'Moul',
        'Moulpali',
        'Mountains of Christmas',
        'Mouse Memoirs',
        'Mr Bedfort',
        'Mr Dafoe',
        'Mr De Haviland',
        'Mrs Saint Delafield',
        'Mrs Sheppards',
        'Muli',
        'Mystery Quest',
        'Neucha',
        'Neuton',
        'New Rocker',
        'News Cycle',
        'Niconne',
        'Nixie One',
        'Nobile',
        'Nokora',
        'Norican',
        'Nosifer',
        'Nothing You Could Do',
        'Noticia Text',
        'Noto Sans',
        'Noto Serif',
        'Nova Cut',
        'Nova Flat',
        'Nova Mono',
        'Nova Oval',
        'Nova Round',
        'Nova Script',
        'Nova Slim',
        'Nova Square',
        'Numans',
        'Nunito',
        'Odor Mean Chey',
        'Offside',
        'Old Standard TT',
        'Oldenburg',
        'Oleo Script',
        'Oleo Script Swash Caps',
        'Open Sans',
        'Open Sans Condensed',
        'Oranienbaum',
        'Orbitron',
        'Oregano',
        'Orienta',
        'Original Surfer',
        'Oswald',
        'Over the Rainbow',
        'Overlock',
        'Overlock SC',
        'Ovo',
        'Oxygen',
        'Oxygen Mono',
        'PT Mono',
        'PT Sans',
        'PT Sans Caption',
        'PT Sans Narrow',
        'PT Serif',
        'PT Serif Caption',
        'Pacifico',
        'Paprika',
        'Parisienne',
        'Passero One',
        'Passion One',
        'Pathway Gothic One',
        'Patrick Hand',
        'Patrick Hand SC',
        'Patua One',
        'Paytone One',
        'Peralta',
        'Permanent Marker',
        'Petit Formal Script',
        'Petrona',
        'Philosopher',
        'Piedra',
        'Pinyon Script',
        'Pirata One',
        'Plaster',
        'Play',
        'Playball',
        'Playfair Display',
        'Playfair Display SC',
        'Podkova',
        'Poiret One',
        'Poller One',
        'Poly',
        'Pompiere',
        'Pontano Sans',
        'Port Lligat Sans',
        'Port Lligat Slab',
        'Prata',
        'Preahvihear',
        'Press Start 2P',
        'Princess Sofia',
        'Prociono',
        'Prosto One',
        'Puritan',
        'Purple Purse',
        'Quando',
        'Quantico',
        'Quattrocento',
        'Quattrocento Sans',
        'Questrial',
        'Quicksand',
        'Quintessential',
        'Qwigley',
        'Racing Sans One',
        'Radley',
        'Raleway',
        'Raleway Dots',
        'Rambla',
        'Rammetto One',
        'Ranchers',
        'Rancho',
        'Rationale',
        'Redressed',
        'Reenie Beanie',
        'Revalia',
        'Ribeye',
        'Ribeye Marrow',
        'Righteous',
        'Risque',
        'Roboto',
        'Roboto Condensed',
        'Roboto Slab',
        'Rochester',
        'Rock Salt',
        'Rokkitt',
        'Romanesco',
        'Ropa Sans',
        'Rosario',
        'Rosarivo',
        'Rouge Script',
        'Ruda',
        'Rufina',
        'Ruge Boogie',
        'Ruluko',
        'Rum Raisin',
        'Ruslan Display',
        'Russo One',
        'Ruthie',
        'Rye',
        'Sacramento',
        'Sail',
        'Salsa',
        'Sanchez',
        'Sancreek',
        'Sansita One',
        'Sarina',
        'Satisfy',
        'Scada',
        'Schoolbell',
        'Seaweed Script',
        'Sevillana',
        'Seymour One',
        'Shadows Into Light',
        'Shadows Into Light Two',
        'Shanti',
        'Share',
        'Share Tech',
        'Share Tech Mono',
        'Shojumaru',
        'Short Stack',
        'Siemreap',
        'Sigmar One',
        'Signika',
        'Signika Negative',
        'Simonetta',
        'Sintony',
        'Sirin Stencil',
        'Six Caps',
        'Skranji',
        'Slackey',
        'Smokum',
        'Smythe',
        'Sniglet',
        'Snippet',
        'Snowburst One',
        'Sofadi One',
        'Sofia',
        'Sonsie One',
        'Sorts Mill Goudy',
        'Source Code Pro',
        'Source Sans Pro',
        'Special Elite',
        'Spicy Rice',
        'Spinnaker',
        'Spirax',
        'Squada One',
        'Stalemate',
        'Stalinist One',
        'Stardos Stencil',
        'Stint Ultra Condensed',
        'Stint Ultra Expanded',
        'Stoke',
        'Strait',
        'Sue Ellen Francisco',
        'Sunshiney',
        'Supermercado One',
        'Suwannaphum',
        'Swanky and Moo Moo',
        'Syncopate',
        'Tangerine',
        'Taprom',
        'Tauri',
        'Telex',
        'Tenor Sans',
        'Text Me One',
        'The Girl Next Door',
        'Tienne',
        'Tinos',
        'Titan One',
        'Titillium Web',
        'Trade Winds',
        'Trocchi',
        'Trochut',
        'Trykker',
        'Tulpen One',
        'Ubuntu',
        'Ubuntu Condensed',
        'Ubuntu Mono',
        'Ultra',
        'Uncial Antiqua',
        'Underdog',
        'Unica One',
        'UnifrakturCook',
        'UnifrakturMaguntia',
        'Unkempt',
        'Unlock',
        'Unna',
        'VT323',
        'Vampiro One',
        'Varela',
        'Varela Round',
        'Vast Shadow',
        'Vibur',
        'Vidaloka',
        'Viga',
        'Voces',
        'Volkhov',
        'Vollkorn',
        'Voltaire',
        'Waiting for the Sunrise',
        'Wallpoet',
        'Walter Turncoat',
        'Warnes',
        'Wellfleet',
        'Wendy One',
        'Wire One',
        'Yanone Kaffeesatz',
        'Yellowtail',
        'Yeseva One',
        'Yesteryear',
        'Zeyada',
    );
    $fonts = array();
    foreach ($tmp as $key => $value) {
        $fonts[$value] = $value;   
    }   
    return $fonts;
}

function gavias_castron_render_option_font(){
    $fonts = gavias_castron_fonts();
    $output = '';
    foreach ($fonts as $key => $value) {
        $output .= '<option value="'.$key.'">' . $value . '</option>';
    }
    return $output;
}

function gavias_castron_typography_font_styles($option, $selectors) {
    $output = $selectors . ' {';
    if(isset($option['face']) && $option['face']){
        $output .= 'font-family:' . $option['face'] . '; ';
    }    
    if(isset($option['weight']) && $option['weight']){
        $output .= 'font-weight:' . $option['weight'] . '; ';
    }
    if(isset($option['size']) && $option['size']){
        $output .= 'font-size:' . $option['size'] . '; ';
    }
    $output .= '}';
    $output .= "\n";
    return $output;
}

function gavias_castron_typography_enqueue_google_font($font) {
 
    if($font && $font != "---"){
        if(array_search($font, array_keys(gavias_castron_fonts())) > 17){
            $font = str_replace(" ", "+", $font);
            return "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=$font:100,300,400,600,800,900\"/>\n";
        }
    } 
    return '';   
}

function gavias_castron_links_typography_font($json){
    if($json === null) return '';
    
    $links_fonts = '';
    $customize = (array)json_decode($json, true);
    if(isset($customize['font_family_primary']) && $customize['font_family_primary']){
        $links_fonts .= gavias_castron_typography_enqueue_google_font($customize['font_family_primary']);
    }
    if(isset($customize['font_family_second']) && $customize['font_family_second']){
        $links_fonts .= gavias_castron_typography_enqueue_google_font($customize['font_family_second']);
    }
    return $links_fonts;
}


function gavias_castron_options_patterns(){
    $output = '';
    $file_path = drupal_get_path('theme', 'gavias_castron');
    $list_file = glob($file_path . '/images/patterns/*.{jpg,png,gif}', GLOB_BRACE);
   
    foreach ($list_file as $key => $file) {
      if(basename($file)){
        $file_url = $file_path . 'images/patterns/' .  basename($file); 
        $output .= '<option value = "'.basename($file).'">'.basename($file).'</option>';
      } 
    }
    return $output;
}