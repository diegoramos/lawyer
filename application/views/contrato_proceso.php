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
    	<p class="text-center">CONTRATO N°: <?php echo $info->proceso_id; ?></p>
        <p class="text-center">OBLIGACIONES DEL SERVICIO LEGAL</p>
        <ol>
            <li><p>EL PRESENTE SERVICIO LEGAL ESTA DIRIGIDO PARA CLIENTES QUE LLEVAN UN SERVICIO PERMANTENTE DE SU CASO LEGAL, DESDE SU INICIO HASTA EL FINAL, COMO TAMBIEN DE UN PROCESO YA AVANZADO HASTA EL FINAL, LA MISMA QUE TAMBIEN DEPENDERA DEL ACUERDO ENTRE CLIENTE Y EL ABOGADO Y SEGUN CONFORME LO ESTIPULA SU CRONOGRAMA DE PAGOS. ASIMISMO, EN LOS PARRAFOS PRECEDENTES SE ENUMERA LAS SIGUIENTES OBLIGACIONES QUE SERAN DE CUMPLIMIENTO OBLIGATORIO POR PARTE DEL CLIENTE, O EN TODO CASO SE PROCEDERA A LA DESACTIVACION DEL SERVICIO LEGAL O LA RENUNCIA DEL PROCESO.</p></li>
            <li><p>EL CLIENTE SE OBLIGA EN CUMPLIR CON LAS INDICACIONES SEÑALADAS POR EL ABOGADO DURANTE EL PROCESO, BAJO SU RESPONSAILBIDAD DE OMCUMPLIRLAS Y QUE REPERCUTAN EN SU CASO.</p></li>
            <li><p>EL CLIENTE DEBE PAGAR O ESTAR AL DIA EN SUS PAGOS PARA QUE EL ABOGADO LE BRINDE LA INFORMACION DE SU CASO.</p></li>
            <li><p>EL ABOGADO NO ASUME RESPONSABILIDAD POR EL PROCESO, CUANDO EL CLIENTE SE ENCUENTRA MOROSO EN SUS PAGOS POR SERVICIO DE ASESORIA. ASIMISMO SI ESTE LLEGARA A REACTIVAR EL SERVICIO MENDIANTE EL PAGO, EL PROCESO DE LA CONTINUIDAD DE SU CASO SERA PROPORCIONAL A LA DEMORA.</p></li>
            <li><p>EL ABOGADO NO SE HACE RESPONSABLE POR LOS DOCUMENTOS, ESCRITOS, SUGERENCIAS Y ORDENES QUE SOLICITE EL CLIENTE.</p></li>
            <li><p>EL CLIENTE SE COMPROMETE EN CUMPLIR PLENAMENTE SU CRONOGRAMA DE PAGOS POR ASESORIA, EN CASO DE INCUMPLIMIENTO EL ABOGADO RENUNCIARA AL PROCESO. (PARA CLIENTES QUE LLEVAN PROCESO, LUEGOS DE DOS MESES CONSECUTIVOS DE MOROSIDAD). POR OTRO LAGO.</p></li>
            <li><p>EL ABOGADO NO REALIZA DEVOLUCIONES POR NINGUN MOTIVO, SOLO SE PUEDE PACTAR CON EL CLIENTE OTRO SERVICIO DE ASESORIA.</p></li>
            <li><p>EL ABOGADO NO REALIZA DEVOLUCIONES POR NINGUN MOTIVO, SOLO SE PUEDE PACTAR CON EL CLIENTE OTRO SERVICIO DE ASESORIA.</p></li>
            <li><p>EL ABOGADO ES RESPONSABLE DEL PROCESO DEL CLIENTE, SEGÚN LO PACTADO EN EL CASO Y TERMINOS QUE SE LLEGA CON EL CLIENTE, MAS NO ASUME RESPONSABILIDAD POR COSAS ADICIONALES A LO PACTADO.</p></li>
            <li><p>EL ABOGADO NO ASUME RESPONSABILIDAD POR EL PROCESO, NI TAMPOCO POR LOS DOCUMENTOS Y/O ESCRITOS QUE DEJO Y ABANDONO EL CLIENTE DESPUES DE TRANSCURRIDO LOS DOS MESES, NO TENIENDO DERECHO A DEVOLUCION ALGUNA EL CLIENTE.</p></li>
            <li><p>EL CLIENTE SE OBLIGA A MANTENER ACTUALIZADA SU INFORMACION CORRESPONDIENTE A: TELEFONO Y/O CORREO. A FIN QUE SE LE INFORME DETALLADAMENTE SOBRE SU PROCESO, BAJO RESPONSABILIDAD DEL CLIENTE DE NO UBICARSELE POR UN TERMINO DE 2 MESES.</p></li>
            <li><p>EL CLIENTE SE OBLIGA A GUARDAR LO RECIBOS DE PAGO  QUE SE LE DA AL MOMENTO DE CANCELAR SU PAGO Y/O CONSERVAR SUS BOUCHER PARA EVITAR CUALQUIER TIPO DE RECLAMOS O MALOS ENTENDIDOS.</p></li>
            <li><p>EL CLIENTE SE OBLIGA QUE LOS DOCUMENTOS ENTREGADOS AL ABOGADO, CORRESPONDIENTES A SU PROCESO, SON VERDADEROS Y DE BUENA FE, BAJO SU ENTERA RESPONSABILIDAD Y FUTURAS ACCIONES LEGALES QUE CONLLEVARIAN SOBRE LA FALSEDAD DE LOS MENCIONADOS DOCUMENTOS.</p></li>
            <li><p>EL ABOGADO ARCHIVARA EL CASO, CUANDO EL CLIENTE DECIDA CONTRATAR LOS SERVICIOS PROFESIONALES DE OTRO ASESOR LEGAL (ABOGADO), EN LO CORRESPONDIENTE A SU PROCESO, SEA POR: ESCRITO, ASESORAMIENTO Y/O APERSONAMIENTO U OTROS.</p></li>
            <li><p>EL CLIENTE TOMA CONOCIMIENTO, QUE LOS PAGOS MENSUALES NO SE RELACIONAN CON EL AVANCE DEL PROCESO, POR LO TANTO EL USUARIO SE COMPROMETE A CUMPLIR CON EL PAGO DEL PROCESO, ASI COMO TAMBIEN EL ABOGADO SE COMPROMETE A TERMINAR EL PROCESO, EXCEPTO QUE EL CLIENTE INCUMPLIERA CUALQUIERA DE LA RECOMENDACIONES ANTES MENCIONADAS, PARA LA CUAL TAMBIEN CONLLEVARA A RESCINDIR EL SERVICIO, POR PARTE DEL ABOGADO.</p></li>
            <li><p>EL CLIENTE SE ENCUENTRA EN LA OBLIGACION DE CUMPLIR CON LO ORDENADO POR SU ABOGADO, RESPECTO A SU PROCESO (COMO SON TRAER: DOCUMENTOS, TESTIGOS, VIDEOS Y/O FOTOS, ETC) E IGUALMENTE CON CUMPLIR EN LA PUNTUALIDAD DE LO ORDENADO POR EL JUZGADO (CORRESPONDIENTE A SU JUICIO). SIN EMBARGO, SI DURANTE EL PROCESO EL CLIENTE INCUMPLE O POR SU CAUSA DE ESTE SE PIERDE EL JUICIO, EL ABOGADO SE ENCONTRARA EN LA FACULTAD DE RENUNCIAR AL PROCESO O COMO TAMBIEN DE VOLVERLE A COBRAR EL MONTO DE UN NUEVO JUICIO PARA PODER OTRA VEZ LLEVARLO.</p></li>
        </ol>
        <br><br><br><br><br><br><br>
        <table>
          <tr>
            <th>ABOGADO</th>
            <th>CLIENTE (TITULAR DE ACUERDO)</th>
          </tr>
          <tr>
            <td><?php echo $info->first_name.' '.$info->last_name; ?></td>
            <td><?php echo $info->cust_first.' '.$info->cust_last; ?></td>
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