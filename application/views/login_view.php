<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistema de Gestión</title>

    <link href="<?php base_url()?>assets/login/css/style.default.css" rel="stylesheet">

</head>

<body class="signin">


<section>

    <div class="panel panel-signin">
        <div class="panel-body">
            <button id="error" class="btn btn-danger btn-block"></button>
            <button id="redirect" class="btn btn-success btn-block "><i class="fa fa-check"></i> Ingresaste correctamente. Redirigiendo ....</button>
            <?php
            $msg=$this->session->userdata('message');
            if($msg){
                echo "<button id='flash' class='btn btn-info btn-block'><i class='fa fa-thumbs-up'></i> ".$msg."</button>";
                $this->session->unset_userdata('message');
            }
            ?>
            <h2 class="text-center">Sistema de Gestión</h2>
            <div class="text-center" style="padding-bottom: 10px">
                <img src="<?php echo base_url(); ?>assets/dist/img/logo.jpg" height='150px' width='150px'>              
            </div>
            <form id="login" action="<?php echo base_url(); ?>login/check_login" redirect="<?php echo ($this->input->get('continue')!='') ? $this->input->get('continue') :'dashboard'; ?>" method="post" >
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="input-group mb15">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div><!-- input-group -->
                <div class="input-group mb15">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div><!-- input-group -->
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="ckbox ckbox-primary mt10">
                            <input type="checkbox" id="rememberMe" value="1">
                            <label for="rememberMe">Recuerdame</label><br>
                        </div>
                    </div>
                    <br>
                    <button id="sign" type="submit" class="btn btn-success btn-block">Ingresar</button>
                    <button id="auth" class="btn btn-info btn-block">Autenticando <i class='fa fa-spinner'></i></button>
                    <br>
                    <a href=""><div class="text-center">Olvidé mi contraseña</div></a>
                </div>
            </form>
        </div>
    </div><!-- panel -->
</section>
<script src="<?php base_url()?>assets/plugins/jQuery/jquery-3.3.1.min.js"></script>
<script src="<?php base_url()?>assets/dist/js/login.js"></script>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>
</body>
</html>
