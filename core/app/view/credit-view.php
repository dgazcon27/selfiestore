<section class="content">
<div class="row">
  <div class="col-md-12">
  <h1>CREDITOS</h1>
  <h4>LISTA DE CLIENTES CON CREDITO</h4>
  <br>
  <?php
    $users = PersonData::getClientsWithCredit();
    if(count($users)>0){
      // si hay usuarios
      ?>
    <a href="./report/credit-word.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a>
    <a href="./report/credit-excel.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a>
    <br><br>
    <div class="box box-primary">
      <table class="table table-bordered table-hover">
      <thead>
      <th style="text-align: center;">NOMBRE COMPLETO CLIENTE</th>
      <th style="text-align: center;">DIRECCION</th>
      <th style="text-align: center;">TELEFONO</th>
      <th style="text-align: center;">CREDITO</th>
      <th style="text-align: center;">SALDO PENDIENTE</th>
      <th></th>
      </thead>
      <?php
      foreach($users as $user)
      {
        unset($sells);
        $sells=0;
        ?>
        <tr style="border-bottom: none !important;background-color:#3c8dbc;color: #fff;">
          <td style="text-align: center; font-weight: bold;"><?php echo strtoupper($user->name." ".$user->lastname); ?></td>
          <td style="text-align: center;"><?php echo strtoupper($user->address1); ?></td>
          <td style="text-align: center;"><?php echo $user->phone1; ?></td>
          <td style="text-align: center;"><?php if($user->has_credit){ echo "<i class='fa fa-check'></i>"; }; ?></td>
          <td style="text-align: center;">$ <?php echo number_format(PaymentData::sumByClientId($user->id)->total,2,".",","); ?></td>
          <td style="width:230px;text-align: center;">
            <a href="index.php?view=paymenthistory&id=<?php echo $user->id;?>" class="btn btn-default btn-xs">HISTORIAL</a>
          </td>
        </tr>
        <?php
        $sells = SellData::getCreditsByClientId($user->id);
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
              <a href="index.php?view=makepayment&id=<?php echo $user->id;?>&sell=<?php echo $sell->id;?>" class="btn btn-default btn-xs">
                REALIZAR PAGO
              </a>
            </td>
          </tr>
          <?php
          }
        }
      }
      ?>
      </table>
      </div>
      <?php
    }
    else
    {
      echo "<p class='alert alert-danger'>No hay clientes</p>";
    }
    ?>
  </div>
</div>
</section>