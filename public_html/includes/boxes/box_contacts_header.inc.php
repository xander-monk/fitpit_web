<?php
  functions::draw_lightbox();

  $box_contacts_header = new ent_view();

  echo $box_contacts_header->stitch('views/box_contacts_header');
