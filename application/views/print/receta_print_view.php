<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tahoma";
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 20mm;
        margin: 10mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .subpage {
        padding: 1cm;
        /*border: 5px red solid;*/
        height: 257mm;
        outline: 2cm #FFEAEA solid;
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        html, body {
            width: 210mm;
            height: 297mm;        
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
	</style>
</head>
<body>
	<div class="book">
	    <div class="page">
	        <div class="subpage">
	        	<div align="center">
	        		<div class="" id="">
	        			<table class="" style="width: 100%">
	        				<tr>
	        					<td colspan="2" style="width: 40%;height: 100px;text-align: ;"><font style="font-size: 21px;">Medicos S.A.C.</font></td>
	        					<td style="width: 60%;text-align: center;">
	        						<table style="width: 100%">
	        							<tr>
	        								<td style="text-align: right;">
	        									<font style="font-weight: bold;font-size: 18px;">Dr(a): <?php echo $cita->name_doctor; ?></font>
	        								</td>
	        							</tr>
	        							<tr>
	        								<td style="text-align: right;">
	        									<font style="font-weight: bold;font-size: 16px;"><!-- Medico Veterinario --></font>
	        								</td>
	        							</tr>
	        							<tr>
	        								<td style="text-align: right;">
	        									<font style="font-weight: bold;font-size: 14px;">CMP: 2432</font>
	        								</td>
	        							</tr>
	        						</table>
	        					</td>
	        				</tr>
	        				<tr>
	        					<td colspan="3">
									&nbsp;        					
	        					</td>
	        				</tr>
	        				<tr>
	        					<td colspan="3">
	        						<font style="font-size:px;font-weight: bold;">Paciente: </font> <span><?php echo $cita->name_patient; ?></span>
	        					</td>
	        				</tr>
	        				<?php  $i=0; foreach ($receta as $key => $value): ?>
	        				<tr>
	        					<td colspan="3">
	        						Receta N° <?php $i++; echo $i; ?>
	        					</td>
	        				</tr>
	        				<tr >
	        					<td colspan="3" >
	        						<table style="width: 100%;border-collapse: collapse;" >
	        							<tr>
	        								<td style="width: 40%;border: 1px solid #000;"><font style="font-weight: bold;">Medicamento</font></td>
	        								<td style="width: 40%;border: 1px solid #000;"><font style="font-weight: bold;">Precentación</font></td>
	        								<td style="width: 10%;border: 1px solid #000;"><font style="font-weight: bold;">Cantidad</font></td>
	        							</tr>
	        							<tr>
	        								<td style="width: 40%;border-right: 1px solid #000;border-left: 1px solid #000;border-top: 1px solid #000;"><font><?php echo $value->medicamento; ?></font></td>
	        								<td style="width: 40%;border-right: 1px solid #000;border-left: 1px solid #000;border-top: 1px solid #000;"><font><?php echo $value->presentacion; ?></font></td>
	        								<td style="width: 10%;border-right: 1px solid #000;border-left: 1px solid #000;border-top: 1px solid #000;"><font><?php echo $value->cantidad; ?></font></td>
	        							</tr>
	        						</table>
	        						<table style="width: 100%;border-collapse: collapse;">
	        							<tr>
	        								<td style="width: 30%;border: 1px solid #000;"><font style="font-weight: bold;">Docis</font></td>
	        								<td style="width: 35%;border: 1px solid #000;"><font style="font-weight: bold;">Tiempo</font></td>
	        								<td style="width: 35%;border: 1px solid #000;"><font style="font-weight: bold;">Descripción</font></td>
	        							</tr>
	        							<tr>
	        								<td style="width: 30%;border: 1px solid #000;"><font><?php echo $value->dosis; ?></font></td>
	        								<td style="width: 35%;border: 1px solid #000;"><font><?php echo $value->tiempo; ?></font></td>
	        								<td style="width: 35%;border: 1px solid #000;"><font><?php echo $value->descripcion; ?></font></td>
	        							</tr>
	        						</table>

	        					</td>
	        				</tr>
	        				<tr>
	        					<td colspan="3">
	        						<!--<hr> -->&nbsp;
	        					</td>
	        				</tr>
	        				<?php endforeach ?>

	        				<tr>
	        					<td></td>
	        					<td></td>
	        					<td style="height: 90px;border: 1px solid #000"></td>
	        				</tr>
	        				<tr>
	        					<td></td>
	        					<td>Fecha: <?php echo $cita->start_date; ?></td>
	        					<td align="center">Firma del Medico</td>
	        				</tr>
	        			</table>
	        		</div>
	        	</div>
	        </div>    
	    </div>
	    <!-- EN CASO DE HACER DOBLE PAGINA
	    <div class="page">
	        <div class="subpage">Page 2/2</div>    
	    </div> -->
	</div>
</body>
<script type="text/javascript">
	window.print();
</script>
</html>