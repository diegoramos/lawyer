<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Factura</title>
    <style>
		body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 8pt 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;;
	    }
	    * {
	        box-sizing: border-box;
	        -moz-box-sizing: border-box;
	    }
	    .page {
	        width: 70mm;
	        min-height: 150mm;
	        padding: 2mm;
	        margin: 1mm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .text-center{text-align: center;}
	    @page {
            size: 2.75591in 7.08661in;
	        margin: 0;
	    }
	    @media print {
        html, body {
            width: 70mm;
            height: 150mm;
        }
        .page {
	            margin: 0;
	            border: initial;
	            border-radius: initial;
	            width: initial;
	            min-height: initial;
	            box-shadow: initial;
	            background: initial;
	            page-break-after: always;
        	}
    	}
	table{
	  width: 100%;
	  border-collapse: collapse;
	}
	.right{
		text-align: right;
	}
	.center{
		text-align: center;
	}

    </style>
</head>
<body onload="window.print();">
    <div class="page">
        <h1><p class="text-center">ESTUDIO JURÍDICO<br>"MONTAÑEZ"</p></h1>
        <p class="text-center">Somos una firma de abogados especializados en servicios de asesoría jurídica en Familia, Laboral, Comercial, Administrativo, Penal y Civil.</p>
    	<p class="text-center">RECIBO N°: <?php echo $info->payment_id; ?></p>
        <table>
            <tr>
                <td>Fecha/Hora:</td>
                <td><?php echo $info->fecha; ?></td>
            </tr>
            <tr>
                <td>Recibi de:</td>
                <td><?php echo $info->first_name.' '.$info->last_name; ?></td>
            </tr>
            <tr>
                <td>DNI:</td>
                <td><?php echo $info->dni; ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td>POR CONCEPTO DE:</td>
                <td><?php echo $info->tipo_proceso; ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="right">A CUENTA</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->acuenta; ?></td>
            </tr>
            <tr>
                <td class="right">SALDO</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->saldo; ?></td>
            </tr>
            <tr>
                <td class="right">TOTAL</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->total; ?></td>
            </tr>
        </table>
        <?php $this->load->helper('number'); ?>
        <p>SON: <?php
        $exchange_name = "SOLES";
        echo num_to_letras($info->total,"Y",letra_modena($exchange_name)); ?><br>
        EFECTIVO S/ : <?php  echo ($info->acuenta == 0.00) ? $info->total : $info->acuenta; ?></p>
        <p class="text-center"><img src="<?php echo base_url() ?>upload/<?php echo $info->payment_id.'.png';?>" alt="QR-code" class="left"/></p>
        <p class="text-center">Av. José Carlos Mariategui Lote 59 UCV "5"<br>
        Zona "A" Huaycán<br>
        RPC: 997 859084 - RPM: 945 931861 <br> 
        www.abogadosejm.com</p>
    </div>
</body>
</html>