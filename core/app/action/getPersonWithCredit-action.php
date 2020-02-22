<?php
	if (isset($_GET['q'])) {
		$persons = PersonData::getClientsWithCreditByName($_GET['q']);
		$text = "";
        unset($sells);
        $sells=0;
        ?>
        <table class="table table-bordered table-hover">
      <thead>
      <th style="text-align: center;">NOMBRE COMPLETO CLIENTE</th>
      <th style="text-align: center;">DIRECCION</th>
      <th style="text-align: center;">TELEFONO</th>
      <th style="text-align: center;">CREDITO</th>
      <th style="text-align: center;">SALDO PENDIENTE</th>
      <th></th>
      </thead>
      <div>
        <?php
          foreach($persons as $person)
          {
            unset($sells);
            $sells=0;
            ?>
            <tr style="border-bottom: none !important;background-color:#3c8dbc;color: #fff;">
              <td style="text-align: center; font-weight: bold;"><?php echo strtoupper($person->name." ".$person->lastname); ?></td>
              <td style="text-align: center;"><?php echo strtoupper($person->address1); ?></td>
              <td style="text-align: center;"><?php echo $person->phone1; ?></td>
              <td style="text-align: center;"><?php if($person->has_credit){ echo "<i class='fa fa-check'></i>"; }; ?></td>
              <td style="text-align: center;">$ <?php echo number_format(PaymentData::sumByClientId($person->id)->total,2,".",","); ?></td>
              <td style="width:230px;text-align: center;">
                <a href="index.php?view=paymenthistory&id=<?php echo $person->id;?>" class="btn btn-default btn-xs">HISTORIAL</a>
              </td>
            </tr>
            <?php
            $sells = SellData::getCreditsByClientId($person->id);
            if(count($sells)>0)
            {
            ?>
              <tr>
                <thead>
                  <th></th>
                  <th style="text-align:center;">TIPO DE PAGO</th>
                  <th style="text-align:center;">PRODUCTOS</th>
                  <th style="text-align:center;">TOTAL</th>
                  <th style="text-align:center;">FECHA</th>
                  <th style="text-align:center;"></th>
                </thead>
              </tr>
              <?php
              foreach($sells as $sell)
              {
              ?>
              <tr>
                <td style="text-align:center;">
                  <a href="index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
                    <i class="glyphicon glyphicon-eye-open"></i>
                  </a>
                   #<?php echo $sell->ref_id; ?>
                </td>

                <td style="text-align: center;"><?php echo strtoupper($sell->getP()->name); ?></td>
                <td style="text-align: center;"><?php echo strtoupper($sell->getD()->name); ?></td>
                <td style="text-align: center;">
                <?php
                $total= $sell->total-$sell->discount;
                  echo "<b>$ ".number_format($total,2,".",",")."</b>";
                ?>      
                </td>
                <td style="text-align: center;"><?php echo $sell->created_at; ?></td>
                <td style="text-align: center;">
                  <a href="index.php?view=makepayment&id=<?php echo $person->id;?>&sell=<?php echo $sell->id;?>" class="btn btn-default btn-xs">
                    REALIZAR PAGO
                  </a>
                </td>
              </tr>
              <?php
              }
            }
          }
        ?>
        
      </div>
      </table>

            
 <?php        
	}

?>