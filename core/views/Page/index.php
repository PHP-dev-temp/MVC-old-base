<h2><?= $data['text'] ?></h2>
<ul>
    <?php foreach ($data['menu_links'] as $link){ ?>
    <li><a href="<?= $link['link'] ?>"><?= $link['text'] ?></a></li>
    <?php }?>
</ul>