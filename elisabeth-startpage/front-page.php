<?php
function resource($name) {
	echo(plugin_dir_url( __FILE__ ) . "frontpage-resources/$name");
}

global $wpdb;

$table_name = $wpdb->prefix . "elisabeth_startpage";

$sql = "SELECT * FROM `$table_name`";
$posts = $wpdb->get_results($sql);
$aktuellt = "";
$om_mig = "";
foreach($posts as $post) {
         if($post->description == "aktuellt") {
                $aktuellt = $post->text;
         }
         if($post->description == "om mig") {
                $om_mig = $post->text;
         }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php resource("frontpage-style.css"); ?>">
</head>
<body>

<div id="header">
    <div id="header_img_container">
    <img id="header_img" src="<?php resource("cropped-loggatest-hemsida.png"); ?>">
    </div>
</div>


<!-- -------------------
    Menyn för mobiler
   --------------------- -->

<div id="small">
    <div id="small_content">
        <div id="small_img">
	<img src="<?php resource("elisabeth-on-top.jpg"); ?>" />
        </div>
        <div id="t0" class="mobile-transition"></div>
        <nav>
            <div id="small_menu">
                <div class="small_menu_item">
		<a href="http://kreativapsykologen.com/psykologi/">
                        Psykologi
                    </a>
                </div>
                <div class="small_menu_item">
                    <a href="http://kreativapsykologen.com/teater/">
                        Teater
                    </a>
                </div>
                <div class="small_menu_item">
                    <a href="http://kreativapsykologen.com/klimat/">
                        Klimat
                    </a>
		        </div>
                <div class="small_menu_item">
                    <a href="http://kreativapsykologen.com/konst/">
                        Konst
                    </a>
                </div>
                <div class="small_menu_item">
                    <a href="http://kreativapsykologen.com/pyssel/">
                        Pyssel
                    </a>
                </div>
            </div>
        </nav>
    </div>
</div>


<!-- -------------------
    Menyn (kategori-knapparna) för datorer
   --------------------- -->

<div id="large">
    <div id="large_content">
    <img id="center_img" src="<?php resource("elisabeth-in-center.jpg"); ?>" />
        <nav>
            <div class="large_menu_item">
                <div class="large_menu_item_text">
                    <a href="http://kreativapsykologen.com/psykologi/">Psykologi</a>
                </div>
                <div class="large_menu_item_image">
		<a href="http://kreativapsykologen.com/psykologi/"><img src="<?php resource("kategori-psykologi.jpg"); ?>"></a>
                </div>
            </div>
            <div class="large_menu_item">
                <div class="large_menu_item_text">
                    <a href="http://kreativapsykologen.com/teater/">Teater</a>
                </div>
                <div class="large_menu_item_image">
		<a href="http://kreativapsykologen.com/teater/"><img src="<?php resource("kategori-teater.jpg"); ?>"></a>
                </div>
            </div>
            <div class="large_menu_item">
                <div class="large_menu_item_text">
                    <a href="http://kreativapsykologen.com/klimat/">Klimat</a>
                </div>
                <div class="large_menu_item_image">
		<a href="http://kreativapsykologen.com/klimat/"><img src="<?php resource("kategori-planta.jpg"); ?>"></a>
                </div>
            </div>
            <div class="large_menu_item">
                <div class="large_menu_item_text">
                    <a href="http://kreativapsykologen.com/konst/">Konst</a>
                </div>
                <div class="large_menu_item_image">
		<a href="http://kreativapsykologen.com/konst/"><img src="<?php resource("kategori-konst.jpg"); ?>"></a>
                </div>
            </div>
            <div class="large_menu_item">
                <div class="large_menu_item_text">
                    <a href="http://kreativapsykologen.com/pyssel/">Pyssel</a>
                </div>
                <div class="large_menu_item_image">
		<a href="http://kreativapsykologen.com/pyssel/"><img src="<?php resource("kategori-pyssel.jpg"); ?>"></a>
                </div>
            </div>
        </nav>
    </div>
</div>

<div id="t1" class="mobile-transition"></div>


<!-- -------------------
    Aktuellt-rutan
   --------------------- -->

<div id="aktuellt">
    <h1>Aktuellt</h1>
    <?php echo($aktuellt); ?>
</div>

<div id="t2" class="mobile-transition"></div>


<!-- -------------------
    Kontaktinformation
   --------------------- -->

<div id="contact_me">
<p>Vill du kontakta mig för ett uppdrag eller har du en fråga att ställa, tveka inte att höra av dig!</p>

<p>Mail:<br>
<a href="mailto:kreativapsykologen@gmail.com">kreativapsykologen@gmail.com</a></p>

<p>Instagram:<br>
<a href="https://www.instagram.com/kreativapsykologen/">@kreativapsykologen</a></p>

</div>

<div id="t3" class="mobile-transition"></div>


<!-- -------------------
    Om mig-rutan
   --------------------- -->

<div id="about_me">
    <h1>Elisabeth Brånsgård</h1>
    <?php echo($om_mig); ?>
</div>

<script src="<?php resource("frontpage-script.js"); ?>"></script>
</body>
</html>
