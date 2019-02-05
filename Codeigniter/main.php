<div class='itemListBox'>
    <?php
    if (isset($items) && !empty($items)) {
        $i = 0;
        foreach ($items as $key => $item) {
            if ($i % 2 == 0) {
                $even_clr = 'clr';
                echo "<div class='clr'></div>";
            }
            ?>
            <div class='itemBox le <?php echo($key % 2 ? 'itemEven' : 'itemOdd'); ?>'>
                <div class='p-10 splitmegrd'>
                    <div class='itemBoxinfo'>
                        <h4>
                            <a href='<?php echo site_url($this->ln . '/' . $controller . '/show/' . $item->id); ?>'><?php echo $item->title; ?></a>
                        </h4>
                        <?php

                        $img = $ImagesServerPath . $controller . '/' . $item->cat_id . '/' . $item->img;
                        if (is_file($img)) {
                            $img = $ShowImagesPath . $controller . '/' . $item->cat_id . '/' . $item->img;
                            ?>
                            <img src=<?php echo $img; ?> alt='<?php echo $item->title; ?>' width='85' class='le'/>
                            <?php
                        }
                        ?>
                        <?php echo $item->short; ?>
                        <div class='clr'></div>
                    </div>
                    <div class='le'>
                        <div class="average_rate rate_spl" data-average="<?php echo $item->average_rate; ?>"
                             data-id="<?php echo $item->id; ?>" data-module="<?php echo $controller; ?>"></div>
                    </div>
                    <span class='download_info'><?php echo isset($item->download) ? "(Բեռնվել է {$item->download} անգամ)" : ''; ?></span>
                    <div class='clr'></div>
                </div>
            </div>
            <?php
            $i++;
        }
        echo "<div class='clr'></div>";
        echo isset($pagination) ? $pagination : '';
    } else {
        echo isset($lbl->no_results) ? $lbl->no_results : '';
    }
    ?>
</div>