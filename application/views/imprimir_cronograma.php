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
            width: 210mm;
            min-height: 297mm;
            padding: 5mm;
            margin: 1mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .text-center{text-align: center;}
        @page {
            size: 8.3in 11.7in;
            margin: 0;
        }
        @media print {
        html, body {
            width: 210mm;
            height: 295mm;
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

    th {
      padding: 8px;
      text-align: center;
      border-top: 1px solid #313233;
    }
    td {
      padding: 8px;
      text-align: center;
    }

    </style>
</head>
<body onload="window.print();">
    <div class="page">
        <h1><p class="text-center">ESTUDIO JURÍDICO<br>"MONTAÑEZ"</p></h1>
        <p class="text-center">Somos una firma de abogados especializados en servicios de asesoría jurídica en Familia, Laboral, Comercial, Administrativo, Penal y Civil.</p>
        <p class="text-center">CRONOGRAMA DE PAGOS DE LA ASESORIA LEGAL DEL CLIENTE</p>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>A cuenta</th>
                    <th>Saldo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($info as $value) { ?>
                <tr>
                    <td><?php echo $value['fecha'];?></td>
                    <td><?php echo $value['acuenta'];?></td>
                    <td><?php echo $value['saldo'];?></td>
                    <td><?php echo $value['total'];?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <ol>
            <li><p>INCUMPLIR EL PAGO POR SERVICIO DE ASESORIA, DESPUES DE LA FECHA PACTADA SE IMPONDRA UNA MULTA DE S/5.00 DIARIOS, BAJO APERCIBIMIENTO DE INCREMENTARSELE S/100.00 DE TRANSCURRIDO POR DOS SEMANAS Y UN PAGO EQUIVALENTE A UNA CUOTA MENSUAL DE SU PROCESO SI EL PAGO SE ENCUENTRA INCUMPLIDO POR UN MES.</p></li>
            <li><p>EL PAGO MENSUAL SE REALIZARA ANTES DE BRINDARLE LA INFORMACION. SI EL CLIENTE ES MOROSO EL DOCTOR NO SE HARA RESPONSABLE DEL CASO HASTA QUE SE CUMPLA CON SU OBLIGACION DE PAGO, ASIMISMO SI ESTE LLEGARA A REACTIVAR EL SERVICIO MENDIANTE EL PAGO, EL PROCESO DE LA CONTINUIDAD DE SU CASO SERA PROPORCIONAL A LA DEMORA.</p></li>
            <li><p>INCUMPLIR EL PAGO POR SERVICIO DE ASESORIA, DESPUES DE DOS MESES DE LA FECHA PACTADA SE PROCEDERA A LA CANCELACION DEL SERVICIO LEGAL E INCINERACION DE SUS DOCUMENTOS, BAJO SU RESPONSABILIDAD.</p></li>
        </ol>
        <br><br><br><br><br><br><br>
        <table>
          <tr>
            <th>ABOGADO</th>
            <th>CLIENTE (TITULAR DE ACUERDO)</th>
          </tr>
          <tr>
            <td></td>
            <td></td>
          </tr>
        </table>
        <br>
        <p class="text-center">Av. José Carlos Mariategui Lote 59 UCV "5"<br>
        Zona "A" Huaycán<br>
        RPC: 997 859084 - RPM: 945 931861 <br> 
        www.abogadosejm.com</p>
    </div>
</body>
</html>