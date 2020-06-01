<section id="box-slides" class="">

<?php
  foreach ($slides as $key => $slide) {
    echo '<div class="item'. (($key == 0) ? ' active' : '') .'">' . PHP_EOL;

    if ($slide['link']) {
      echo '<a href="'. htmlspecialchars($slide['link']) .'">' . PHP_EOL;
    }

    echo '<img src="'. document::href_link($slide['image']) .'" alt="" /></a>' . PHP_EOL;

    if (!empty($slide['caption'])) {
      echo '<div class="carousel-caption">'. $slide['caption'] .'</div>' . PHP_EOL;
    }

    if ($slide['link']) {
      echo '</a>' . PHP_EOL;
    }

    echo '</div>' . PHP_EOL;
  }
?>

</section>
