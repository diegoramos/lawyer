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
    	<p class="text-center">RECIBO N°: <?php echo $info->tramite_id; ?></p>
        <table>
            <tr>
                <td>Fecha/Hora:</td>
                <td><?php echo $info->fecha_tram.' '.$info->hora_tram; ?></td>
            </tr>
            <tr>
                <td>Recibi de:</td>
                <td><?php echo $info->cust_first.' '.$info->cust_last; ?></td>
            </tr>
            <tr>
                <td>DNI:</td>
                <td><?php echo $info->cust_dni; ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td>POR CONCEPTO DE:</td>
                <td><?php echo $info->tipo_tramite; ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="right">A CUENTA</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->a_cuenta_tram; ?></td>
            </tr>
            <tr>
                <td class="right">SALDO</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->saldo_tram; ?></td>
            </tr>
            <tr>
                <td class="right">TOTAL</td>
                <td class="right">S/.</td>
                <td class="right"><?php echo $info->total_tram; ?></td>
            </tr>
        </table>
        <?php $this->load->helper('number'); ?>
        <p>SON: <?php
        $exchange_name = "SOLES";
        echo num_to_letras($info->total_tram,"Y",letra_modena($exchange_name)); ?><br>
        EFECTIVO S/ : <?php  echo ($info->a_cuenta_tram == 0.00) ? $info->total_tram : $info->a_cuenta_tram; ?></p>
        <p class="text-center"><img src="<?php echo base_url() ?>upload/<?php echo $info->tramite_id.'.png';?>" alt="QR-code" class="left"/></p>
        <p class="text-center">Av. José Carlos Mariategui Lote 59 UCV "5"<br>
        Zona "A" Huaycán<br>
        RPC: 997 859084 - RPM: 945 931861 <br> 
        www.abogadosejm.com</p>
    </div>
</body>
</html>