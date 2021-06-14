
    <?php

    if ($events_hpp > 0) {
    $category = '';
    $i = 0;
    foreach ($events_hpp as $row) :

        if ($category != $row->hpp_category) {
            
            if ($i != 0) { ?>
                </tbody>
                </table>
                </div>
                </div>
            <?php }
    ?>

    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="pl-1"><b><?php echo $row->hpp_category;?></b></div>

            <table class="table table-sm table-borderless m-0" style="width: 100%">
                <thead>
                    <tr class="text-center">
                        <th style="width:65%">Keterangan</th>   
                        <th style="width:15%">Jumlah</th>
                        <th style="width:20%">Asumsi</th> 
                    </tr>
                </thead>
                <tbody>

    <?php
        } ?>
        <tr>
            <td><?php echo $row->item_name;?></td>
            <td class="text-center"><?php echo $row->item_qty . ' ' . $row->item_satuan;?></td>
            <td class="text-center"><?php echo number_format($row->item_price*$row->item_qty);?></td>
        </tr>
    <?php
    $i = 1;
    $category = $row->hpp_category;
    endforeach; } ?>